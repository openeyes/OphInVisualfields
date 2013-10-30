<?php

/**
 * Utility class for importing Huphrey visual fields.
 */
class HumphreyScanner {

  //put your code here
  public function import() {
    Yii::import('application.modules.OphInVisualfields.components.*');
    Yii::import('application.modules.OphInVisualfields.models.*');

    $src_dir = Yii::app()->params['visualfields.dir_in']['humphreys'];
    $dest_dir = Yii::app()->params['visualfields.dir_out']['humphreys'];
    $err_dir = Yii::app()->params['visualfields.dir_err']['humphreys'];
    $dup_dir = Yii::app()->params['visualfields.duplicates']['dir']['humphreys'];

    $img_ext = Yii::app()->params['visualfields.img_extension'];

    $files = glob($src_dir . '/*.xml');
    foreach ($files as $file) {
      $img_file = $src_dir . '/' . basename($file, '.xml') . '.' . $img_ext;
      if (!file_exists($img_file)) {
        $this->message(sprintf("Error: Could not locate image file '%s'", $img_file), "importHumphreyImageSet", "scan");
        $time = microtime(true);
        $this->moveFile($file, $err_dir . '/' . $time . '.' . basename($file));
        continue;
      }
      $this->audit("importHumphreyImageSet", "scan", $file);

      $data = file_get_contents($file);
      try {
        $xml_data = $this->getXmlData($data);
        // -----------------------------------------------------------------------------------------
        // TODO - now have patient XML data - need to verify patient ID, name, age, gender etc. HERE
        // -----------------------------------------------------------------------------------------
      } catch (Exception $ex) {
        // need to move files to another (error) location
        $this->message(sprintf("Error: parsing file '%s': '%s'", $file, $ex->getMessage()), "importHumphreyImageSet", "scan");
        continue;
      }
      if (!array_key_exists('file_reference', $xml_data)) {
        $this->moveFile($src_dir . '/' . $xml_data['file_reference'], $err_dir . '/' . $xml_data['file_reference']);
        $this->message(sprintf("Error: XML file %s contained no PID", $xml_data['file_reference']), "importHumphreyImageSet", "scan");
        continue;
      }
      $image_file = $src_dir . '/' . $xml_data['file_reference'];
      if (!file_exists($image_file)) {
        // again, need to move XML file to another (error) location
        $this->message(sprintf("Error: Could not find image file '%s' for XML source file '%s'", $xml_data['file_reference'], $src_file), "importHumphreyImageSet", "scan");
        continue;
      }
      $uid = ScannedDocumentUid::model()->find('pid=\'' . $xml_data['recorded_pid'] . '\'');
      if (!$uid) {
        $uid = new ScannedDocumentUid();
        $uid->pid = $xml_data['recorded_pid'];
        $uid->save();
      }
      $xml_file_exists = false;
      $image_file_exists = false;
      // step 1 - move file:
      try {
        if (Yii::app()->params['visualfields.dir_structure'] == 'deep') {
          $dest = $dest_dir . '/' . $xml_data['test_strategy'] . '/' . $uid->id;
          if (!file_exists($dest)) {
            mkdir($dest, 0755, true);
          } else {
            $dest = $dest_dir;
          }
          // TODO check that destinations do not already exist; if they do,
          // copy them to -err
          // -- code here
          $xml_file = basename($file);
          $xml_file_exists = file_exists($dest . '/' . $xml_file);
          $image_file_exists = file_exists($dest . '/' . $xml_data['file_reference']);
          if ($xml_file_exists) {
            $this->dealWithDuplicate($dup_dir, $dest_dir, $src_dir,  $xml_file);
          } else {
            // it doesn't exist so move it to the correct directory:
            $this->moveFile($src_dir . '/' . $xml_file, $dest . '/' . $xml_file);
          }
          if ($image_file_exists) {
            $this->dealWithDuplicate($dup_dir, $dest_dir, $src_dir, $xml_data['file_reference']);
          } else {
            // it doesn't exist so move it to the correct directory:
            $this->moveFile($src_dir . '/' . $xml_data['file_reference'], $dest . '/' . $xml_data['file_reference']);
          }
        } else {
          // user has not specified dest; so dest and src are the same (import in situ):
          $dest = $src_dir;
        }
      } catch (Exception $e) {
        $this->message(sprintf("Error importing file: %s", $e->getMessage()), "importHumphreyImageSet", "scan");
      }
      $dir = $this->addFsDirectory($dest);
      $imageFileImport = $this->addFile($xml_data['file_reference'], $dir);
      $himage_file = OphInVisualfields_Humphrey_Image::model()->find('file_id = ' . $imageFileImport->id);
      if (!$himage_file) {
        $imageDataFileImport = new OphInVisualfields_Humphrey_Image;
        $imageDataFileImport->file_id = $imageFileImport->id;
        $imageDataFileImport->save();

        try {
          foreach (Yii::app()->params['visualfields.subimages']['humphreys'] as $key => $subimage_config) {
            $sub_dir = $dest . '/' . $key . '/';
            if (!file_exists($sub_dir)) {
              mkdir($sub_dir, 0755, true);
            }
            $image = new Imagick($dest . '/' . $xml_data['file_reference']);
            $cropParams = explode(',' , $subimage_config['crop']);
            $image->cropimage($cropParams[0], $cropParams[1], $cropParams[2], $cropParams[3]);
            
            if (isset($subimage_config['scale'])) {
              $configParams = explode('x' , $subimage_config['scale']);
              $image->thumbnailimage($configParams[0], $configParams[1]);
            }
            $image->writeimage($sub_dir . $xml_data['file_reference'] . '.jpg');
          }
        } catch (Exception $e) {
          $this->audit("importHumphreyImageSet", "scan", "Failed to create thumbnail images: " . $e->getMessage());
        }
      }

      // if the file does not yet exist, add it's details:
      $xmlFileImport = $this->addFile($xml_file, $dir);
      $xmlData = OphInVisualfields_Humphrey_Xml::model()->find('file_id=' . $xmlFileImport->id);
      if (!$xmlData) {
        try {
          $xmlDataFileImport = $this->addXmlData($xmlFileImport->id, $xml_data);
          $xmlDataFileImport->tif_file_id = $imageDataFileImport->file_id;
          $xmlDataFileImport->save();
        } catch (Exception $e) {
          $this->message(sprintf("Error importing file: %s", $e->getMessage()), "importHumphreyImageSet", "scan");
        }
      } else {
        $xmlDataFileImport = OphInVisualfields_Humphrey_Xml::model()->find('file_id=' . $xmlFileImport->id);
      }
      if (Yii::app()->params['visualfields.bind'] === true && !$xml_file_exists && !$image_file_exists) {
        $this->createHumphreyImagePairEvent($xml_data['recorded_pid'], $xmlDataFileImport->tif_file_id, $xmlDataFileImport->id, $xml_data['test_strategy']);
        $this->message("Imported file " . $xml_file . " for patient " . $xml_data['recorded_pid'], "importHumphreyImageSet", "scan");
      } else {
        $this->message("Imported Image (but not bound) " . $xml_file . " for patient " . $xml_data['recorded_pid'], "importHumphreyImageSet", "scan");
      }
    }
  }
  
