<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property int $uid
 * @property string $name
 * @property string $picture
 * @property int $followers
 * @property int $follows
 *
 * @property UserTokens tokens
 * @property int tokensCount
 * @property FollowManager manager
 */
class Users extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, picture', 'required'),
			array('uid', 'length', 'max'=>11),
			array('name', 'length', 'max'=>50),
			array('picture', 'length', 'max'=>100),
			array('followers, follows', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('uid, name, picture, followers, follows', 'safe', 'on'=>'search'),
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
            'tokens' => array(self::HAS_MANY, 'UserTokens', 'uid'),
            'manager'=>array(self::HAS_ONE, 'FollowManager', 'uid'),
            'tokensCount'=>array(self::STAT, 'UserTokens', 'uid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uid' => 'ID',
			'name' => 'Имя',
			'picture' => '',
			'followers' => 'Фолловеров',
            'follows' => 'Фолловит',
		);
	}

    public function getStatus()
    {
        if ($this->manager->paused == 1)
            return '<span class="label label-inverse">Приостановлено</span>';
        switch ($this->manager->status) {
            case 'notStarted': return '<span class="label label-important">Не запущено</span>';
            case 'follow': return '<span class="label label-info">Follow</span>';
            case 'wait': $time = strtotime($this->manager->time);
                return '<span class="label label-warning">Ждем</span><br />'.date("d.m.y H:i", $time);
            case 'unfollow': return '<span class="label label-default">Unfollow</span>';
            case 'done': return '<span class="label label-success">Закончено</span>';
        }
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
        $criteria->with = 'manager';

		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('picture',$this->picture,true);
		$criteria->compare('followers',$this->followers,true);
        $criteria->compare('follows', $this->follows, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}