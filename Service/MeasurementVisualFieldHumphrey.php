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

class MeasurementVisualFieldHumphrey extends \Service\Resource {
  
  public $id;
  public $study_datetime;
  public $patient_id;
  public $eye_id;
  public $strategy;
  public $pattern;
  public $scanned_field_id;
  public $scanned_field_crop_id;
  public $image_data;

//	static protected $operations = array(self::OP_READ, self::OP_CREATE, self::OP_SEARCH);
//	public static function fromModel(\MeasurementHumphreyField $model) {
//
//		$resource = new self(
//						array(
//							$id = $model->id,
//							$study_datetime = $model->study_datetime,
//							$patient_id = $model->patient_id,
//							$eye = $model->eye,
//							$test_strategy = $model->test_strategy,
//							$test_name = $model->test_name,
//							$scanned_field_crop_id = $model->$scanned_field_crop_id,
//							$scanned_field_id = $model->$scanned_field_id,
//							$xml_ref_id = $model->xml_file_id,
//						)
//		);
//
//		return $resource;
//	}

  static public function fromFhir($fhirObject) {
    $report = parent::fromFhir($fhirObject);

    $patient = \Patient::model()->find("id=?", array($report->patient_id));
    $report->patient_id = $patient->id;
    $report->study_datetime = $report->study_datetime;
    $eye = 'Right';
    if ($report->eye == 'L') {
      $eye = 'Left';
    }
    $report->pattern = $fhirObject->pattern;
    $report->strategy = $fhirObject->strategy;
    $report->eye_id = \Eye::model()->find("name=:name", array(":name" => $eye))->id;
    
    $title = $report->patient_id . "-" . $report->study_datetime;
		$protected_file = \ProtectedFile::createForWriting($title);
    $protected_file->mimetype = 'image/gif';
    $protected_file->name = $title;
    file_put_contents($protected_file->getPath(), base64_decode($report->image_scan_data));
    $protected_file->save();
		$report->scanned_field_id = $protected_file->id;
    // now write the contents to files:
    $title = $report->patient_id . "-" . $report->study_datetime;
    $model = \ProtectedFile::createForWriting($title . 'a');
    // all content is base64 encoded, so decode it:
    file_put_contents($model->getPath(), base64_decode($report->image_scan_crop_data));
    $model->mimetype = 'image/gif';
    $model->name = $title;
    $val = $model->save();

    $report->scanned_field_crop_id = $model->id;

    return $report;
  }

//	public function toModel(\MeasurementHumphreyField $model) {
//
//		$model->id = $this->id;
//		$model->study_datetime = $this->study_datetime;
//		$model->patient_id = $this->patient_id;
//		$model->eye_id = $this->eye_id;
//		$model->test_strategy = $this->test_strategy;
//		$model->test_name = $this->test_name;
//		$model->cropped_image_id = $this->cropped_humphrey_image_id;
//		$model->scanned_field_id = $this->scanned_field_id;
//		\Service\Service::saveModel($model);
//	}
}
