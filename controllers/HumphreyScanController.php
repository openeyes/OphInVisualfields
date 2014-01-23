<?php

/**
 * Taken and adapted from:
 *  http://www.yiiframework.com/wiki/175/how-to-create-a-rest-api/
 */
class HumphreyScanController extends BaseModuleController {

	/**
	 * Takes an XML source file that references a given image file, both
	 * in the same directory.
	 * 
	 * If only source directory is set, images are imported 'is situ' - used
	 * for example demonstrations.
	 * 
	 * When files are imported from an external source, specifying the destination
	 * directory ensures the files are moved from source to destination.
	 * 
	 * The XML file is parsed for data such as image file name, patient ID,
	 * DOB, eye, test type and strategy.
	 */
	function actionImport() {
		$dest_dir = null;

		$this->_checkAuth();
		$json = file_get_contents('php://input');
		$put_vars = CJSON::decode($json, true);

		// step 1 - check all parameters are sane AND valid:
		if (!isset($put_vars['src_dir'])) {
			$this->_sendResponse(500, 'Error: incorrect parameters', "import", "scan");
		} else {
			$src_dir = $put_vars['src_dir'];
		}
		if (!isset($put_vars['xml_file'])) {
			$this->_sendResponse(500, 'Error: incorrect parameters', "import", "scan");
		} else {
			$xml_file = $put_vars['xml_file'];
		}
		if (isset($put_vars['dest_dir'])) {
			$dest_dir = $put_vars['dest_dir'];
		}
		$src_file = $src_dir . '/' . $xml_file;
		if (!file_exists($src_file)) {
			$this->_sendResponse(400, sprintf("Error: Could not locate XML file '%s'", $src_file), "import", "scan");
		}
		$this->audit("import", "scan", $json);

		/* It's been known for transferred XML files to contain rogue data -
		 * in the form of non-ASCII characters. This will make the processing
		 * of the XML file barf. If it does contain illegal characters, we
		 * need to move it: */
		if (preg_match('/^[\x20-\x7e]*$/', file_get_contents($src_file))) {
			// since we can't read the file, we can't pull the name of the
			// file out - so obtain it from the XML file's name:
			$image_scan = basename($xml_file, ".xml")
					. "." . Yii::app()->params['visualfields.img_extension'];
			$this->moveToErrorDir($src_dir, $xml_file, $image_scan, "XML file contained illegal (non-ascii) characters");
			return;
		}

		// get the XML data:
		$data = file_get_contents($src_file);
		try {
			$xml_data = $this->getXmlData($data);
		} catch (Exception $ex) {
			// need to move files to another (error) location
			$image_scan = basename($xml_file, ".xml")
					. "." . Yii::app()->params['visualfields.img_extension'];
			$this->moveToErrorDir($src_dir, $xml_file, $image_scan, sprintf("Error: parsing file '%s': '%s'", $src_file, $ex->getMessage()));
			$this->_sendResponse(400, sprintf("Error: parsing file '%s': '%s'", $src_file, $ex->getMessage()), "import", "scan");
		}
		if (!array_key_exists('file_reference', $xml_data)) {
			$image_scan = basename($xml_file, ".xml")
					. "." . Yii::app()->params['visualfields.img_extension'];
			$this->moveToErrorDir($src_dir, $xml_file, $image_scan, sprintf("Error: parsing file '%s': '%s'", $src_file, $ex->getMessage()));
			$this->_sendResponse(400, sprintf("Error: XML file %s contained no PID", $xml_data['file_reference']), "import", "scan");
		}
		// The XML file is fine and hopefully valid, get the image file name:
		$image_file = $src_dir . '/' . $xml_data['file_reference'];
		if (!file_exists($image_file)) {
			// again, need to move XML file to another (error) location
			$this->moveToErrorDir($src_dir, $xml_file, $image_file, sprintf("Error: parsing file '%s': '%s'", $src_file, $ex->getMessage()));
			$this->_sendResponse(404, sprintf("Error: Could not find image file '%s' for XML source file '%s'", $xml_data['file_reference'], $src_file), "import", "scan");
		}
		// validate the PID against main config pattern:
		$pid = $xml_data['recorded_pid'];
		if (!preg_match(Yii::app()->params['hos_num_regex'], $pid)) {
			$this->moveToErrorDir($src_dir, $xml_file, $image_scan, sprintf("Error: Bad patient identifier in '%s': '%s'", $src_file, $ex->getMessage()));
			$this->_sendResponse(400, "Error: bad patient identifier.");
		}
		// if the patient doesn't exist, create them:
		// TODO check for lower/upper case!
		// Data check! Any checks to be done (check and amend bad chars?):
		// TODO - verify PID against the trust's hospital number:
		// CODE HERE - this is not verification of patient, it's verification that the hos num is correct for this trust:
		$patient = Patient::model()->find('hos_num=?', array($pid));
		if (!$patient && Yii::app()->params['visualfields.create_patient']) {
			$username = $_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME'];
			$this->createPatient($username, $xml_data);
		}
		// patient UID helps make their directory location:
		$uid = $this->getPatientUid($xml_data['recorded_pid']);
		// keep track and see if the files exist in the move-to locations
		// - need to be careful of over-writing files:
		$xml_file_exists = false;
		$image_file_exists = false;
		// move files:
		try {
			if ($dest_dir) {
				$dest_dir = $dest_dir . '/' . $xml_data['test_strategy'] . '/' . $uid;
				if (!file_exists($dest_dir)) {
					mkdir($dest_dir, 0777, true);
				}
				// TODO check that destinations do not already exist;
				// the exact action will depend on the users set up
				$xml_file_exists = file_exists($dest_dir . '/' . $xml_file);
				$image_file_exists = file_exists($dest_dir . '/' . $xml_data['file_reference']);
				$this->checkForDuplicates($xml_file_exists, $src_dir, $xml_file, $dest_dir);
				$this->checkForDuplicates($image_file_exists, $src_dir, $xml_data['file_reference'], $dest_dir);
			} else {
				// user has not specified dest; so dest and src are the same (import in situ):
				$dest_dir = $src_dir;
			}
		} catch (Exception $e) {
			$this->_sendResponse(400, sprintf("Error importing file: %s", $e->getMessage()), "import", "scan");
		}
		$dir = $this->addFsDirectory($dest_dir);
		// now start adding relevant OE objects:
		if (!$image_file_exists) {
			$imageFileImport = $this->addFile($xml_data['file_reference'], $dir);

			$imageDataFileImport = new OphInVisualfields_Humphrey_Image;
			$imageDataFileImport->file_id = $imageFileImport->id;
			$imageDataFileImport->save();

			if (!file_exists($dest_dir . '/thumbs')) {
				mkdir($dest_dir . '/thumbs', 0777, true);
			}
			try {
				$this->createSubImages($dest_dir, $xml_data['file_reference']);
			} catch (Exception $e) {
				$this->audit("import", "scan", "Failed to create thumbnail images: " . $e->getMessage());
			}
		}
		// if the file does not yet exist, add it's details:
		if (!$xml_file_exists) {
			// step 2 - import file information:
			try {
				$xmlFileImport = $this->addFile($xml_file, $dir);
				$xmlDataFileImport = $this->addXmlData($xmlFileImport->id, $xml_data);
				$xmlDataFileImport->tif_file_id = $imageDataFileImport->file_id;
				$xmlDataFileImport->save();
			} catch (Exception $e) {
				$this->_sendResponse(400, sprintf("Error importing file: %s", $e->getMessage()), "text/html", "import", "scan");
			}
		}
		if (Yii::app()->params['visualfields.bind'] && !$xml_file_exists && !$image_file_exists) {
			$this->createHumphreyImagePairEvent($xml_data['recorded_pid'], $xmlDataFileImport->tif_file_id, $xmlDataFileImport->id, $xml_data['test_strategy']);
			$this->_sendResponse(200, sprintf("Success: " . var_export(Yii::app()->params['visualfields.bind'], true), $xmlDataFileImport->id), "text/html", "import", "scan");
		} else {

			$this->_sendResponse(200, sprintf("Imported Image (but not bound)", $xmlDataFileImport->id), "text/html", "import", "scan");
		}
		// ELSE { // what? }
	}

