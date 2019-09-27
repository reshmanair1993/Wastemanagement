<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ServiceAssignment extends \yii\db\ActiveRecord
{
        public $district_id, $block_id, $assembly_constituency_id, $lsgi_id,$ward_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'modified_at','service_request_id','account_id_gt','remarks','servicing_datetime','servicing_status_option_id','quantity','quality','door_status','lat_update_from','lng_updated_from','ward_id','planned_date','expiry_ts'], 'safe'],
            [['servicing_status_option_id','lat_update_from','lng_updated_from'], 'required','on'=>'add-status'],
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
// public function scenarios() {
//             return [
//           'add-status' => ['servicing_status_option_id','lat_update_from','lng_updated_from','remarks','quantity','quality'],
//             ];
//     }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function getDistrict()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
    }
    public function getFkServiceRequest()
    {
        return $this->hasOne(ServiceRequest::className(), ['id' => 'service_request_id'])->andWhere(['status'=>1]);
    }
     public static function getAllQuery()
    {
      return static::find()->where(['service_assignment.status'=>1])->orderBy(['id' => SORT_DESC]);
    }
    public function getFkServiceStatus()
    {
        return $this->hasOne(ServicingStatusOption::className(), ['id' => 'servicing_status_option_id'])->andWhere(['status'=>1]);
    }
    public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_gt'])->andWhere(['status'=>1]);
    }
    public function getFkQuality()
    {
        return $this->hasOne(WasteQuality::className(), ['id' => 'quality'])->andWhere(['status'=>1]);
    }
}
