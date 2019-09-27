<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "evaluation_config_waste_quality".
 *
 * @property int $id
 * @property int $lsgi_id
 * @property int $quality_type_id
 * @property double $performance_point
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class EvaluationConfigWasteQuality extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation_config_waste_quality';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lsgi_id','quality_type_id','performance_point'], 'required'],
            [['lsgi_id', 'quality_type_id', 'status'], 'integer'],
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
            'quality_type_id' => Yii::t('app', 'Quality Type'),
            'performance_point' => Yii::t('app', 'Performance Point'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteQuality($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('evaluation_config_waste_quality', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkQuality()
    {
        return $this->hasOne(WasteQuality::className(), ['id' => 'quality_type_id']);
    }
}
