<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "owners".
 *
 * @property int $id
 * @property string $title
 *
 * @property Books[] $books
 * @property LogIssuing[] $logIssuings
 * @property Sections[] $sections
 * @property User[] $users
 */
class Owners extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'owners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::className(), ['owner_id' => 'id']);
    }

    /**
     * Gets query for [[LogIssuings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogIssuings()
    {
        return $this->hasMany(LogIssuing::className(), ['owner_id' => 'id']);
    }

    /**
     * Gets query for [[Sections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Sections::className(), ['owner_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['owner_id' => 'id']);
    }
}
