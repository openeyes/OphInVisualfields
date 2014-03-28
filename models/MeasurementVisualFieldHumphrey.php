<?php

/**
 * This is the model class for table "ophinvisualfields_field_measurement".
 *
 * The followings are the available columns in table 'ophinvisualfields_field_measurement':
 * @property string $id
 * @property string $patient_measurement_id
 * @property integer $deleted
 * @property string $patient_id
 * @property string $eye_id
 * @property string $image_id
 * @property string $cropped_image_id
 * @property string $strategy_id
 * @property string $pattern_id
 *
 * The followings are the available model relations:
 * @property ProtectedFile $croppedImage
 * @property Eye $eye
 * @property ProtectedFile $image
 * @property OphinvisualfieldsPattern $pattern
 * @property Patient $patient
 * @property PatientMeasurement $patientMeasurement
 * @property OphinvisualfieldsStrategy $strategy
 * @property OphinvisualfieldsFieldMeasurementVersion[] $ophinvisualfieldsFieldMeasurementVersions
 */
class MeasurementVisualFieldHumphrey extends BaseActiveRecordVersioned
{

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return OphinvisualfieldsFieldMeasurement the static model class
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
        return 'ophinvisualfields_field_measurement';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('patient_measurement_id, patient_id, eye_id, image_id, cropped_image_id, strategy_id, pattern_id, study_datetime', 'required'),
            array('deleted', 'numerical', 'integerOnly'=>true),
            array('patient_measurement_id, patient_id, legacy, eye_id, image_id, cropped_image_id, strategy_id, pattern_id', 'length', 'max'=>10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, patient_measurement_id, deleted, legacy, patient_id, eye_id, image_id, cropped_image_id, strategy_id, pattern_id, study_datetime', 'safe', 'on'=>'search'),
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
            'cropped_image' => array(self::BELONGS_TO, 'ProtectedFile', 'cropped_image_id'),
            'eye' => array(self::BELONGS_TO, 'Eye', 'eye_id'),
            'image' => array(self::BELONGS_TO, 'ProtectedFile', 'image_id'),
            'pattern' => array(self::BELONGS_TO, 'OphInVisualfields_Pattern', 'pattern_id'),
            'patient' => array(self::BELONGS_TO, 'Patient', 'patient_id'),
            'patientMeasurement' => array(self::BELONGS_TO, 'PatientMeasurement', 'patient_measurement_id'),
            'strategy' => array(self::BELONGS_TO, 'OphInVisualfields_Strategy', 'strategy_id'),
            'ophinvisualfieldsFieldMeasurementVersions' => array(self::HAS_MANY, 'OphinvisualfieldsFieldMeasurementVersion', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'patient_measurement_id' => 'Patient Measurement',
            'deleted' => 'Deleted',
            'patient_id' => 'Patient',
            'eye_id' => 'Eye',
            'legacy' => 'Legacy',
            'image_id' => 'Image',
            'cropped_image_id' => 'Cropped Image',
            'strategy_id' => 'Strategy',
            'pattern_id' => 'Pattern',
            'study_datetime' => 'Study Datetime',
            'source' => 'Source',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('patient_measurement_id',$this->patient_measurement_id,true);
        $criteria->compare('deleted',$this->deleted);
        $criteria->compare('patient_id',$this->patient_id,true);
        $criteria->compare('legacy',$this->legacy,true);
        $criteria->compare('eye_id',$this->eye_id,true);
        $criteria->compare('image_id',$this->image_id,true);
        $criteria->compare('cropped_image_id',$this->cropped_image_id,true);
        $criteria->compare('strategy_id',$this->strategy_id,true);
        $criteria->compare('pattern_id',$this->pattern_id,true);
        $criteria->compare('study_datetime',$this->study_datetime,true);
        $criteria->compare('source',$this->source,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}