<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Books;
use app\models\Authors;
use app\models\BookAuthor;

/**
 * BooksSearch represents the model behind the search form of `app\models\Books`.
 */
class BooksSearch extends Books
{
    /**
     * {@inheritdoc}
     */
    
    public $owner_title;
    public $authorNames; // для site/search-book

    public function attributes()
    {
        // делаем поле зависимости доступным для поиска
        return array_merge(parent::attributes(), ['authorNamesFormat', 'tagNamesFormat']);
    }

    public function rules()
    {
        return [
            [['id', 'owner_id'], 'integer'],
            [['title', 'description', 'qr_code', 'owner_title', 'tagNamesFormat', 'authorNamesFormat', 'search_text', 'authorNames'], 'safe'],
            ['description', 'string', 'max' => 10]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    public $search_text;

    public function search($params)
    {
        $query = Books::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // print_r($this->authorNamesFormat);
        // exit(0);

        // grid filtering conditions
        $query->andFilterWhere(['like', 'books.title', $this->title]);
        $query->andFilterWhere(['like', 'books.title', $this->search_text]);
        $query->andFilterWhere(['like', 'owner_id', $this->owner_id]);
        
        

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'qr_code', $this->qr_code])
            ->joinWith(['owner' => function($query) { $query->from(['owner' => 'owners']); }])
            //->andFilterWhere(['like', 'owner.title', $this->getAttribute('owner.title')]);
            ->andFilterWhere(['like', 'owner.title', $this->owner_title])
            //->joinWith(['authors' => function($query) { $query->from(['author' => 'authors']); }])
            ->joinWith(['authorB' => function($query) { $query->from(['author' => 'authors']); }])
            //->andFilterWhere(['like', 'author.fio', $this->getAttribute('author.fio')])
            ->andFilterWhere(['like', 'author.title', $this->getAttribute('authorNamesFormat')])
            ->joinWith(['tagB' => function($query) { $query->from(['tag' => 'tags']); }])
            ->andFilterWhere(['like', 'tag.title', $this->getAttribute('tagNamesFormat')]); //tagNamesFormat
            if(trim($this->authorNames) != ''){
                $authorNames = explode(",", $this->authorNames);
                $query->andFilterWhere(['author.title' => $authorNames]); //пока что запрос будет выдавать все книги, у которых есть хотя бы один из данных авторов
            }
            

        $dataProvider->sort->attributes['owner_title'] = [
            'asc' => ['owner.title' => SORT_ASC],
            'desc' => ['owner.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['authorNamesFormat'] = [
            'asc' => ['author.fio' => SORT_ASC],
            'desc' => ['author.fio' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['tagNamesFormat'] = [
            'asc' => ['tag.title' => SORT_ASC],
            'desc' => ['tag.title' => SORT_DESC],
        ]; //tagNamesFormat

        return $dataProvider;
    }
}
