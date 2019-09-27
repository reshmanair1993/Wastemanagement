<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "evaluation_config_customer_rating".
 *
 * @property int $id
 * @property int $lsgi_id
 * @property double $rating_value
 * @property double $performance_point
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class EvaluationConfigCustomerRating extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation_config_customer_rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rating_value', 'performance_point'], 'required'],
            [['lsgi_id', 'status'], 'integer'],
            [['rating_value', 'performance_point'], 'number'],
             [['rating_value'],'new_and_unique'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lsgi_id' => Yii::t('app', 'Lsgi ID'),
            'rating_value' => Yii::t('app', 'Rating Value'),
            'performance_point' => Yii::t('app', 'Performance Point'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteRating($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('evaluation_config_customer_rating', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function new_and_unique($attribute,$params)
    {
        $err  = EvaluationConfigCustomerRating::find()->where(['rating_value'=>$this->rating_value])->andWhere(['status'=>1])->one();
        if($err)
        $this->addError($attribute,'Value is already taken ');
    }
}
