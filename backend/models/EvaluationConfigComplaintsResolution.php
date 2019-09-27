<?php

namespace backend\models;

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
class EvaluationConfigComplaintsResolution extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation_config_complaints_resolution';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_value_percentage','end_value_percentage'], 'required'],
            [['lsgi_id', 'start_value_percentage', 'end_value_percentage', 'status'], 'integer'],
            [['performance_point'], 'number'],
            [['end_value_percentage'],'new_and_unique'],
            [['created_at', 'modified_at','hours'], 'safe'],
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
            'start_value_percentage' => Yii::t('app', 'Start Value Percentage'),
            'end_value_percentage' => Yii::t('app', 'End Value Percentage'),
            'performance_point' => Yii::t('app', 'Performance Point'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteComplaintsResolution($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('evaluation_config_complaints_resolution', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function new_and_unique($attribute,$params)
    {
        $err  = EvaluationConfigComplaintsResolution::find()->where(['>=','end_value_percentage',$this->start_value_percentage])->andWhere(['<=','start_value_percentage',$this->start_value_percentage])->andWhere(['status'=>1])->one();
        if($err)
        $this->addError($attribute,'Range is already taken ');
    }
}
