<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\GreenActionUnitWard;

/**
 * GreenActionUnitWardSearch represents the model behind the search form of `backend\models\GreenActionUnitWard`.
 */
class GreenActionUnitWardSearch extends GreenActionUnitWard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'green_action_unit_id', 'ward_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = GreenActionUnitWard::find()->where(['status'=>1])->orderby('id DESC');
        if($id)
        {
            $query->andWhere(['green_action_unit_id'=>$id]);
        }

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
            'green_action_unit_id' => $this->green_action_unit_id,
            'ward_id' => $this->ward_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        return $dataProvider;
    }
}
