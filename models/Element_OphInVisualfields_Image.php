<?php

/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "et_ophinvisualfields_image".
 *
 * The followings are the available columns in table:
 * @property string $id
 * @property integer $event_id
 * @property integer $left_field_id
 * @property integer $right_field_id
 *
 * The followings are the available model relations:
 *
 * @property ElementType $element_type
 * @property EventType $eventType
 * @property Event $event
 * @property User $user
 * @property User $usermodified
 */
class Element_OphInVisualfields_Image extends BaseEventTypeElement {

	public $service;

	/**
	 * Returns the static model of the specified AR class.
	 * @return the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'et_ophinvisualfields_image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, left_field_id, right_field_id, ', 'safe'),
			array('id, event_id, left_field_id, right_field_id, ', 'safe', 'on' => 'search'),
//        array('left_field_id', 'numerical', 'integerOnly' => true, 'min' => 1, 'message' => 'Left image must be valid'),
//        array('right_field_id', 'numerical', 'integerOnly' => true, 'min' => 1, 'message' => 'Right image must be valid'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'element_type' => array(self::HAS_ONE, 'ElementType', 'id', 'on' => "element_type.class_name='" . get_class($this) . "'"),
			'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
			'left_field' => array(self::HAS_ONE, 'MeasurementVisualFieldHumphrey', 'left_field_id'),
			'right_field' => array(self::HAS_ONE, 'MeasurementVisualFieldHumphrey', 'right_field_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'left_field_id' => 'Left image',
			'right_field_id' => 'Right image',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('event_id', $this->event_id, true);
		$criteria->compare('left_field_id', $this->left_field_id);
		$criteria->compare('right_field_id', $this->right_field_id);

		return new CActiveDataProvider(get_class($this), array(
					'criteria' => $criteria,
				));
	}

	/**
	 * 
	 * @return boolean
	 */
	public function beforeSave() {
		// the IDs are protected file IDs of the cropped images (this should be changed at a later date):
		$this->left_field_id = $this->getFieldMeasurement(1, $this->left_field_id)->id;
		$this->right_field_id = $this->getFieldMeasurement(2, $this->right_field_id)->id;
		return true;
	}

	/**
	 * Once the image is saved, it needs to be attached to a measurement reference
	 * and appropriate measurement reference.
	 */
	public function afterSave() {
		parent::afterSave();
		// we only set references to valid images that are NOT associated with a legacy episode
		$api = new MeasurementAPI;
		if (isset($this->left_field_id) && $this->event->episode->legacy == 0) {
			$measurementL = MeasurementVisualFieldHumphrey::model()->findByPk($this->left_field_id);
			// is this an edit?
			if (isset($_POST['original_left_field_id'])) {
				$oldRef = $_POST['original_left_field_id'];
				if ($oldRef != $this->left_field_id) {
					$ref = MeasurementReference::model()->find("event_id=:event_id and patient_measurement_id=:pm_id", array(":event_id" => $this->event->id, ":pm_id" => $oldRef));
					$this->updateReference($ref, $measurementL, $this->event->id);
				}
			} else {
				$api->addReference($measurementL->patientMeasurement, $this->event);
			}
		}
		if (isset($this->right_field_id) && $this->event->episode->legacy == 0) {
			$measurementR = MeasurementVisualFieldHumphrey::model()->findByPk($this->right_field_id);
			// edit?
			if (isset($_POST['original_right_field_id'])) {
				$oldRef = $_POST['original_right_field_id'];
				if ($oldRef != $this->right_field_id) {
					$ref = MeasurementReference::model()->find("event_id=:event_id and patient_measurement_id=:pm_id", array(":event_id" => $this->event->id, ":pm_id" => $oldRef));
					$this->updateReference($ref, $measurementR, $this->event->id);
				}
			} else {
				$api->addReference($measurementR->patientMeasurement, $this->event);
			}
		}
		return true;
	}

	/**
	 * 
	 * @param type $old
	 * @param type $new
	 * @param type $event
	 */
	private function updateReference($old, $new) {
		$old->patient_measurement_id = $new->id;
		$old->save();
	}

	private function getPatientMeasurement($eye_id, $thumbnail_id) {
		return getFieldMeasurement($eye_id, $thumbnail_id)->patientMeasurement;
	}

	private function getFieldMeasurement($eye_id, $thumbnail_id) {
		Yii::import('application.modules.OphInVisualfields.models.MeasurementVisualFieldHumphrey');
		$criteria = new CdbCriteria;
		$criteria->condition = "eye_id=:eye_id AND cropped_image_id=:cropped_image_id";
		$criteria->params = array(":eye_id" => $eye_id, ":cropped_image_id" => $thumbnail_id);
		return MeasurementVisualFieldHumphrey::model()->find($criteria);
	}

}

?>