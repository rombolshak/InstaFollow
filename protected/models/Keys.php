<?php

/**
 * This is the model class for table "keys".
 *
 * The followings are the available columns in table 'keys':
 * @property string $kid
 * @property string $clientId
 * @property string $clientSecret
 */
class Keys extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Keys the static model class
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
		return 'keys';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clientId, clientSecret', 'required'),
			array('clientId, clientSecret', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('kid, clientId, clientSecret', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'kid' => 'Kid',
			'clientId' => 'Client ID',
			'clientSecret' => 'Client Secret',
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

		$criteria->compare('kid',$this->kid,true);
		$criteria->compare('clientId',$this->clientId,true);
		$criteria->compare('clientSecret',$this->clientSecret,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}