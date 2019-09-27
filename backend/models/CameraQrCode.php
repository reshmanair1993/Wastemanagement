<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "qr_code".
 *
 * @property int $id
 * @property string $value
 * @property int $account_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CameraQrCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'camera_qr_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'account_id'], 'required'],
            [['account_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
            'account_id' => Yii::t('app', 'Account ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
}
