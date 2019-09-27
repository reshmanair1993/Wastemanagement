<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cms_social_media".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $icon
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CmsSocialMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_social_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['icon', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name', 'url'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'url' => Yii::t('app', 'Url'),
            'icon' => Yii::t('app', 'Icon'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status' => 1]);
      return $query;
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
    return $this->hasOne(Image::className(), ['id' => 'icon']);
  }
  public function deleteSocialMedia($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('cms_social_media', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