	/**
	 * @param type $dest_dir
	 * @param type $file_ref
	 */
	private function createSubImages($dest_dir, $file_ref) {

		foreach (Yii::app()->params['visualfields.subimages']['humphreys']
		as $key => $subimage_config) {
			$sub_dir = $dest_dir . '/' . $key . '/';
			if (!file_exists($sub_dir)) {
				mkdir($sub_dir, 0755, true);
			}
			$image = new Imagick($dest_dir . '/' . $file_ref);
			$cropParams = explode(',', $subimage_config['crop']);
			$image->cropimage($cropParams[0], $cropParams[1], $cropParams[2], $cropParams[3]);

			if (isset($subimage_config['scale'])) {
				$configParams = explode('x', $subimage_config['scale']);
				$image->thumbnailimage($configParams[0], $configParams[1]);
			}
			$image->writeimage($sub_dir . $file_ref . '.jpg');
		}
	}

	/**
	 * TODO - scanned document ID is a class that can ultimately
	 * be removed, when patients are automatically imported from the scan:
	 * the UID is really just an ID that took the place of a patient's ID
	 * in the days when fields were imported prior to patient creation.
	 * 
	 * Ultimately this can be replaced by the patient ID.
	 * 
	 * @param type $recordedPid
	 * @return \ScannedDocumentUid
	 */
	private function getPatientUid($recordedPid) {
		Yii::import('application.modules.OphInVisualfields.components.ScannedDocumentUid');
		$uid = ScannedDocumentUid::model()->find('pid=\'' . $recordedPid . '\'');
		if (!$uid) {
			$uid = new ScannedDocumentUid();
			$uid->pid = $recordedPid;
			$uid->save();
		}
		return $uid->id;
	}

