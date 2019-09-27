<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ServicingStatusOption;

/**
 * ServicingStatusOptionSearch represents the model behind the search form of `backend\models\ServicingStatusOption`.
 */
class ServicingStatusOptionSearch extends ServicingStatusOption
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'service_id', 'image_id', 'status'], 'integer'],
            [['value', 'created_at', 'modified_at'], 'safe'],
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
    public function search($params,$id=null)
    {
        $query = ServicingStatusOption::find()->where(['status'=>1])->orderby('id DESC');
        if($id)
        {
            $query->andWhere(['service_id'=>$id]);
        }

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
            'service_id' => $this->service_id,
            'image_id' => $this->image_id,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
