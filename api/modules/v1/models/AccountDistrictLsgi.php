<?php

namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "green_action_unit_ward".
 *
 * @property int $id
 * @property int $green_action_unit_id
 * @property int $ward_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class AccountDistrictLsgi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account_district_lsgi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id','district_id','lsgi_id'], 'required'],
            [['created_at', 'modified_at','district_id','lsgi_id'], 'safe'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'account_id' => Yii::t('app', 'Account'),
            'district_id' => Yii::t('app', 'District'),
            'lsgi_id' => Yii::t('app', 'Lsgi'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
}