	/**
	 * 
	 * @param type $username
	 * @param type $xml_data
	 * @return \Patient
	 */
	private function createPatient($username, $xml_data) {

		$c = new Contact();
		$c->first_name = $xml_data['given_name'];
		$c->last_name = $xml_data['family_name'];
		$c->created_user_id = User::model()->find('LOWER(username)=?', array(strtolower($username)))->id;
		$c->save();
		$p = new Patient();
		$p->gender = $xml_data['gender'];
		$p->dob = $xml_data['birth_date'];
		$p->hos_num = $pid;
		$p->created_user_id = User::model()->find('LOWER(username)=?', array(strtolower($username)))->id;
		$p->contact_id = $c->id;
		$p->save();
		return $p;
	}

	/**
	 * 
	 * @param type $src_dir
	 * @param type $xmlFile
	 * @param type $imageScan
	 * @param type $reason
	 */
	private function moveToErrorDir($src_dir, $xml_file, $image_scan, $reason) {
		$dirError = Yii::app()->params['visualfields.dir_err']['humphreys'];
		if (file_exists($src_dir . "/" . $xml_file)) {
			// again, beware multiple file systems! Do a copy and unlink, NOT a move:
			copy($src_dir . '/' . $xml_file, $dirError . "/" . $xml_file);
			unlink($src_dir . '/' . $xml_file);
			$this->audit("importHumphreySet", "scan", $reason);
		} else {
			$this->audit("importHumphreySet", "scan", "Request to move file "
					. $src_dir . "/" . $xml_file . " failed.");
		}
		if (file_exists($src_dir . "/" . $image_scan)) {
			copy($src_dir . '/' . $image_scan, $dirError . "/" . $image_scan);
			unlink($src_dir . '/' . $image_scan);
		} else {
			$this->audit("importHumphreySet", "scan", "Request to move file "
					. $src_dir . "/" . $image_scan . " failed.");
		}
	}

