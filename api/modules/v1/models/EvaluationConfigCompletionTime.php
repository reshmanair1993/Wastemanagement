<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "evaluation_config_completion_time".
 *
 * @property int $id
 * @property int $lsgi_id
 * @property double $start_value_minutes
 * @property double $end_value_minutes
 * @property double $performance_point
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class EvaluationConfigCompletionTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation_config_completion_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lsgi_id','start_value_minutes', 'end_value_minutes', 'performance_point'], 'required'],
            [['lsgi_id', 'status'], 'integer'],
            [['start_value_minutes', 'end_value_minutes', 'performance_point'], 'number'],
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
            'start_value_minutes' => Yii::t('app', 'Start Value Minutes'),
            'end_value_minutes' => Yii::t('app', 'End Value Minutes'),
            'performance_point' => Yii::t('app', 'Performance Point'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function deleteCompletionTime($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('evaluation_config_completion_time', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
