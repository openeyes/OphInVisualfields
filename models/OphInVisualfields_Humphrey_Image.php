<?php

/**
 * This is the model class for table "fs_scan_humphrey_image".
 *
 * The followings are the available columns in table 'fs_scan_humphrey_image':
 * @property string $id
 * @property string $pid
 * @property string $given_name
 * @property string $middle_name
 * @property string $family_name
 * @property string $birth_date
 * @property string $study_date
 * @property string $study_time
 * @property string $gender
 * @property string $eye
 * @property string $file_name
 * @property string $test_strategy
 * @property string $last_modified_user_id
 * @property string $last_modified_date
 * @property string $created_user_id
 * @property string $created_date
 * @property string $file_id
 * @property string $tif_file_id
 * @property string $test_name
 *
 * The followings are the available model relations:
 * @property FsFile $file
 * @property FsScanHumphreyXml $tifFile
 * @property User $createdUser
 * @property User $lastModifiedUser
 */
class OphInVisualfields_Humphrey_Image extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FsScanHumphreyImage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ophinvisualfields_humphrey_image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file_id', 'required'),
			array('last_modified_user_id, created_user_id, file_id', 'length', 'max'=>10),
			array('last_modified_date, created_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, last_modified_user_id, last_modified_date, created_user_id, created_date, file_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'file' => array(self::BELONGS_TO, 'FsFile', 'file_id'),
			'xml' => array(self::BELONGS_TO, 'OphInVisualfields_Humphrey_Xml', 'xml_id'),
			'createdUser' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'lastModifiedUser' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'last_modified_user_id' => 'Last Modified User',
			'last_modified_date' => 'Last Modified Date',
			'created_user_id' => 'Created User',
			'created_date' => 'Created Date',
			'file_id' => 'File',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('test_strategy',$this->test_strategy,true);
		$criteria->compare('last_modified_user_id',$this->last_modified_user_id,true);
		$criteria->compare('last_modified_date',$this->last_modified_date,true);
		$criteria->compare('created_user_id',$this->created_user_id,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('file_id',$this->file_id,true);
//		$criteria->compare('xml_id',$this->xml_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    /**
     * 
     * @param type $file
     * @param type $subdir
     * @return type
     */
    public function getPath($subdir = '') {
      $path = null;
      if (Yii::app()->params['visualfields.file_system_paths']) {
        $path = Yii::app()->params['visualfields.file_system_paths'];
        if ($path['humphreys']) {
          $vfaPath = explode(':', $path['humphreys']);
          $realPath = $vfaPath[0];
          $oePath = $vfaPath[1];
          $path = str_replace($realPath, $oePath, $this->file->dir->path) . '/' . $subdir . '/';
        }
      }
      return $path;
    }
}