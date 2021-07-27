<?php

namespace app\models;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $name;
    public $surname;

    public function rules() {
        return [
            [['username', 'password', 'name', 'surname'], 'required', 'message' => 'Заполните поле'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'name' => 'Имя',
            'surname' => 'Фамилия',
        ];
    }
}