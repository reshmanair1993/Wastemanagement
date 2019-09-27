<?php

namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
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
class CustomerSelfService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_self_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id','service_id','qty','mrc_id'], 'required'],
            [['created_at', 'modified_at','qty','lat','lng'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function behaviors()
    {
        return [
            [

                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value'              => new Expression('NOW()')
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ]
        ];
    }
}
