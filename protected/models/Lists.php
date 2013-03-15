<?php

/**
 * This is the model class for table "lists".
 *
 * The followings are the available columns in table 'lists':
 * @property integer $lid
 * @property string $name
 * @property string $count
 * @property int $collected
 */
class Lists extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lists the static model class
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
		return 'lists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, count', 'required'),
			array('lid, count', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('count', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, name, count', 'safe', 'on'=>'search'),
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
            'collected'=>array(self::STAT, 'PeopleToFollow', 'lid',
                'select'=>'max(`pos`)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => 'ID пользователя',
			'name' => 'Имя',
			'count' => 'Количество',
            'collected'=>'Просканировано',
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

		$criteria->compare('lid',$this->lid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('count',$this->count,true);
        //$criteria->compare('collected',$this->collected,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}