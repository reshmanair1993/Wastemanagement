<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "lsgi".
 *
 * @property int $id
 * @property string $name
 * @property int $block_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Lsgi extends \yii\db\ActiveRecord
{
    public $district_id,$assembly_constituency_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lsgi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'block_id','code','lsgi_type_id'], 'required'],
            [['block_id', 'status','camera_fault_calculation_interval_hours'], 'integer'],
            // [['default_complaint_rate', 'default_service_rate'], 'number'],
            [['created_at', 'modified_at','sort_order','image_id','default_complaint_rate', 'default_service_rate','is_camera_surveillance_required','is_wastemanagement_required','service_assigment_expiry_hours','rating_calculation_interval_hours','last_complaint_count_points_calculated_at','last_service_completion_calculated_at','complaints_count_rating_calculation_interval_hours','default_service_point','last_complaint_resolution_calculated_at','header_image_id','footer_image_id','default_slab_rate','subscription_fee_collection_date','gst_no','cgst_percentage','sgst_percentage'], 'safe'],
            [['name'], 'string', 'max' => 250],
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
            'name' => Yii::t('app', 'Name'),
            'block_id' => Yii::t('app', 'Block'),
            'lsgi_type_id' => Yii::t('app', 'Lsgi Type'),
            'code' => Yii::t('app','code'),
            'fkBlock.name' => Yii::t('app', 'Block'),
            'fkLsgiType.name' => Yii::t('app', 'Lsgi Type'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function deleteLsgi($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('lsgi', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkBlock()
    {
        return $this->hasOne(LsgiBlock::className(), ['id' => 'block_id']);
    }
    public function getFkLsgiType()
    {
        return $this->hasOne(LsgiType::className(), ['id' => 'lsgi_type_id']);
    }
     public function getBlocks()
    {
        $isgi =  LsgiBlock::find()->where(['status'=> 1])->all();
        return $isgi;
    }
    public function getTypes()
    {
        $types =  LsgiType::find()->where(['status'=> 1])->all();
        return $types;
    }
    public function getDistricts()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
    }
    public static function getAllQuery()
    {
        return static::find()->where(['status'=> 1]);
    }
    public function getBlock($id)
    {
        $name  = null;
        $block =  LsgiBlock::find()->where(['id'=> $id])->one();
        if($block){
            $name = $block->name;
        }
      return $name;
    }
    public function getConstituency($id)
    {
        $name = null;
        $block =  LsgiBlock::find()->where(['id'=> $id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            $name = $assembly_constituency->name;
        }

        return $name;
    }
     public function getDistrict($id)
    {
        $name = null;
        $block =  LsgiBlock::find()->where(['id'=> $id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            if($assembly_constituency)
            {
                $district = District::find()->where(['id'=> $assembly_constituency->district_id])->one();
                $name = $district->id;
            }

        }
        return $name;
    }
     public function getProfileUrl()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogo();
    }
    return $logoUrl;
  }
  public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
}
