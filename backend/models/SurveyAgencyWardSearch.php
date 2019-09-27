<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SurveyAgencyWard;

/**
 * GreenActionUnitWardSearch represents the model behind the search form of `backend\models\GreenActionUnitWard`.
 */
class SurveyAgencyWardSearch extends SurveyAgencyWard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_agency_id', 'ward_id', 'status'], 'integer'],
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
        $query = SurveyAgencyWard::find()->where(['status'=>1])->orderby('id DESC');
        if($id)
        {
            $query->andWhere(['survey_agency_id'=>$id]);
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
            'survey_agency_id' => $this->survey_agency_id,
            
        ]);

        return $dataProvider;
    }
}
