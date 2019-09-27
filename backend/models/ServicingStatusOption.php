<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "servicing_status_option".
 *
 * @property int $id
 * @property int $service_id
 * @property string $value
 * @property int $image_id
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 */
class ServicingStatusOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicing_status_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'image_id', 'status','ask_waste_quality','ask_waste_quantity'], 'integer'],
            [['value'], 'required'],
            [['created_at', 'modified_at','sort_order','name_ml'], 'safe'],
            [['value'], 'string', 'max' => 255],
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
            'value' => Yii::t('app', 'Value'),
            'image_id' => Yii::t('app', 'Image ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
            'name_ml' => Yii::t('app', 'Malayalam Translation'),
        ];
    }
     public function getProfileUrl()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrl();
    }
    return $logoUrl;
  }
  public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
public function deleteServicingStatusOption($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('servicing_status_option', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    }

