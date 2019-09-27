<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Schedule;

/**
 * ScheduleSearch represents the model behind the search form of `backend\models\Schedule`.
 */
class ScheduleSearchTest extends ScheduleTest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'lsgi_id', 'account_id_creator', 'week_day', 'month_day', 'repeat_day_count', 'service_id', 'activity_name', 'status'], 'integer'],
            [['date', 'created_at', 'modified_at'], 'safe'],
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
    public function search($params,$lsgi=null,$district=null,$keyword=null)
    {
        $query = ScheduleTest::find()->where(['schedule_test.status'=>1])->orderby('id DESC');

        // add conditions that should always apply here
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
        if($userRole=='supervisor'&&isset($modelUser->id))
    {
      $supervisor = $modelUser->id;
      $unit = $modelUser->green_action_unit_id;
      $query->andWhere(['green_action_unit_id'=>$unit]);
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
            'lsgi_id' => $this->lsgi_id,
            'account_id_creator' => $this->account_id_creator,
            'week_day' => $this->week_day,
            'month_day' => $this->month_day,
            'date' => $this->date,
            'repeat_day_count' => $this->repeat_day_count,
            'service_id' => $this->service_id,
            'activity_name' => $this->activity_name,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        return $dataProvider;
    }
}
