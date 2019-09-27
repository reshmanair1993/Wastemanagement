<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WasteCollectionInterval;

/**
 * WasteCollectionIntervalSearch represents the model behind the search form of `app\models\WasteCollectionInterval`.
 */
class WasteCollectionIntervalSearch extends WasteCollectionInterval
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'waste_category_id', 'is_public', 'sort_order', 'status'], 'integer'],
            [['name', 'created_at', 'modified_at','building_type_available'], 'safe'],
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
        $query = WasteCollectionInterval::find()->where(['status'=>1])->orderby('sort_order ASC');

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
            'waste_category_id' => $this->waste_category_id,
            'building_type_available' => $this->building_type_available,
            'is_public' => $this->is_public,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
