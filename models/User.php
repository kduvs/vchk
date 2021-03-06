<?php

namespace app\models;

use Yii;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            //[['username', 'password', 'name', 'surname'], 'required', 'message' => 'Заполните поле'],
            ['username', 'unique', 'targetClass' => User::className(),  'message' => 'Этот логин уже занят'],
            [['auth_key'], 'string', 'max' => 255],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Owners::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'username' => 'Логин',
            'password' => 'Пароль',
            'owner_id' => 'Owner_id'
        ];
    }

    public function getLogIssuings()
    {
        return $this->hasMany(LogIssuing::className(), ['issuing_id' => 'id']);
    }

    public function getLogIssuings0()
    {
        return $this->hasMany(LogIssuing::className(), ['recipient_id' => 'id']);
    }

    public function getLogIssuings1()
    {
        return $this->hasMany(LogIssuing::className(), ['taker_id' => 'id']);
    }

    public function getSectionUsers()
    {
        return $this->hasMany(SectionUser::className(), ['user_id' => 'id']);
    }

    public function getSections()
    {
        return $this->hasMany(Sections::className(), ['id' => 'section_id'])->viaTable('section_user', ['user_id' => 'id']);
    }

    public function getOwner()
    {
        return $this->hasOne(Owners::className(), ['id' => 'owner_id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }
 
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

}
