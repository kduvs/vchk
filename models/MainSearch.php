<?php

namespace app\models;

use Yii;

class MainSearch extends Model
{
    public $tite;
    public $date_from;

    public static function tableName()
    {
        return '{{%flights}}';
    }

    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            ['description', 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'название',
            'description' => 'описание'
        ];
    }

    public function search($params)
    {

        $query = Flights::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if(!$this->validate()){
            /*$query->where('0=1');*/
            return $dataProvider;
        }

        $query->andFilterWhere(['>=', 'date_start', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
              ->andFilterWhere(['=', 'city_start_id', $this->city_from]);
        return $dataProvider;

    }
}