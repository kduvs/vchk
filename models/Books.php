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

    // private $_tags;
    // private $_tagNames;

    // public function getTags($obj = false){
    //     $query = $this->hasMany(Tags::className(), ['id' => 'tag_id'])
    //                     ->viaTable('book_tag', ['book_id' => 'id']);
    //     if($obj === true){
    //         if(!isset($this->_tags)){
    //             $this->_tags = $query->all();
    //         }
    //         return $this->_tags;    
    //     }
    //     return $query;
    // }

    // public function getTagNamesArray(){
    //     $tags = $this->getTags(true);
    //     $res = [];
    //     if(count($tags)){
    //         foreach($tags as $tag){
    //             $res[$tag->id] = $tag->title;
    //         }
    //     }
    //     return $res;
    // }

    // public function getTagNamesFormat($format = 'default', $delimeter = ', '){
    //     $tags = $this->getTagNamesArray();
    //     $res = '';
    //     if($format == 'default'){
    //         if(count($tags)){
    //             foreach($tags as $key => $tag){
    //                 $res .= $tags[$key];
    //                 $res .= $delimeter;
    //             }
    //         }
    //         $res = substr($res, 0, -1*strlen($delimeter));
    //     }
    //     return $res;
    // } 

    

    // public function getTagNames(){
    //     if(!isset($this->_tagNames)){
    //         $my_tags = $this->getTags(true);
    //         if(count($my_tags)){
    //             foreach($my_tags as $tag){
    //                 $this->_tagNames .= $tag->title;
    //                 $this->_tagNames .= ',';
    //             }
    //             $this->_tagNames = substr($this->_tagNames, 0, -1);
    //         } else {
    //             $this->_tagNames = '';
    //         }
    //     }
    //     return $this->_tagNames;
    // }

    // private function tagInsert($cur_tags){
    //     if(count($cur_tags)){
    //         $rows = [];
    //         foreach($cur_tags as $tag){
    //             $rows[] = array($this->id, $tag->id);
    //         }
    //         if(count($rows) != 0){
    //             if(Yii::$app->db->createCommand()->batchInsert('book_tag', ['book_id', 'tag_id'], $rows)->execute()){
    //                 return true;
    //             }
    //         }
    //     }
    //     return false;
    // }

    // public function afterSave($insert, $changedAttributes){
    //     parent::afterSave($insert, $changedAttributes);
    //     $book_tag = [];
    //     $names = explode(",", $this->tagNames);
    //     $cur_tags = [];
    //     foreach($names as $name){
    //         $t = Tags::find()->where(['title' => $name])->one();
    //         if(!$t){
    //             if($name){
    //                 $t = new Tags();
    //                 $t->title = $name;
    //                 $t->save();
    //                 $cur_tags[] = $t;
    //             }
    //         } else {
    //             $cur_tags[] = $t;
    //         }
            
    //     }
        
    //     if($insert){
    //         $this->tagInsert($cur_tags);
    //     } else {
    //         $db_tags = $this->getTags(true);
    //         $x = [];
    //         $y = [];
    //         foreach($db_tags as $tag1){
    //             foreach($cur_tags as $tag2){
    //                 if($tag1->id == $tag2->id){
    //                     //unset($db_tags[$key]);
    //                     //unset($cur_tags[$key2]);
    //                     $x[] = $tag1;
    //                     $y[] = $tag2;
    //                 }
    //             }
    //         }
    //         foreach($x as $key=> $obj){
    //             unset($db_tags[$key]);
    //         }
    //         foreach($y as $key => $obj){
    //             unset($cur_tags[$key]);
    //         }
    //         if(count($db_tags)){
    //             $tag_ids = [];
    //             foreach($db_tags as $tag){   
    //                 $tag_ids[] = $tag->id;
    //             }
    //             Yii::$app->db->createCommand()->delete('book_tag', ['AND', 'book_id = :book_id', ['IN', 'tag_id', $tag_ids]], [':book_id' => $this->id])->execute();   
    //         }
    //         $this->tagInsert($cur_tags);
    //     }
    // }

    // public function setTags($some_tags){
    //     $this->_tags = $some_tags;
    // }

    // public function setTagNames($some_tagNames){
    //     $this->_tagNames = $some_tagNames;
    // }

    // public function afterDelete(){
    //     Yii::$app->db->createCommand()->delete('book_tag', 'book_id = :book_id', [':book_id' => $this->id])->execute();
    //     parent::afterDelete();
    // }

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
            [['title', 'description', 'owner_id', 'qr_code'], 'required'],
            [['description'], 'string'],
            [['tagNames'], 'safe'],
            [['authorNames'], 'safe'],
            [['objectNames'], 'safe'],
            [['owner_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['qr_code'], 'string', 'max' => 55],
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
