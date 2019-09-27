<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "evaluation_config_complaints_count".
 *
 * @property int $id
 * @property int $lsgi_id
 * @property int $start_value_count
 * @property int $end_value_count
 * @property double $performance_point
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class EvaluationConfigComplaintsCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation_config_complaints_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lsgi_id','start_value_count','end_value_count'], 'required'],
            [['lsgi_id', 'start_value_count', 'end_value_count', 'status'], 'integer'],
            [['performance_point'], 'number'],
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
            'start_value_count' => Yii::t('app', 'Start Value Count'),
            'end_value_count' => Yii::t('app', 'End Value Count'),
            'performance_point' => Yii::t('app', 'Performance Point'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteComplaintsCount($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('evaluation_config_complaints_count', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
