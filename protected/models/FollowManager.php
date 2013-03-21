<?php

/**
 * This is the model class for table "followManager".
 *
 * The followings are the available columns in table 'followManager':
 * @property string $uid
 * @property string $lid
 * @property string $pos
 * @property string $status
 * @property integer $paused
 * @property string $time
 */
class FollowManager extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FollowManager the static model class
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
		return 'followManager';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid', 'required'),
			array('paused', 'numerical', 'integerOnly'=>true),
			array('uid, lid, pos', 'length', 'max'=>11),
			array('status', 'length', 'max'=>10),
			array('time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('uid, lid, pos, status, paused, time', 'safe', 'on'=>'search'),
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
            'user'=>array(self::HAS_ONE, "Users", "uid"),
            'list'=>array(self::HAS_ONE, "Lists", "lid"),
            'people'=>array(self::HAS_MANY, "PeopleToFollow", 'lid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uid' => 'Uid',
			'lid' => 'Спмсок',
			'pos' => 'Pos',
			'status' => 'Статус',
			'paused' => 'Paused',
			'time' => 'Time',
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

		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('pos',$this->pos,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('paused',$this->paused);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}