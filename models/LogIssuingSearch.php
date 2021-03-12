<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogIssuing;
use Yii;

class LogIssuingSearch extends LogIssuing
{
    private $taker;
    private $book;
    public function rules()
    {
        return [
            [['id', 'owner_id', 'taker_id'], 'integer'],
            [['deadline', 'message', 'response', 'taker', 'book'], 'safe'],
            [['message', 'response'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LogIssuing::find()->where(['log_issuing.owner_id' => Yii::$app->user->identity->owner_id]);
        // print_r($query->all());
        // exit(0);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'deadline', $this->deadline])
        ->joinWith(['taker' => function($query) { $query->from(['user' => 'user']); }])
            ->andFilterWhere(['like', 'user.username', $this->taker])
        ->joinWith(['book' => function($query) { $query->from(['book' => 'books']); }])
            ->andFilterWhere(['like', 'book.title', $this->book]);

        $dataProvider->sort->attributes['taker'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['book'] = [
            'asc' => ['book.title' => SORT_ASC],
            'desc' => ['book.title' => SORT_DESC],
        ];

        return $dataProvider;
    }
}
