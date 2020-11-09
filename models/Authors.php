<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string $fio ФИО
 *
 * @property BookAuthor[] $bookAuthors
 * @property Books[] $books
 */
class Authors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio'], 'required'],
            [['fio'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'ФИО',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::className(), ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::className(), ['id' => 'book_id'])->viaTable('book_author', ['author_id' => 'id']);
    }

    public function getTitle(){
        return $this->fio; //потом переделать в более хитроумное мб с тремя разными аргументами name surname otchestvo
    }
}