	/**
	 * TODO need to also check for DB entries - what if the duplicate exists
	 * as a file but there is no entry for it in the DB?
	 * 
	 * @param type $file_exists
	 * @param type $src_dir
	 * @param type $file
	 * @param type $dest_dir
	 */
	function checkForDuplicates($file_exists, $src_dir, $file, $dest_dir) {
		$deleteDuplicates = Yii::app()->params['visualfields.duplicates']['delete'];
		$duplicates = Yii::app()->params['visualfields.duplicates']['dir']['humphreys'];
		if (!$file_exists) {
			// it's not a duplicate so is safe to move:
			copy($src_dir . '/' . $file, $dest_dir . '/' . $file);
			unlink($src_dir . '/' . $file);
		} else if ($file_exists && !$deleteDuplicates) { //overwrite
			$dups = $this->addFsDirectory($duplicates);
			$time = microtime(true);
			copy($src_dir . '/' . $file, $duplicates . '/' . $time . '.' . $file);
			unlink($src_dir . '/' . $file);
			$f = $this->addFile($time . '.' . $file, $dups);
			$this->audit("import", "scan", "File already exists for patient: " . $dest_dir
					. '/' . $file . "; moving to duplicates directory");
		} else if ($file_exists && $deleteDuplicates) {
			// delete it!
			unlink($src_dir . '/' . $file);
		}
	}

	/**
	 * 
	 * @param type $id
	 * @param type $xml_data
	 * @return \FsScanHumphreyXml
	 */
	private function addXmlData($id, $xml_data) {

		$xmlDataFileImport = new OphInVisualfields_Humphrey_Xml;
		$xmlDataFileImport->file_id = $id;
		$xmlDataFileImport->file_name = $xml_data['file_reference'];
		$xmlDataFileImport->pid = $xml_data['recorded_pid'];
		$xmlDataFileImport->study_date = $xml_data['study_date'];
		$xmlDataFileImport->study_time = $xml_data['study_time'];
		$xmlDataFileImport->given_name = $xml_data['given_name'];
		$xmlDataFileImport->family_name = $xml_data['family_name'];
		$xmlDataFileImport->middle_name = $xml_data['middle_name'];
		$xmlDataFileImport->eye = $xml_data['eye'];
		$xmlDataFileImport->test_name = $xml_data['test_name'];
		$xmlDataFileImport->test_strategy = $xml_data['test_strategy'];
		$xmlDataFileImport->birth_date = $xml_data['birth_date'];
		$xmlDataFileImport->gender = $xml_data['gender'];
		return $xmlDataFileImport;
	}

