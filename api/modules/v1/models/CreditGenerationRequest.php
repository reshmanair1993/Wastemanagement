<?php

namespace api\modules\v1\models;

use Yii;

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
class CreditGenerationRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit_generation_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'amount'], 'required'],
            [['balance', 'amount'], 'number'],
            [['service_id', 'is_approved','payment_status'], 'integer'],
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
            'account_id' => Yii::t('app', 'Customer'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    //  public static function getAllQuery()
    // {
    //   return static::find()->where(['camera_servicing_status_option.status'=>1])->orderBy(['id' => SORT_DESC]);
    // }
    // public function getFkImage()
    //  {
    //    return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status'=>1]);
    //  }
}
