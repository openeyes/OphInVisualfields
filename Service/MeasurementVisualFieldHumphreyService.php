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

class MeasurementVisualFieldHumphreyService extends \Service\ModelService {

  static protected $operations = array(self::OP_READ, self::OP_UPDATE, self::OP_CREATE, self::OP_SEARCH);
  static protected $primary_model = 'MeasurementVisualFieldHumphrey';

  public function search(array &$params) {
	$this->setUsedParams($params, 'id');

	$model = $this->getSearchModel();
	if (isset($params['id']))
	  $model->id = $params['id'];

	$searchParams = array('pageSize' => null);

	return $this->getResourcesFromDataProvider($model->search($searchParams));
  }

  /**
   * @param type $res
   * @param type $measurement
   * @return type
   */
  public function resourceToModel($res, $measurement) {
	$measurement->patient_id = $res->patient_id;
	$measurement->eye_id = $res->eye_id;
	$measurement->pattern_id = \OphInVisualfields_Pattern::model()->find("name=:name", array(":name" => $res->pattern))->id;
	$measurement->strategy_id = \OphInVisualfields_Strategy::model()->find("name=:name", array(":name" => $res->strategy))->id;
	$measurement->patient_measurement_id = \PatientMeasurement::model()->findByPk($res->patient_measurement_id)->id;
	$measurement->study_datetime = $res->study_datetime;
	$measurement->cropped_image_id = $res->scanned_field_crop_id;
	$measurement->image_id = $res->scanned_field_id;
	$measurement->source = base64_decode($res->xml_file_data);
	$saved = $measurement->save();
	return $measurement;
  }

}
