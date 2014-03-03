<?php

/**
 * This is the model class for table "fs_scan_humphrey_xml".
 *
 * The followings are the available columns in table 'fs_scan_humphrey_xml':
 * @property string $id
 * @property string $last_modified_user_id
 * @property string $last_modified_date
 * @property string $created_user_id
 * @property string $created_date
 * @property string $file_id
 *
 * The followings are the available model relations:
 * @property FsScanHumphreyImage[] $fsScanHumphreyImages
 * @property FsFile $file
 * @property User $createdUser
 * @property User $lastModifiedUser
 */
class OphInVisualfields_Humphrey_Xml extends BaseActiveRecordVersionedSoftDelete
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FsScanHumphreyXml the static model class
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
		return 'ophinvisualfields_humphrey_xml';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('patient_id, test_strategy, test_name', 'required'),
			array('patient_id', 'length', 'max'=>40),
			array('study_datetime', 'length', 'max'=>40),
			array('test_strategy, test_name', 'length', 'max'=>100),
			array('last_modified_date, created_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, patient_id, study_datetime, test_strategy, last_modified_user_id, last_modified_date, created_user_id, created_date, test_name', 'safe', 'on'=>'search'),
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
			'eye' => array(self::BELONGS_TO, 'Eye', 'eye_id'),
			'patient' => array(self::BELONGS_TO, 'Patient', 'patient_id'),
			'protected_file' => array(self::BELONGS_TO, 'ProtectedFile', 'humphrey_image_id'),
			'cropped_image' => array(self::BELONGS_TO, 'ProtectedFile', 'cropped_image_id'),
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
		$criteria->compare('patient_id',$this->patient_id,true);
		$criteria->compare('eye',$this->patient_id,true);
		$criteria->compare('study_datetime',$this->study_datetime,true);
		$criteria->compare('last_modified_user_id',$this->last_modified_user_id,true);
		$criteria->compare('last_modified_date',$this->last_modified_date,true);
		$criteria->compare('created_user_id',$this->created_user_id,true);
		$criteria->compare('created_date',$this->created_date,true);
//		$criteria->compare('file_id',$this->file_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}