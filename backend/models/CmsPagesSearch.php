<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CmsPages;

/**
 * CmsPagesSearch represents the model behind the search form of `backend\models\CmsPages`.
 */
class CmsPagesSearch extends CmsPages
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'featured_image_id', 'status'], 'integer'],
            [['name', 'title_en', 'title_ml', 'sub_title_en', 'sub_title_ml', 'description_en', 'description_ml', 'slug', 'date', 'created_at', 'modified_at'], 'safe'],
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
    public function search($params)
    {
        $query = CmsPages::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'featured_image_id' => $this->featured_image_id,
            'date' => $this->date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title_en', $this->title_en])
            ->andFilterWhere(['like', 'title_ml', $this->title_ml])
            ->andFilterWhere(['like', 'sub_title_en', $this->sub_title_en])
            ->andFilterWhere(['like', 'sub_title_ml', $this->sub_title_ml])
            ->andFilterWhere(['like', 'description_en', $this->description_en])
            ->andFilterWhere(['like', 'description_ml', $this->description_ml])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
