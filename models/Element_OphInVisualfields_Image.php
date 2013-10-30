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
 * @property integer $left_image
 * @property integer $right_image
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
  private $oldLeftImage = null;
  private $oldRightImage = null;

  /**
   * Returns the static model of the specified AR class.
   * @return the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * Update with new values and store the old values as previously selected.
   * Important for unsetting any old IDs on the previous images.
   * 
   * @param type $name
   * @param type $value
   * @return type
   */
  public function setAttribute($name, $value) {
    if ($name == 'left_image' && $this->left_image != $value) {
      $this->oldLeftImage = $this->left_image;
      $this->left_image = $value;
    }
    if ($name == 'right_image' && $this->right_image != $value) {
      $this->oldRightImage = $this->right_image;
      $this->right_image = $value;
    }
    return parent::setAttribute($name, $value);
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
        array('event_id, left_image, right_image, ', 'safe'),
        array('left_image, right_image, ', 'required'),
        array('left_image, right_image, ', 'validateImages'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('id, event_id, left_image, right_image, ', 'safe', 'on' => 'search'),
        array('left_image', 'numerical', 'integerOnly' => true, 'min' => 1, 'message' => 'Left image must be higher or equal to 1'),
    );
  }

  /**
   * At least one image must be set.
   * 
   * @return boolean true if either image is set.
   */
  public function validateImages() {
    return $this->right_image || $this->left_image;
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
        'left_tif' => array(self::BELONGS_TO, 'FsFile', 'left_image'),
        'right_tif' => array(self::BELONGS_TO, 'FsFile', 'right_image'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'id' => 'ID',
        'event_id' => 'Event',
        'left_image' => 'Left image',
        'right_image' => 'Right image',
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
    $criteria->compare('left_image', $this->left_image);
    $criteria->compare('right_image', $this->right_image);

    return new CActiveDataProvider(get_class($this), array(
                'criteria' => $criteria,
            ));
  }

  protected function beforeSave() {
    return parent::beforeSave();
  }

  protected function afterSave() {
    $this->updateImagePairs($this->left_image, $this->right_image, $this->oldLeftImage, $this->oldRightImage);
    return parent::afterSave();
  }

  protected function beforeValidate() {
    return parent::beforeValidate();
  }

  /**
   * Update the new left and right images as associated with a test; if
   * old image IDs are not null, unset them as being associated.
   * 
   * @param int $leftImageNew the left image to mark as associated with this
   * test.
   * @param int $rightImageNew the right image to mark as associated with this
   * test.
   * @param int $leftImageOld if set, will unmark this image as associated
   * with a test.
   * @param int $rightImageOld if set, will unmark this image as associated
   * with a test.
   */
  private function updateImagePairs($leftImageNew, $rightImageNew, $leftImageOld, $rightImageOld) {
    $doc = new ScannedDocument;
    $patient = $this->event->episode->patient;
    if ($leftImageNew) {
      $image = OphInVisualfields_Humphrey_Xml::model()->find('tif_file_id=' . $leftImageNew);
//      $tif = $doc->getScannedDocument('humphreys', $patient->hos_num, $leftImageNew);
//      $image = FsScanHumphreyXml::model()->find('tif_file_id=' . $tif->id);
      $image->associated = 1;
      $image->save();
    }
    if ($rightImageNew) {
      $image = OphInVisualfields_Humphrey_Xml::model()->find('tif_file_id=' . $rightImageNew);
//      $tif = $doc->getScannedDocument('humphreys', $patient->hos_num, $rightImageNew);
//      $image = FsScanHumphreyXml::model()->find('tif_file_id=' . $tif->id);
      $image->associated = 1;
      $image->save();
    }
    if ($leftImageOld) {
      $image = OphInVisualfields_Humphrey_Xml::model()->find('tif_file_id=' . $leftImageOld);
//      $tif = $doc->getScannedDocument('humphreys', $patient->hos_num, $leftImageOld);
//      $image = FsScanHumphreyXml::model()->find('tif_file_id=' . $tif->id);
      $image->associated = 0;
      $image->save();
    }
    if ($rightImageOld) {
      $image = OphInVisualfields_Humphrey_Xml::model()->find('tif_file_id=' . $rightImageOld);
//      $tif = $doc->getScannedDocument('humphreys', $patient->hos_num, $rightImageOld);
//      $image = FsScanHumphreyXml::model()->find('tif_file_id=' . $tif->id);
      $image->associated = 0;
      $image->save();
    }
  }

  public static function getSubSpecialitySubimage($subspeciality) {
    $subsp_subimage = Yii::app()->params['visualfields.subspeciality_subimage']['humphreys'];
//    $name = ;
    if (isset($subsp_subimage[$subspeciality])) {
      $image_type = $subsp_subimage[$subspeciality];
    } else if (isset($subsp_subimage['default'])) {
      $image_type = $subsp_subimage['default'];
    }
    return $image_type;
  }

  public static function getImageWidth($image_type) {

    $config = Yii::app()->params['visualfields.subimages']['humphreys'];
    if (isset($config[$image_type]['scale'])) {
      $data = $config[$image_type]['scale'];
      $dims = explode('x', $data);
      $width = $dims[0];
    } else if (isset($config[$image_type])) {
      $data = $config[$image_type]['crop'];
      $dims = explode(',', $data);
      $width = $dims[0];
    }
    return $width;
  }

  public static function getImageHeight($image_type) {

    $config = Yii::app()->params['visualfields.subimages']['humphreys'];
    if (isset($config[$image_type]['scale'])) {
      $data = $config[$image_type]['scale'];
      $dims = explode('x', $data);
      $height = $dims[1];
    }else if (isset($config[$image_type])) {
      $data = $config[$image_type]['crop'];
      $dims = explode(',', $data);
      $height = $dims[1];
    }
    return $height;
  }

}

?>