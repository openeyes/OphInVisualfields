<?php

/**
 * (C) OpenEyes Foundation, 2014
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (C) 2014, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

namespace OphInVisualfields\Service;

class DiagnosticReport extends \Service\Resource {

	public $id;
	public $pid;
	public $study_date;
	public $study_time;
	public $patient_id;
	public $given_name;
	public $middle_name;
	public $family_name;
	public $birth_date;
	public $gender;
	public $eye;
	public $file_name;
	public $test_strategy;
	public $test_reference;

	public $image_humphrey_id;
	public $image_ref_id;
	public $xml_ref_id;
	
	public $image_data;
	public $attachment_data;
	
	public $contentType;

	public static function fromModel(\OphInVisualfields_Humphrey_Xml $model) {

		$resource = new self(
						array(
							$id = $model->id,
							$pid = $model->pid,
							$study_date = $model->study_date,
							$study_time = $model->study_time,
							$patient_id = $model->pid,
							$given_name = $model->given_name,
							$middle_name = $model->middle_name,
							$family_name = $model->family_name,
							$birth_date = $model->birth_date,
							$gender = $model->gender,
							$eye = $model->eye,
							$file_name = $model->file_name,
							$test_strategy = $model->test_strategy,
							$test_name = $model->test_name,
							$image_humphrey_id = $model->image_ref_id,
							$xml_ref_id = $model->xml_file_id,
						)
		);

		return $resource;
	}

	static public function fromFhir(\StdClass $fhirObject) {
		$report = parent::fromFhir($fhirObject);
		
		// the reference is actually a 'contained' (embeddede) reference;
		// for the time, we treat the first element as the only observation
		// in this report:
		if (isset($fhirObject->results) && count($fhirObject->results) > 0) {
			$refXmlResults = $fhirObject->results[0]->reference;
		}
		$ref = split('/', $fhirObject->image->reference);
//		$report->$image_ref_id = substr($ref[1], 1);
		// get the XML file data and write it to file:
		foreach ($fhirObject->contained as $element) {
			// if the reference matches, we've found our contained file:
			if ($element->id && substr($refXmlResults, 1) == $element->id) {
				if ($element->resourceType == 'Observation'
						&& isset($element->valueAttachment)) {
					$contentType = $element->valueAttachment->contentType;
					$data = $element->valueAttachment->data;
					$title = $element->valueAttachment->title;

					$model = \ProtectedFile::createForWriting($title);
					// all content is base64 encoded, so decode it:
					file_put_contents($model->getPath(), base64_decode($data));
					
					$report->getHumphreyXmlData($model->getPath());
					$id = $model->save();
					$report->xml_ref_id = $model->id;
					// so we now have the 'short' XML file and have created the necessary file locally -
					// now find the image:
					
				}
			}
		}
		
		$model = \ProtectedFile::model()->find("id=?", array($ref[1]));
		$id = $model->id;
		$humphrey_image = new \OphInVisualfields_Humphrey_Image;
		$humphrey_image->protected_file_id = $model->id;
		$humphrey_image->save();
		$report->image_ref_id = $humphrey_image->id;
		
		return $report;
	}

	public function toModel(\OphInVisualfields_Humphrey_Xml $model) {
		
		$model->id = $this->id;
		$model->study_date = $this->study_date;
		$model->study_time = $this->study_time;
		$model->pid = $this->pid;
		$model->given_name = $this->given_name;
		$model->middle_name = $this->middle_name;
		$model->family_name = $this->family_name;
		$model->birth_date = $this->birth_date;
		$model->gender = $this->gender;
		$model->eye = $this->eye;
		$model->file_name = $this->file_name;
		$model->test_strategy = $this->test_strategy;
		$model->test_name = $this->test_name;
		$model->humphrey_image_id = $this->image_ref_id;
		$f = $model->humphrey_image_id;
		$model->xml_file_id = $this->xml_ref_id;
		$model->image_file = $this->image_humphrey_id;
		\Service\Service::saveModel($model);
	}

	public function getHumphreyXmlData($file) {
		$data = file_get_contents($file);
		try {
			return $this->parseShortXmlData(DiagnosticReport::getXmlData($data));
			// -----------------------------------------------------------------------------------------
			// TODO - now have patient XML data - need to verify patient ID, name, age, gender etc. HERE
			// -----------------------------------------------------------------------------------------
		} catch (Exception $ex) {
			// need to move files to another (error) location
			$this->message(sprintf("Error: parsing file '%s': '%s'", $file, $ex->getMessage()), "importHumphreyImageSet", "scan");
			continue;
		}
	}

	/**
	 * 
	 * @param type $id
	 * @param type $xml_data
	 * @return \FsScanHumphreyXml
	 */
	public function parseShortXmlData($xml_data) {
		$this->pid = $xml_data['recorded_pid'];
		$this->birth_date = $xml_data['birth_date'];
		$this->eye = $xml_data['eye'];
		$this->family_name = $xml_data['family_name'];
		$this->file_name = $xml_data['file_reference'];
		$this->gender = $xml_data['gender'];
		$this->given_name = $xml_data['given_name'];
		$this->middle_name = $xml_data['middle_name'];
		$this->study_date = $xml_data['study_date'];
		$this->study_time = $xml_data['study_time'];
		$this->test_name = $xml_data['test_name'];
		$this->test_strategy = $xml_data['test_strategy'];
	}

	/**
	 * 
	 * @param type $data
	 * @return type
	 */
	private static function getXmlData($data) {

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

}
