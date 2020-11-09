<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property int $id
 * @property string $title Название
 *
 * @property BookTag[] $bookTags
 * @property Books[] $books
 * @property TagTree[] $tagTrees
 * @property TagTree[] $tagTrees0
 * @property Tags[] $descendants
 * @property Tags[] $ancestors
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
        ];
    }

    /**
     * Gets query for [[BookTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookTags()
    {
        return $this->hasMany(BookTag::className(), ['tag_id' => 'id']);
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::className(), ['id' => 'book_id'])->viaTable('book_tag', ['tag_id' => 'id']);
    }

    /**
     * Gets query for [[TagTrees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagTrees()
    {
        return $this->hasMany(TagTree::className(), ['ancestor' => 'id']);
    }

    /**
     * Gets query for [[TagTrees0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagTrees0()
    {
        return $this->hasMany(TagTree::className(), ['descendant' => 'id']);
    }

    /**
     * Gets query for [[Descendants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDescendants()
    {
        return $this->hasMany(Tags::className(), ['id' => 'descendant'])->viaTable('tag_tree', ['ancestor' => 'id']);
    }

    /**
     * Gets query for [[Ancestors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAncestors()
    {
        return $this->hasMany(Tags::className(), ['id' => 'ancestor'])->viaTable('tag_tree', ['descendant' => 'id']);
    }
}
