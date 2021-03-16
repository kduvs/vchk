<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogIssuingSearch;
use Yii;

class InboxLogIssuingSearch extends LogIssuingSearch
{
    public function search($params)
    {
        $this->owner_id = Yii::$app->user->identity->owner_id;
        return parent::search($params);
    }
}