	/**
	 * 
	 * @param type $data
	 * @return type
	 */
	private function getXmlData($data) {

		$xml = simplexml_load_string($data);
		$xml_data = array();
		$xml_data['file_reference'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->ReferencedImage_M->file_reference;
		$xml_data['recorded_pid'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->Patient_M->patient_id;

		$xml_data['family_name'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->Patient_M->patients_name->family_name;
		$xml_data['given_name'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->Patient_M->patients_name->given_name;
		$xml_data['middle_name'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->Patient_M->patients_name->middle_name;
		$xml_data['birth_date'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->Patient_M->patients_birth_date;
		$xml_data['gender'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->Patient_M->patients_sex;
		$xml_data['study_date'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->GeneralStudy_M->study_date;
		$xml_data['study_time'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->GeneralStudy_M->study_time;
		$xml_data['eye'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->GeneralSeries_M->laterality;
		$xml_data['test_strategy'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->CZM_HFA_Series_M->test_strategy;
		$xml_data['test_name'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->CZM_HFA_Series_M->test_name;
		return $xml_data;
	}

	/**
	 * 
	 * @param type $name
	 * @param type $dir
	 * @return \FsFile
	 */
	private function addFile($name, $dir) {
		$file = new FsFile;
		$file->name = $name;
		$file->dir_id = $dir->id;
		$stat = stat($dir->path . '/' . $name);
		$file->modified = $stat['mtime'];
		$file->created_date = date('Y-m-d H:i:s');
		$file->dir = $dir;
		$file->length = filesize($dir->path . '/' . $name);
		$username = $_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME'];
		$file->created_user_id = User::model()->find('username=\'' . $username . '\'')->id;
		$file->save();
		return $file;
	}

	/**
	 * 
	 * @param type $dir
	 * @return \FsDirectory
	 */
	private function addFsDirectory($dir) {
		$fsdir = FsDirectory::model()->find('path=\'' . $dir . '\'');
		if (!$fsdir) {
			$stat = stat($dir);
			$fsdir = new FsDirectory;
			$fsdir->path = $dir;
			$fsdir->modified = $stat['mtime'];
			$username = $_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME'];
			$fsdir->created_user_id = User::model()->find('username=\'' . $username . '\'')->id;
			$fsdir->created_date = date('Y-m-d H:i:s');
			$fsdir->save();
		}
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		return $fsdir;
	}

	/**
	 * Attempts to find a matching (opposite) test to the test file given in.
	 * 
	 * Opposite tests must be made on the same day, with the opposite eye.
	 * 
	 * @param type $pid the patient's non-null hospital number.
	 * @param type $tif_file_id non-null ID detailing the TIF file ID related
	 * to the test.
	 * @param type $xml_id non-null ID for the XML test file's ID.
	 * @param type $test_strategy
	 * @return void is returned if any of the specified values do not exist
	 * (like the patient not existing or the parameters being null).
	 */
	private function createHumphreyImagePairEvent($pid, $tif_file_id, $xml_id, $test_strategy) {
		if (!$pid || !$tif_file_id || !$xml_id || !$test_strategy) {
			return;
		}
		$event_type = EventType::model()->find('class_name=\'OphInVisualfields\'');
		$criteria = new CdbCriteria;
		$criteria->addSearchCondition('hos_num', strtolower($pid), true, 'OR', 'like');
		$criteria->addSearchCondition('hos_num', strtoupper($pid), true, 'OR', 'like');

		$patient = Patient::model()->find($criteria);
		if (!$patient) {
			return;
		}
		$xml_image = OphInVisualfields_Humphrey_Xml::model()->find('id=' . $xml_id);

		$createdDate = new DateTime($xml_image->study_date);
		$createdTime = new DateTime($xml_image->study_date . ' ' . $xml_image->study_time);
		$interval = Yii::app()->params['visualfields.event_bond_time'];
		if ($interval) {
			$preTime = $createdTime->sub(new DateInterval($interval));
		}
		// search for images of the other eye:
		$eye = 'L';
		if ($xml_image->eye == 'L') {
			$eye = 'R';
		}
		$criteria = '(pid=\'' . strtolower($patient->hos_num)
				. '\' or pid=\'' . strtoupper($patient->hos_num)
				. '\') and associated=0 and eye=\'' . $eye . '\'';
		if ($preTime) {
			$criteria = $criteria
					. ' and study_date=\'' . $createdDate->format('Y-m-d')
					. '\' and study_time>=\'' . $preTime->format('H:i:s') . '\'';
		}
		// so these are the images that are for the other eye that are not yet
		// associated:
		$images = OphInVisualfields_Humphrey_Xml::model()->findAll($criteria);

		if ($patient && $event_type) {
			// are we in legacy mode or normal import mode?
			if ($this->isLegacyMode('humphreys') && count($images) > 0) {
				$episode = null;
				if (!$patient->legacyepisodes || count($patient->legacyepisodes) == 0) {
					$ep = new Episode;
					$ep->legacy = 1;
					$ep->patient_id = $patient->id;
					$ep->save();
					$episode = $ep;
				} else {
					$episode = $patient->legacyepisodes[0];
				}
				$this->createLegacyEvent($episode, $tif_file_id, $images[0], $event_type, $test_strategy, $xml_image, $xml_id);
				$image1 = OphInVisualfields_Humphrey_Xml::model()->find('id=' . $xml_id);
				$image1->associated = 1;
				$image1->save();
				$image2 = OphInVisualfields_Humphrey_Xml::model()->find('id=' . $images[0]->id);
				$image2->associated = 1;
				$image2->save();
			} else {
				$bindingImage = null;
				$specialities = Yii::app()->params['visualfields.image_specialities']['humphreys'];
				foreach ($specialities as $speciality) {
					$condition = '';
					if (count($specialities) > 0) {
						$condition = $condition . ' and (';
					}
					$sp = Subspecialty::model()->find('name=\'' . $speciality . '\'');
					if ($sp) {
						$ssa = ServiceSubspecialtyAssignment::model()->find('subspecialty_id=' . $sp->id);
						$firms = Firm::model()->findAll('service_subspecialty_assignment_id=' . $ssa->id);
						$index = 0;
						foreach ($firms as $firm) {
							if ($index > 0 && $index < count($specialities)) {
								$condition = $condition . ' or ';
							}
							$condition = $condition . ' firm_id=' . $firm->id;
							$index++;
						}
					}
					if (count($specialities) > 0) {
						$condition = $condition . ')';
					}
					$cdbcriteria = new CDbCriteria;
					$cdbcriteria->condition = 'patient_id=' . $patient->id . $condition;
					$episodes = Episode::model()->findAll($cdbcriteria);
					if (count($images) > 0) {
						$bindingImage = $images[0];
						$this->createHumphreyEvent($tif_file_id, $images[0], $episodes, $event_type, $test_strategy, $xml_image, $xml_id);
					}
				}
				if ($bindingImage) {
					$image1 = OphInVisualfields_Humphrey_Xml::model()->find('id=' . $xml_id);
					$image1->associated = 1;
					$image1->save();
					$image2 = OphInVisualfields_Humphrey_Xml::model()->find('id=' . $bindingImage->id);
					$image2->associated = 1;
					$image2->save();
				}
			}
		}
	}

	/**
	 * Determines if the specified type is in legacy mode or not.
	 * Note that if the specified type cannot be found, the default
	 * type will be used. This enables the configuration to run off one value
	 * to either be legacy mode or not.
	 * 
	 * If type and default is NOT defined, legacy mode is always false.
	 * 
	 * @return boolean true if legacy mode is supported for the specified type;
	 * false otherwise.
	 */
	private function isLegacyMode($type = 'default') {
		if (!Yii::app()->params['visualfields.legacy_mode'][$type]) {
			$type = 'default';
		}
		return Yii::app()->params['visualfields.legacy_mode'][$type]
				&& Yii::app()->params['visualfields.legacy_mode'][$type] == true;
	}

	/**
	 * @param type $patient_id
	 * @param type $tif_file_id
	 * @param type $image
	 * @param type $event_type
	 * @param type $test_strategy
	 * @param type $xml_image
	 * @param type $xml_id
	 */
	private function createLegacyEvent($episode, $tif_file_id, $image, $event_type, $test_strategy, $xml_image, $xml_id) {
		$this->bindEpisode($episode, $event_type, $tif_file_id, $image, $xml_image, $test_strategy);
	}

	/**
	 * 
	 * @param type $tif_file_id
	 * @param type $image
	 * @param type $episodes
	 * @param type $event_type
	 * @param type $test_strategy
	 * @param type $xml_image
	 * @param type $xml_id
	 */
	private function createHumphreyEvent($tif_file_id, $image, $episodes, $event_type, $test_strategy, $xml_image, $xml_id) {
		foreach ($episodes as $episode) {
			$this->bindEpisode($episode, $event_type, $tif_file_id, $image, $xml_image, $test_strategy);
		}
	}

	/**
	 * Binds the episode to a new event, and creates necessary OphInVisualfield
	 * objects based on the given information from the TIF and XML files and the
	 * test strategy. Both eyes are bound to the new event.
	 * 
	 * @param type $episode the episode to bind the new event to.
	 * 
	 * @param type $event_type the event type that the event will be associated
	 * with.
	 * 
	 * @param type $tif_file_id the TIF file ID of the image to bind.
	 * 
	 * @param type $image
	 * @param type $xml_image
	 * @param type $test_strategy
	 */
	private function bindEpisode($episode, $event_type, $tif_file_id, $image, $xml_image, $test_strategy) {
		$tifCriteria = new CDbCriteria;
		$tifCriteria->addCondition('file_id=\'' . $image->tif_file_id . '\'');
		$previous_tif = OphInVisualfields_Humphrey_Image::model()->find($tifCriteria);
		// the current tif that is being tested for:
		$tifCriteria = new CDbCriteria;
		$tifCriteria->addCondition('file_id=\'' . $tif_file_id . '\'');
		$tif_image = OphInVisualfields_Humphrey_Image::model()->find($tifCriteria);
		if ($image->eye == 'R') {
			$tmp = $previous_tif->file_id;
			$previous_tif->file_id = $tif_image->file_id;
			$tif_image->file_id = $tmp;
		}

		$testType = OphInVisualfields_Testtype::model()->find('name=\'Humphreys\'');
		$testStrategy = OphInVisualfields_Strategy::model()->find('name=\'' . $test_strategy . '\'');

		// is this a logged in user or a HTTP request that is requesting this?
		$uid = (Yii::app()->session['user'] ? Yii::app()->session['user']->id : null);
		if ($uid == null) {
			// then it must be HTTP:
			$username = $_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME'];
			$uid = User::model()->find('username=\'' . $username . '\'')->id;
		}
		$event = new Event;
		$event->created_user_id = $uid;
		$event->episode_id = $episode->id;
		$event->event_type_id = $event_type->id;
		$event->created_date = date($xml_image->study_date
				. ' ' . $xml_image->study_time);
		$event->last_modified_date = date($xml_image->study_date
				. ' ' . $xml_image->study_time);
//        $event->datetime = date($xml_image->study_date
//                . ' ' . $xml_image->study_time);
		$event->save($allow_overriding = true);
//        $event->created_date = date($xml_image->study_date
//                . ' ' . $xml_image->study_time);
//        $event->last_modified_date = date($xml_image->study_date
//                . ' ' . $xml_image->study_time);
//        $event->datetime = date($xml_image->study_date
//                . ' ' . $xml_image->study_time);
//        $event->save($allow_overriding = true);
//        $event->created_user_id = (Yii::app()->session['user'] ? Yii::app()->session['user']->id : null);
		$event->created_date = date($xml_image->study_date
				. ' ' . $xml_image->study_time);
//        $event->datetime = date($xml_image->study_date
//                . ' ' . $xml_image->study_time);
		$event->last_modified_date = date($xml_image->study_date
				. ' ' . $xml_image->study_time);
		$event->save($allow_overriding = true);

		$objTestType = new Element_OphInVisualfields_Testtype;
		$objTestType->event_id = $event->id;
		$objTestType->test_type_id = $testType->id;
		$objTestType->save();
		$objDetails = new Element_OphInVisualfields_Details;
		$objDetails->event_id = $event->id;
		$objDetails->strategy_id = $testStrategy->id;
		$objDetails->save();
		$objImage = new Element_OphInVisualfields_Image;
		$objImage->event_id = $event->id;
		$objImage->left_image = $previous_tif->file_id;
		$objImage->right_image = $tif_image->file_id;
		$objImage->save();
	}

//
////  public function actionDelete() {
////    $this->_checkAuth();
////    switch ($_GET['model']) {
////      // Load the respective model
////      case 'asset':
////        $model = Asset::model()->findByPk($_GET['id']);
////        break;
////      default:
////        $this->_sendResponse(501, sprintf('Error: Mode delete is not implemented for model \'%s\'', $_GET['model']));
////        Yii::app()->end();
////    }
////    // Was a model found? If not, raise an error
////    if ($model === null)
////      $this->_sendResponse(400, sprintf("Error: Didn't find any model \'%s\' with ID \'%s\'.", $_GET['model'], $_GET['id']));
////
////    // Delete the model
////    $num = $model->delete();
////    if ($num > 0)
////      $this->_sendResponse(200, $num);    //this is the only way to work with backbone
////    else
////      $this->_sendResponse(500, sprintf("Error: Couldn't delete model \'%s\' with ID \'%s\'.", $_GET['model'], $_GET['id']));
////  }
//
	function isUseRest() {
		return Yii::app()->params['visualfields.use_rest'] === 'true';
	}

	/**
	 * 
	 */
	function getRestUsers() {
		return Yii::app()->params['visualfields.users'];
	}

	function getApplicationId() {
		return Yii::app()->params['visualfields.api_id'];
	}

	/**
	 * 
	 */
	protected function _checkAuth() {
		$f = $this->getApplicationId();
		// Check if we have the USERNAME and PASSWORD HTTP headers set? 
		if (!(isset($_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME']))) {
			// Error: Unauthorized 
			$this->_sendResponse(401, 'HTTP_X_' . $this->getApplicationId() . '_USERNAME is not set');
		}
		if (!(isset($_SERVER['HTTP_X_' . $this->getApplicationId() . '_PASSWORD']))) {
			// Error: Unauthorized 
			$this->_sendResponse(401, 'HTTP_X_' . $this->getApplicationId() . '_PASSWORD is not set');
		}
		if (!(isset($_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME']) and isset($_SERVER['HTTP_X_' . $this->getApplicationId() . '_PASSWORD']))) {
			// Error: Unauthorized 
			$this->_sendResponse(401, 'HTTP_X_' . $this->getApplicationId() . '_USERNAME is not set');
		}
		$username = $_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME'];
		$password = $_SERVER['HTTP_X_' . $this->getApplicationId() . '_PASSWORD'];
		// Find the user 
		$user = User::model()->find('LOWER(username)=?', array(strtolower($username)));
		if ($user === null) {
			// Error: Unauthorized 
			$this->_sendResponse(401, 'Error: User Name is invalid');
		} else if (!$user->validatePassword($password)) {
			// Error: Unauthorized 
			$this->_sendResponse(401, 'Error: User Password is invalid');
		}
	}

	public function accessRules() {
		// Allow logged in users - the main authorisation check happens later in verifyActionAccess
		return array(array('allow'));
	}

	/**
	 * 
	 * @param type $status
	 * @param string $body
	 * @param type $content_type
	 */
	protected function _sendResponse($status = 200, $body = '', $content_type = 'text/html') {
		// set the status
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		header($status_header);
		// and the content type
		header('Content-type: ' . $content_type);

		// pages with body are easy
		if ($body != '') {
			// send the body
			echo $body;
		}
		// we need to create the body if none is passed
		else {
			// create some body messages
			$message = '';

			// this is purely optional, but makes the pages a little nicer to read
			// for your users.  Since you won't likely send a lot of different status codes,
			// this also shouldn't be too ponderous to maintain
			switch ($status) {
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}

			// servers don't always have a signature turned on 
			// (this is an apache directive "ServerSignature On")
			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

			// this should be templated in a real-world solution
			$body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';

			echo $body;
		}
		Yii::app()->end();
	}

	/**
	 * 
	 * @param type $status
	 * @return type
	 */
	protected function _getStatusCodeMessage($status) {
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}

	/**
	 * TODO - new OE will have table_version data; so not much need for
	 * audit.
	 * @param type $action
	 * @param type $target_type
	 * @param type $data
	 */
	protected function audit($action, $audit_type, $data) {
//		$audit = new Audit;
//		$action_id = AuditType::model()->find("name=\"" . $action . "\"");
//		$type_id = AuditType::model()->find("name=\"" . $audit_type . "\"");
//		$audit->action = $action_id;
//		$audit->target_type = $audit_type;
//		$audit->data = $data;
//		$username = $_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME'];
//		$audit->user_id = User::model()->find('username=\'' . $username . '\'')->id;
//		$audit->save();
	}

}

?>
