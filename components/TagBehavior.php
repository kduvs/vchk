<?php

namespace app\components;

use yii\base\Behavior;
use app\models\Tags;

class TagBehavior extends SelectizeBehavior{
    function __construct(){
        $this->className = Tags::className();
        $this->viaClassName = 'book_tag';
        $this->viaModelIdName = 'book_id';
        $this->viaClassIdName = 'tag_id';
    }
}
//можем так делать
//$obj = new $this->class();
//$this->class::deleteAll();