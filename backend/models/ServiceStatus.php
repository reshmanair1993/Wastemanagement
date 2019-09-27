<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "service_status".
 *
 * @property int $id
 * @property int $service_id
 * @property int $account_id
 * @property string $remark
 * @property int $remark_status 1.Completed 2. Not Completed 3. Deligated
 * @property string $created_at
 * @property string $modified_at
 */
class ServiceStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'account_id'], 'required'],
            [['service_id', 'account_id', 'remark_status'], 'integer'],
            [['remark'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }
    public function behaviors() {
    return [
      [
        'class' => TimestampBehavior::className(),
        'createdAtAttribute' => 'created_at',
        'updatedAtAttribute' => 'modified_at',
        'value' => new Expression('NOW()')
      ]
    ];

 }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'service_id' => Yii::t('app', 'Service ID'),
            'account_id' => Yii::t('app', 'Account ID'),
            'remark' => Yii::t('app', 'Remark'),
            'remark_status' => Yii::t('app', 'Remark Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
}
