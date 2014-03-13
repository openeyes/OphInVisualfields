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

  /**
   * 
   * @param type $fhirObject
   * @return type
   */
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
	$report->file_reference = $fhirObject->file_reference;
	$report->strategy = $fhirObject->strategy;
	$report->eye_id = \Eye::model()->find("name=:name", array(":name" => $eye))->id;
	$x = $fhirObject->xml_file_data;
	$report->source = base64_decode($fhirObject->xml_file_data);
	
	$title = $report->file_reference;
	$protected_file = \ProtectedFile::createForWriting($title);
	$protected_file->mimetype = 'image/gif';
	$protected_file->name = $title;
	file_put_contents($protected_file->getPath(), base64_decode($report->image_scan_data));
	$protected_file->save();
	$report->scanned_field_id = $protected_file->id;
	// now write the contents to files:
	$title = $report->file_reference;
	$model = \ProtectedFile::createForWriting($title);
	// all content is base64 encoded, so decode it:
	file_put_contents($model->getPath(), base64_decode($report->image_scan_crop_data));
	$model->mimetype = 'image/gif';
	$model->name = $title;
	$val = $model->save();

	$report->scanned_field_crop_id = $model->id;

	return $report;
  }
}
