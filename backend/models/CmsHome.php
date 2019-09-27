<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cms_home".
 *
 * @property int $id
 * @property string $title
 * @property string $sub_title
 * @property int $fk_image_banner
 * @property string $top_box_one_title
 * @property string $top_box_one_sub
 * @property int $fk_image_top_box_one
 * @property string $top_box_two_title
 * @property string $top_box_two_sub
 * @property int $fk_image_top_box_two
 * @property string $top_box_three_title
 * @property string $top_box_three_sub
 * @property int $fk_image_top_box_three
 * @property string $abt_head_one
 * @property string $abt_head_two
 * @property string $abt_head_three
 * @property string $abt_head_four
 * @property int $fk_image_abt
 * @property string $mid_four_title
 * @property string $mid_four_sub_title
 * @property string $mid_four_one_title
 * @property string $mid_four_one_sub_title
 * @property int $fk_image_mid_four_one
 * @property string $mid_four_two_title
 * @property string $mid_four_two_sub_title
 * @property int $fk_image_mid_four_two
 * @property string $mid_four_three_title
 * @property string $mid_four_three_sub_title
 * @property int $fk_image_mid_four_three
 * @property string $mid_four_four_title
 * @property string $mid_four_four_sub_title
 * @property int $fk_image_mid_four_four
 * @property string $video_title
 * @property string $video_sub_title
 * @property string $video_url
 * @property string $circle_menu_one
 * @property int $fk_image_circle_menu_one
 * @property string $circle_menu_two
 * @property int $fk_image_circle_menu_two
 * @property string $circle_menu_three
 * @property int $fk_image_circle_menu_three
 * @property string $circle_menu_four
 * @property int $fk_image_circle_menu_four
 */
