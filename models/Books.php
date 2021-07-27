<?php

namespace app\models;

use Yii;
use app\models\Tags;
use yii\db\ActiveRecord;
use app\components\AuthorBehavior;
use app\components\TagBehavior;


/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title Название книги
 * @property string $description Описание
 * @property int $owner_id Владелец
 * @property string $qr_code Qr-код
 *
 * @property BookAuthor[] $bookAuthors
 * @property Owners $owner
 * @property LogIssuing[] $logIssuings
 */
class Books extends \yii\db\ActiveRecord
{
    public $tagBehavior;
    public $authorBehavior;
    public function __construct(){
        $this->tagBehavior = $this->getBehavior('tagBehavior');
        $this->authorBehavior = $this->getBehavior('authorBehavior');
    }

public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if ($insert) {
            $this->crypt_key = Yii::$app->getSecurity()->generateRandomString(32);
            $this->qr_code = Yii::$app->getSecurity()->encryptByKey($this->tableName().'/'.$this->id, $this->crypt_key);
        }
        return true;
    } else {
        return false;
    }
    
}

    public function getTagB(){
        return $this->getBehavior('tagBehavior')->objects;
    }

    public function getAuthorB(){
        return $this->getBehavior('authorBehavior')->objects;
    }

    public function behaviors(){
        return [
            'tagBehavior' => TagBehavior::className(),
            'authorBehavior' => AuthorBehavior::className(),
        ];
    }

    public function getTagNames(){
        return $this->tagBehavior->objectNames;
    }

    public function setTagNames($tags){
        $this->tagBehavior->setObjectNames($tags);
    }

    public function setAuthorNames($tags){
        $this->authorBehavior->setObjectNames($tags);
    }

    public function getAuthorNames(){
        return $this->authorBehavior->objectNames;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'owner_id'], 'required'],
            [['description'], 'string'],
            [['tagNames'], 'safe'],
            [['authorNames'], 'safe'],
            [['objectNames'], 'safe'],
            [['owner_id', 'imagemanager_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['qr_code'], 'string', 'max' => 255],
            [['crypt_key'], 'string', 'max' => 32],
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
            'title' => 'Название книги',
            'description' => 'Описание',
            'owner_id' => 'Владелец',
            'imagemanager_id' => 'Изображение',
            'qr_code' => 'Qr-код',
            'tagNames' => 'Список тегов',
            'authorNames' => 'Список авторов',
            'objectNames' => 'Названия тегов',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::className(), ['book_id' => 'id']);
    }

    public function getAuthors()
    {
        return $this->hasMany(Authors::className(), ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
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

    /**
     * Gets query for [[LogIssuings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogIssuings()
    {
        return $this->hasMany(LogIssuing::className(), ['book_id' => 'id']);
    }
}