  /**
   * 
   * @param type $dup_dir
   * @param type $dest_dir
   * @param type $src_dir
   * @param type $src_file
   */
  private function dealWithDuplicate($dup_dir, $dest_dir, $src_dir, $src_file) {
    
    if (Yii::app()->params['visualfields.duplicates']['delete'] == false) {
      // TODO - what to do if the file exists in the location but
      // the data has not been written to DB?
      $dups = $this->addFsDirectory($dup_dir);
      $time = microtime(true);
      $this->moveFile($src_dir . '/' . $src_file, $dup_dir . '/' . $time . '.' . $src_file);
      $f = $this->addFile($time . '.' . $src_file, $dups);
      $this->message("File already exists for patient: " . $dest_dir
              . '/' . $src_file . "; moving to duplicates directory","importHumphreyImageSet", "scan");
    } else {
      // delete it:
      unlink($src_file . '/' . $src_file);
      $this->message("input file " . $src_dir . '/' . $src_file . " already exists for patient: "
              . $dest_dir . "/" . $src_file . "; deleting it", "importHumphreyImageSet", "scan");
    }
  }

  /**
   * Do not just do an unlink! On OSs with a different drive for src and dest,
   * unlink fails; so always perform a copy/unlink.
   * 
   * @param type $src
   * @param type $dest
   */
  private function moveFile($src, $dest) {
    copy($src, $dest);
    unlink($src);
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

//    // Cardiff check! See if the extraneous char is at the end of the PID:
//    $pid = $xml_data['recorded_pid'];
//    if (strlen($pid) > 7) {
//      // X123456Z - we want to remove the 'Z':
//      if (ctype_alpha($pid[7])) {
//        // it's a character - remove it:
//        $xml_data['recorded_pid'] = substr($pid, 0, 7);
//      }
//    }

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
   * Adds the file if it does not already exist.
   * 
   * @param type $name the name of the file to add.
   * @param type $dir the name of the directory where the file resides.
   * @return \FsFile the added file.
   */
  private function addFile($name, $dir) {
    $fsfile = FsFile::model()->find('name=\'' . $name . '\' and dir_id=' . $dir->id);
    if (!$fsfile) {
      $fsfile = new FsFile;
      $fsfile->name = $name;
      $fsfile->dir_id = $dir->id;
      $stat = stat($dir->path . '/' . $name);
      $fsfile->modified = $stat['mtime'];
      $fsfile->created_date = date('Y-m-d H:i:s');
      $fsfile->dir = $dir;
      $fsfile->length = filesize($dir->path . '/' . $name);
      $fsfile->save();
    }
    return $fsfile;
  }

  /**
   * Adds the specified directory, if it does not already exist.
   * @param type $dir the full path of the directory to add.
   * @return \FsDirectory the added directory.
   */
  private function addFsDirectory($dir) {
    $fsdir = FsDirectory::model()->find('path=\'' . $dir . '\'');
    if (!$fsdir) {
      $stat = stat($dir);
      $fsdir = new FsDirectory;
      $fsdir->path = $dir;
      $fsdir->modified = $stat['mtime'];
      $fsdir->created_date = date('Y-m-d H:i:s');
      $fsdir->save();
    }
    if (!file_exists($dir)) {
      mkdir($dir, 0755, true);
    }
    return $fsdir;
  }
  
  /**
   * Prints and audits the specified message.
   * @param type $message
   * @param type $action
   * @param type $target_type
   */
  private function message($message, $action, $target_type) {
    print_r($message . PHP_EOL);
    $this->audit($action, $target_type, $message);
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
  public function createHumphreyImagePairEvent($pid, $tif_file_id, $xml_id, $test_strategy) {
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
            $x = ServiceSubspecialtyAssignment::model()->find('subspecialty_id=' . $sp->id);
            $y = Firm::model()->findAll('service_subspecialty_assignment_id=' . $x->id);
            $index = 0;
            foreach ($y as $firm) {
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
            $this->createEvent($tif_file_id, $images[0], $episodes, $event_type, $test_strategy, $xml_image, $xml_id);
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
    return Yii::app()->params['visualfields.legacy_mode'];
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
  private function createEvent($tif_file_id, $image, $episodes, $event_type, $test_strategy, $xml_image, $xml_id) {
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
//    $uid = (Yii::app()->session['user'] ? Yii::app()->session['user']->id : null);
//    if ($uid == null) {
//      // then it must be HTTP:
//      $username = $_SERVER['HTTP_X_' . $this->getApplicationId() . '_USERNAME'];
//      $uid = User::model()->find('username=\'' . $username . '\'')->id;
//    }
    $event = new Event;
//    $event->created_user_id = $uid;
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

  /**
   * 
   * @param type $action
   * @param type $target_type
   * @param type $data
   */
  protected function audit($action, $target_type, $data) {
    $audit = new Audit;
    $audit->action = $action;
    $audit->target_type = $target_type;
    $audit->data = $data;
    $audit->save();
  }

}

?>