class CmsHome extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_home';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'sub_title', 'top_box_one_title', 'top_box_one_sub', 'top_box_two_title', 'top_box_two_sub', 'top_box_three_title', 'top_box_three_sub', 'abt_head_one', 'abt_head_two', 'abt_head_three', 'abt_head_four', 'mid_four_title', 'mid_four_sub_title', 'mid_four_one_title', 'mid_four_one_sub_title', 'mid_four_two_title', 'mid_four_two_sub_title', 'mid_four_three_title', 'mid_four_three_sub_title', 'mid_four_four_title', 'mid_four_four_sub_title', 'video_title', 'video_sub_title', 'video_url', 'circle_menu_one', 'circle_menu_two', 'circle_menu_three', 'circle_menu_four'], 'string'],
            [['sub_title'], 'required'],
            [['fk_image_banner', 'fk_image_top_box_one', 'fk_image_top_box_two', 'fk_image_top_box_three', 'fk_image_abt', 'fk_image_mid_four_one', 'fk_image_mid_four_two', 'fk_image_mid_four_three', 'fk_image_mid_four_four', 'fk_image_circle_menu_one', 'fk_image_circle_menu_two', 'fk_image_circle_menu_three', 'fk_image_circle_menu_four','status'], 'integer'],
            [['created_at','modified_at'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'fk_image_banner' => Yii::t('app', 'Fk Image Banner'),
            'top_box_one_title' => Yii::t('app', 'Top Box One Title'),
            'top_box_one_sub' => Yii::t('app', 'Top Box One Sub'),
            'fk_image_top_box_one' => Yii::t('app', 'Fk Image Top Box One'),
            'top_box_two_title' => Yii::t('app', 'Top Box Two Title'),
            'top_box_two_sub' => Yii::t('app', 'Top Box Two Sub'),
            'fk_image_top_box_two' => Yii::t('app', 'Fk Image Top Box Two'),
            'top_box_three_title' => Yii::t('app', 'Top Box Three Title'),
            'top_box_three_sub' => Yii::t('app', 'Top Box Three Sub'),
            'fk_image_top_box_three' => Yii::t('app', 'Fk Image Top Box Three'),
            'abt_head_one' => Yii::t('app', 'Abt Head One'),
            'abt_head_two' => Yii::t('app', 'Abt Head Two'),
            'abt_head_three' => Yii::t('app', 'Abt Head Three'),
            'abt_head_four' => Yii::t('app', 'Abt Head Four'),
            'fk_image_abt' => Yii::t('app', 'Fk Image Abt'),
            'mid_four_title' => Yii::t('app', 'Mid Four Title'),
            'mid_four_sub_title' => Yii::t('app', 'Mid Four Sub Title'),
            'mid_four_one_title' => Yii::t('app', 'Mid Four One Title'),
            'mid_four_one_sub_title' => Yii::t('app', 'Mid Four One Sub Title'),
            'fk_image_mid_four_one' => Yii::t('app', 'Fk Image Mid Four One'),
            'mid_four_two_title' => Yii::t('app', 'Mid Four Two Title'),
            'mid_four_two_sub_title' => Yii::t('app', 'Mid Four Two Sub Title'),
            'fk_image_mid_four_two' => Yii::t('app', 'Fk Image Mid Four Two'),
            'mid_four_three_title' => Yii::t('app', 'Mid Four Three Title'),
            'mid_four_three_sub_title' => Yii::t('app', 'Mid Four Three Sub Title'),
            'fk_image_mid_four_three' => Yii::t('app', 'Fk Image Mid Four Three'),
            'mid_four_four_title' => Yii::t('app', 'Mid Four Four Title'),
            'mid_four_four_sub_title' => Yii::t('app', 'Mid Four Four Sub Title'),
            'fk_image_mid_four_four' => Yii::t('app', 'Fk Image Mid Four Four'),
            'video_title' => Yii::t('app', 'Video Title'),
            'video_sub_title' => Yii::t('app', 'Video Sub Title'),
            'video_url' => Yii::t('app', 'Video Url'),
            'circle_menu_one' => Yii::t('app', 'Circle Menu One'),
            'fk_image_circle_menu_one' => Yii::t('app', 'Fk Image Circle Menu One'),
            'circle_menu_two' => Yii::t('app', 'Circle Menu Two'),
            'fk_image_circle_menu_two' => Yii::t('app', 'Fk Image Circle Menu Two'),
            'circle_menu_three' => Yii::t('app', 'Circle Menu Three'),
            'fk_image_circle_menu_three' => Yii::t('app', 'Fk Image Circle Menu Three'),
            'circle_menu_four' => Yii::t('app', 'Circle Menu Four'),
            'fk_image_circle_menu_four' => Yii::t('app', 'Fk Image Circle Menu Four'),
        ];
    }
     public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status' => 1]);
      return $query;
    }
    public function search($params)
    {
      $query = CmsHome::find()->where(['status'=>1])->orderby('id ASC');
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
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
    // print_r($this->fk_image_banner);die();
    return $this->hasOne(Image::className(), ['id' => 'fk_image_banner']);
  }
  public function getProfileUrlTopBox1()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage1;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImage1()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_top_box_one']);
  }
  public function getProfileUrlTopBox2()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage2;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImage2()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_top_box_two']);
  }
  public function getProfileUrlTopBox3()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage3;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImage3()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_top_box_three']);
  }
  public function getProfileUrlAbout()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageAbout;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageAbout()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_abt']);
  } 
  public function getProfileUrlMid4()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageMid4;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageMid4()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_mid_four_one']);
  }
  public function getProfileUrlMidFour2()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageMid2;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageMid2()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_mid_four_two']);
  }
  public function getProfileUrlMidFour3()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageMid3;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageMid3()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_mid_four_three']);
  }
  public function getProfileUrlMidFour4()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageMidImage;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageMidImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_mid_four_four']);
  }
  public function getProfileUrlCircleMenu1()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageCircleMenu1;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageCircleMenu1()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_circle_menu_one']);
  }
   public function getProfileUrlCircleMenu2()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageCircleMenu2;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageCircleMenu2()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_circle_menu_two']);
  }
  public function getProfileUrlCircleMenu3()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageCircleMenu3;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageCircleMenu3()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_circle_menu_three']);
  }
   public function getProfileUrlCircleMenu4()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImageCircleMenu4;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlLogoHome();
    }
    return $logoUrl;
  }
  public function getFkImageCircleMenu4()
  {
    return $this->hasOne(Image::className(), ['id' => 'fk_image_circle_menu_four']);
  }
  

}
