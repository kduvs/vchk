<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogIssuingSearch;
use Yii;

class SentLogIssuingSearch extends LogIssuingSearch
{
    public function search($params)
    {
        $this->taker_id = Yii::$app->user->identity->id;
        return parent::search($params);
    }
}
