<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CmsHome;

/**
 * CmsHomeSearch represents the model behind the search form of `backend\models\CmsHome`.
 */
class CmsHomeSearch extends CmsHome
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_image_banner', 'fk_image_top_box_one', 'fk_image_top_box_two', 'fk_image_top_box_three', 'fk_image_abt', 'fk_image_mid_four_one', 'fk_image_mid_four_two', 'fk_image_mid_four_three', 'fk_image_mid_four_four', 'fk_image_circle_menu_one', 'fk_image_circle_menu_two', 'fk_image_circle_menu_three', 'fk_image_circle_menu_four'], 'integer'],
            [['title', 'sub_title', 'top_box_one_title', 'top_box_one_sub', 'top_box_two_title', 'top_box_two_sub', 'top_box_three_title', 'top_box_three_sub', 'abt_head_one', 'abt_head_two', 'abt_head_three', 'abt_head_four', 'mid_four_title', 'mid_four_sub_title', 'mid_four_one_title', 'mid_four_one_sub_title', 'mid_four_two_title', 'mid_four_two_sub_title', 'mid_four_three_title', 'mid_four_three_sub_title', 'mid_four_four_title', 'mid_four_four_sub_title', 'video_title', 'video_sub_title', 'video_url', 'circle_menu_one', 'circle_menu_two', 'circle_menu_three', 'circle_menu_four'], 'safe'],
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
        $query = CmsHome::find();

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
            'fk_image_banner' => $this->fk_image_banner,
            'fk_image_top_box_one' => $this->fk_image_top_box_one,
            'fk_image_top_box_two' => $this->fk_image_top_box_two,
            'fk_image_top_box_three' => $this->fk_image_top_box_three,
            'fk_image_abt' => $this->fk_image_abt,
            'fk_image_mid_four_one' => $this->fk_image_mid_four_one,
            'fk_image_mid_four_two' => $this->fk_image_mid_four_two,
            'fk_image_mid_four_three' => $this->fk_image_mid_four_three,
            'fk_image_mid_four_four' => $this->fk_image_mid_four_four,
            'fk_image_circle_menu_one' => $this->fk_image_circle_menu_one,
            'fk_image_circle_menu_two' => $this->fk_image_circle_menu_two,
            'fk_image_circle_menu_three' => $this->fk_image_circle_menu_three,
            'fk_image_circle_menu_four' => $this->fk_image_circle_menu_four,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'sub_title', $this->sub_title])
            ->andFilterWhere(['like', 'top_box_one_title', $this->top_box_one_title])
            ->andFilterWhere(['like', 'top_box_one_sub', $this->top_box_one_sub])
            ->andFilterWhere(['like', 'top_box_two_title', $this->top_box_two_title])
            ->andFilterWhere(['like', 'top_box_two_sub', $this->top_box_two_sub])
            ->andFilterWhere(['like', 'top_box_three_title', $this->top_box_three_title])
            ->andFilterWhere(['like', 'top_box_three_sub', $this->top_box_three_sub])
            ->andFilterWhere(['like', 'abt_head_one', $this->abt_head_one])
            ->andFilterWhere(['like', 'abt_head_two', $this->abt_head_two])
            ->andFilterWhere(['like', 'abt_head_three', $this->abt_head_three])
            ->andFilterWhere(['like', 'abt_head_four', $this->abt_head_four])
            ->andFilterWhere(['like', 'mid_four_title', $this->mid_four_title])
            ->andFilterWhere(['like', 'mid_four_sub_title', $this->mid_four_sub_title])
            ->andFilterWhere(['like', 'mid_four_one_title', $this->mid_four_one_title])
            ->andFilterWhere(['like', 'mid_four_one_sub_title', $this->mid_four_one_sub_title])
            ->andFilterWhere(['like', 'mid_four_two_title', $this->mid_four_two_title])
            ->andFilterWhere(['like', 'mid_four_two_sub_title', $this->mid_four_two_sub_title])
            ->andFilterWhere(['like', 'mid_four_three_title', $this->mid_four_three_title])
            ->andFilterWhere(['like', 'mid_four_three_sub_title', $this->mid_four_three_sub_title])
            ->andFilterWhere(['like', 'mid_four_four_title', $this->mid_four_four_title])
            ->andFilterWhere(['like', 'mid_four_four_sub_title', $this->mid_four_four_sub_title])
            ->andFilterWhere(['like', 'video_title', $this->video_title])
            ->andFilterWhere(['like', 'video_sub_title', $this->video_sub_title])
            ->andFilterWhere(['like', 'video_url', $this->video_url])
            ->andFilterWhere(['like', 'circle_menu_one', $this->circle_menu_one])
            ->andFilterWhere(['like', 'circle_menu_two', $this->circle_menu_two])
            ->andFilterWhere(['like', 'circle_menu_three', $this->circle_menu_three])
            ->andFilterWhere(['like', 'circle_menu_four', $this->circle_menu_four]);

        return $dataProvider;
    }
}
