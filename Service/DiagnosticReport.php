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
	public $study_datetime;
	public $study_date;
	public $study_time;
	public $patient_id;
	public $eye_id;
	public $test_strategy;
	public $test_reference;
	public $humphrey_image_id;
	public $cropped_humphrey_image_id;
	public $image_ref_id;
	public $xml_ref_id;
	public $image_data;
	public $attachment_data;
	public $contentType;

	public static function fromModel(\OphInVisualfields_Humphrey_Xml $model) {

		$resource = new self(
						array(
							$id = $model->id,
							$study_date = $model->study_date,
							$study_datetime = $model->study_datetime,
							$patient_id = $model->patient_id,
							$study_time = $model->study_time,
							$eye = $model->eye,
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

		// the reference is actually a 'contained' (embedded) reference;
		// for the time, we treat the first element as the only observation
		// in this report:
		if (isset($fhirObject->result) && count($fhirObject->result) > 0) {
			$refXmlResults = $fhirObject->result[0]->reference;
		}
		$patientRef = split('/', $fhirObject->subject->reference);
		$mediaRef = split('/', $fhirObject->image[0]->link->reference);
		// get the XML file data and write it to file:
		foreach ($fhirObject->contained as $element) {
			// if the reference matches, we've found our contained file:
			if (isset($element->Observation)) {
				$xref = substr($refXmlResults, 1, 2);
				if (isset($element->Observation->id) && strcmp($xref, $element->Observation->id) == 0) {
					$contentType = $element->Observation->valueAttachment->contentType;
					$data = $element->Observation->valueAttachment->data;
					$title = $element->Observation->valueAttachment->title;

					$data = base64_decode($data);
					$xml_data = DiagnosticReport::getXmlData($data);
					DiagnosticReport::set($report, $xml_data);
				}
			}
		}
		$mediaRefId = split("-", $mediaRef[1]);
		$patientRefId = split("-", $patientRef[1]);
		$protected_file = \ProtectedFile::model()->find("id=?", array(($mediaRefId[1])));
		$report->humphrey_image_id = $protected_file->id;
		$patient = \Patient::model()->find("id=?", array($patientRefId[1]));
		$report->patient_id = $patient->id;
		$path = $protected_file->getPath();
		// TODO - to be replaced and performed on the black box, not in OE
		// all such image cropping will move out of OE
		$dims = \Yii::app()->params['visualfields.subimages']['humphreys']['thumbs'];
		$src = imagecreatefromgif($path);
		$dest = imagecreatetruecolor(925, 834);
		$croppedPath = $protected_file->getCroppedPath($dims['crop']);
		imagecopy($dest, $src, 0, 0, 1302, 520, 925, 834);
		if (!is_dir(dirname($croppedPath))) {
			mkdir(dirname($croppedPath));
		}
		imagegif($dest, $croppedPath);
		$cropped_image = \ProtectedFile::createFromFile($protected_file->getCroppedPath($dims['crop']));
		$cropped_image->name = $protected_file->name;
		$cropped_image->save();
		$scaled_image = $cropped_image->getThumbnail($dims['scale']);

		$scaled_image = \ProtectedFile::createFromFile($scaled_image['path']);
		$scaled_image->name = $protected_file->name;
		$scaled_image->save();
		$report->cropped_humphrey_image_id = $scaled_image->id;
		return $report;
	}

	public function toModel(\OphInVisualfields_Humphrey_Xml $model) {

		$model->id = $this->id;
		$model->study_datetime = $this->study_datetime;
		$model->study_date = $this->study_date;
		$model->patient_id = $this->patient_id;
		$model->study_time = $this->study_time;
		$model->eye_id = $this->eye_id;
		$model->test_strategy = $this->test_strategy;
		$model->test_name = $this->test_name;
		$model->cropped_image_id = $this->cropped_humphrey_image_id;
		$model->humphrey_image_id = $this->humphrey_image_id;
		\Service\Service::saveModel($model);
	}

	/**
	 * 
	 * @param type $id
	 * @param type $xml_data
	 * @return \FsScanHumphreyXml
	 */
	public static function set($report, $xml_data) {
		$report->pid = $xml_data['recorded_pid'];
		$eye = 'Right';
		if ($xml_data['eye'] == 'L') {
			$eye = 'Left';
		}
		$report->eye_id = \Eye::model()->find("name=:name", array(":name" =>$eye))->id;
		$report->study_datetime = $xml_data['study_date'] . " " . $xml_data['study_time'];
		$report->study_date = $xml_data['study_date'];
		$report->study_time = $xml_data['study_time'];
		$report->test_name = $xml_data['test_name'];
		$report->test_strategy = $xml_data['test_strategy'];
	}

	/**
	 * 
	 * @param type $data
	 * @return type
	 */
	private static function getXmlData($data) {

		$xml = simplexml_load_string($data);
		$xml_data = array();
		$xml_data['recorded_pid'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->Patient_M->patient_id;
		$xml_data['study_date'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->GeneralStudy_M->study_date;
		$xml_data['study_time'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->GeneralStudy_M->study_time;
		$xml_data['eye'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->GeneralSeries_M->laterality;
		$xml_data['test_strategy'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->CZM_HFA_Series_M->test_strategy;
		$xml_data['test_name'] = (string) $xml->DataSet->CZM_HFA_EMR_IOD->CZM_HFA_Series_M->test_name;
		return $xml_data;
	}

}
