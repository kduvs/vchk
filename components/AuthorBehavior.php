<?php

namespace app\components;

use yii\base\Behavior;
use app\models\Authors;

class AuthorBehavior extends SelectizeBehavior{
    function __construct(){
        $this->className = Authors::className();
        $this->viaClassName = 'book_author';
        $this->viaModelIdName = 'book_id';
        $this->viaClassIdName = 'author_id';
    }
}