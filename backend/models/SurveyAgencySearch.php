<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SurveyAgency;

/**
 * SurveyAgencySearch represents the model behind the search form of `backend\models\SurveyAgency`.
 */
class SurveyAgencySearch extends SurveyAgency
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'lsgi_id', 'status'], 'integer'],
            [['contact_person_name', 'contact_person_number', 'contact_person_email', 'created_at', 'modified_at'], 'safe'],
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
        $lsgi = null;
        $query = SurveyAgency::find()->where(['status'=>1])->orderby('id ASC');
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if($lsgi)
        {
            $query->andWhere(['lsgi_id'=>$lsgi]);
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
            'name' => $this->name,
            'lsgi_id' => $this->lsgi_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'contact_person_name', $this->contact_person_name])
            ->andFilterWhere(['like', 'contact_person_number', $this->contact_person_number])
            ->andFilterWhere(['like', 'contact_person_email', $this->contact_person_email]);

        return $dataProvider;
    }
}
