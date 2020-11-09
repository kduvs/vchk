<?php

namespace app\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;

abstract class SelectizeBehavior extends Behavior{

    private $_objects;

    private $_objectNames;

    protected $className; //tags
    protected $viaClassName; //book_tag
    protected $viaModelIdName; //book_id
    protected $viaClassIdName; //tag_id

    public function getObjects($obj = false){
        $query = $this->owner->hasMany($this->className, ['id' => $this->viaClassIdName])
                        ->viaTable($this->viaClassName, [$this->viaModelIdName => 'id']);
        if($obj === true){
            if(!isset($this->_objects)){
                $this->_objects = $query->all();
            }
            return $this->_objects;
        }
        
        return $query;
    }

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

    public function getObjectNamesArray(){
        $objects = $this->getObjects(true);
        $res = [];
        if(count($objects)){
            foreach($objects as $object){
                $res[$object->id] = $object->title;
            }
        }
        return $res;
    }

    public function getObjectNamesFormat($format = 'default', $delimeter = ', '){
        $objects = $this->getObjectNamesArray();
        $res = '';
        if($format == 'default'){
            if(count($objects)){
                foreach($objects as $key => $object){
                    $res .= $objects[$key];
                    $res .= $delimeter;
                }
            }
            $res = substr($res, 0, -1*strlen($delimeter));
        }
        return $res;
    } 

    

    public function getObjectNames(){
        if(!isset($this->_objectNames)){
            $my_objects = $this->getObjects(true);
            if(count($my_objects)){
                foreach($my_objects as $object){
                    $this->_objectNames .= $object->title;
                    $this->_objectNames .= ',';
                }
                $this->_objectNames = substr($this->_objectNames, 0, -1);
            } else {
                $this->_objectNames = '';
            }
        }
        return $this->_objectNames;
    }

    private function objectsInsert($cur_objects){
        $rows = [];
        foreach($cur_objects as $object){
            $rows[] = array($this->owner->id, $object->id);
        }
        if(count($rows) != 0){
            if(Yii::$app->db->createCommand()->batchInsert($this->viaClassName, [$this->viaModelIdName, $this->viaClassIdName], $rows)->execute()){
                return true;
            }
        }
        return false;
    }

    

    public function setObjects($some_objects){
        $this->_objects = $some_objects;
    }

    public function setObjectNames($some_objectNames){
        $this->_objectNames = $some_objectNames;
    }



    public function events(){
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterDelete($event){
        Yii::$app->db->createCommand()->delete($this->viaClassName, $this->viaModelIdName.' = :viaModelIdName', [':viaModelIdName' => $this->owner->id])->execute();
        //$this->owner->{parent::afterDelete()};
    }

    public function afterSave($event){
        //$this->owner->{parent::afterSave($event->insert, $event->changedAttributes)};
        $book_tag = [];
        $names = explode(",", $this->objectNames);
        $cur_objects = [];
        foreach($names as $name){
            $t = $this->className::find()->where(['title' => $name])->one();
            if(!$t){
                $t = new $this->className->class();
                $t->title = $name;
                $t->save();
            }
            $cur_objects[] = $t;
        }
        
        if($event->name == ActiveRecord::EVENT_AFTER_INSERT){
            $this->objectsInsert($cur_objects);
        } else {
            $db_objects = $this->getObjects(true);
            $x = [];
            $y = [];
            foreach($db_objects as $tag1){
                foreach($cur_objects as $tag2){
                    if($tag1->id == $tag2->id){
                        //unset($db_objects[$key]);
                        //unset($cur_objects[$key2]);
                        $x[] = $tag1;
                        $y[] = $tag2;
                    }
                }
            }
            foreach($x as $key => $obj){
                unset($db_objects[$key]);
            }
            foreach($y as $key => $obj){
                unset($cur_objects[$key]);
            }
            if(count($db_objects)){
                $object_ids = [];
                foreach($db_objects as $obj){   
                    $object_ids[] = $obj->id;
                }
                Yii::$app->db->createCommand()->delete($this->viaClassName, ['AND', $this->viaModelIdName.' = :viaModelIdName', ['IN', $this->viaClassIdName, $object_ids]], [':viaModelIdName' => $this->owner->id])->execute();   
            }
            $this->objectsInsert($cur_objects);
        }
    }
}