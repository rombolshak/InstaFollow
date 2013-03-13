<?php

/**
 * This is the model class for table "peopleToFollow".
 *
 * The followings are the available columns in table 'peopleToFollow':
 * @property string $fid
 * @property string $lid
 * @property string $pos
 */
class PeopleToFollow extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PeopleToFollow the static model class
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
		return 'peopleToFollow';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fid, lid', 'required'),
			array('fid, lid', 'length', 'max'=>11),
			array('pos', 'length', 'max'=>9),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('fid, lid, pos', 'safe', 'on'=>'search'),
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
			'fid' => 'Fid',
			'lid' => 'Lid',
			'pos' => 'Pos',
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

		$criteria->compare('fid',$this->fid,true);
		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('pos',$this->pos,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}