<?php

/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
class OphInVisualfields_API extends BaseAPI {

	/**
	 * Gets all non-referenced (by event or episodes) visual field measurements,
	 * unless the event ID is passed in 
	 * 
	 * @param Patient $patient
	 * @param int $eye
	 * @param int event_id the event ID, may be null
	 * @return an array of all appropriate visual fields for the specified
	 * patient and eye.
	 */
	public function getVisualfields($patient, $eye, $event_id = null, $legacy = 0) {
		$criteria = new CDbCriteria();
		$criteria->order = 'study_datetime ASC';
		$extra = null;
		if ($event_id != null) {
			$extra = ' OR (t.patient_measurement_id IN (SELECT patient_measurement_id from (measurement_reference) WHERE event_id=' . $event_id . '))';
		}
		$criteria->condition = 
				' eye_id=' . $eye
				. ' and legacy=' . $legacy
				. ' and (t.patient_measurement_id NOT IN '
				. ' (SELECT patient_measurement_id from (measurement_reference))'
				. $extra . ')';

		return MeasurementVisualFieldHumphrey::model()->findAll(
						$criteria);
	}

}
