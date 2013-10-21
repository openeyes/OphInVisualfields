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
class OphInVisualfields_Humphrey_Xml extends CActiveRecord
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
			array('pid, file_name, test_strategy, file_id, test_name', 'required'),
			array('pid', 'length', 'max'=>40),
			array('given_name, middle_name, family_name', 'length', 'max'=>50),
			array('birth_date, study_date, last_modified_user_id, created_user_id, file_id, tif_file_id', 'length', 'max'=>10),
			array('study_time', 'length', 'max'=>12),
			array('gender, eye', 'length', 'max'=>1),
			array('file_name, test_strategy, test_name', 'length', 'max'=>100),
			array('last_modified_date, created_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pid, given_name, middle_name, family_name, birth_date, study_date, study_time, gender, eye, file_name, test_strategy, last_modified_user_id, last_modified_date, created_user_id, created_date, file_id, tif_file_id, test_name', 'safe', 'on'=>'search'),
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
			'fsScanHumphreyImage' => array(self::BELONGS_TO, 'OphInVisualfields_Humphrey_Image', 'tif_file_id'),
			'file' => array(self::BELONGS_TO, 'FsFile', 'file_id'),
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
		$criteria->compare('last_modified_user_id',$this->last_modified_user_id,true);
		$criteria->compare('last_modified_date',$this->last_modified_date,true);
		$criteria->compare('created_user_id',$this->created_user_id,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('file_id',$this->file_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}