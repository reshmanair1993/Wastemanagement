<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "evaluation_config_completion_percentage".
 *
 * @property int $id
 * @property int $lsgi_id
 * @property double $start_value_percent
 * @property double $end_value_percent
 * @property double $performance_point
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class EvaluationConfigCompletionPercentage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation_config_completion_percentage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_value_percent','end_value_percent','performance_point'], 'required'],
            [['lsgi_id', 'status'], 'integer'],
            [['start_value_percent', 'end_value_percent', 'performance_point'], 'number'],
            // [['end_value_percent'],'new_and_unique'],
            [['created_at', 'modified_at','service_id'], 'safe'],
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
            'start_value_percent' => Yii::t('app', 'Start Value Percent'),
            'end_value_percent' => Yii::t('app', 'End Value Percent'),
            'performance_point' => Yii::t('app', 'Performance Point'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

public function deleteCompletionTime($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('evaluation_config_completion_percentage', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function new_and_unique($attribute,$params)
    {
        $err  = EvaluationConfigCompletionPercentage::find()->where(['>=','end_value_percent',$this->start_value_percent])
        ->andWhere(['<=','start_value_percent',$this->start_value_percent])
        ->andWhere(['service_id'=>$this->service_id])
        ->andWhere(['status'=>1])
        ->one();
        if($err)
        $this->addError($attribute,'Range is already taken ');
    }
    public function getFkService()
        {
                return $this->hasOne(Service::className(), ['id' => 'service_id']);
        }
}
