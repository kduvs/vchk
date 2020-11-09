<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag_tree".
 *
 * @property int $ancestor
 * @property int $descendant
 *
 * @property Tags $ancestor0
 * @property Tags $descendant0
 */
class TagTree extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag_tree';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ancestor', 'descendant'], 'required'],
            [['ancestor', 'descendant'], 'integer'],
            [['ancestor', 'descendant'], 'unique', 'targetAttribute' => ['ancestor', 'descendant']],
            [['ancestor'], 'exist', 'skipOnError' => true, 'targetClass' => Tags::className(), 'targetAttribute' => ['ancestor' => 'id']],
            [['descendant'], 'exist', 'skipOnError' => true, 'targetClass' => Tags::className(), 'targetAttribute' => ['descendant' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ancestor' => 'Ancestor',
            'descendant' => 'Descendant',
        ];
    }

    /**
     * Gets query for [[Ancestor0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAncestor0()
    {
        return $this->hasOne(Tags::className(), ['id' => 'ancestor']);
    }

    /**
     * Gets query for [[Descendant0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDescendant0()
    {
        return $this->hasOne(Tags::className(), ['id' => 'descendant']);
    }
}
