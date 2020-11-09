<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section_user".
 *
 * @property int $section_id
 * @property int $user_id
 *
 * @property Sections $section
 * @property User $user
 */
class SectionUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_id', 'user_id'], 'required'],
            [['section_id', 'user_id'], 'integer'],
            [['section_id', 'user_id'], 'unique', 'targetAttribute' => ['section_id', 'user_id']],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sections::className(), 'targetAttribute' => ['section_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'section_id' => 'Section ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Section]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Sections::className(), ['id' => 'section_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
