<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sections".
 *
 * @property int $id
 * @property string $title Наименование
 * @property string $description Описание
 * @property int $owner_id
 *
 * @property SectionUser[] $sectionUsers
 * @property User[] $users
 * @property Owners $owner
 */
class Sections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'owner_id'], 'required'],
            [['description'], 'string'],
            [['owner_id'], 'integer'],
            [['title'], 'string', 'max' => 155],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Owners::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'description' => 'Описание',
            'owner_id' => 'Owner ID',
        ];
    }

    /**
     * Gets query for [[SectionUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSectionUsers()
    {
        return $this->hasMany(SectionUser::className(), ['section_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('section_user', ['section_id' => 'id']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(Owners::className(), ['id' => 'owner_id']);
    }
}
