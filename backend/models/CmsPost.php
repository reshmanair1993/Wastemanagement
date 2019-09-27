<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "cms_post".
 *
 * @property int $id
 * @property string $name
 * @property string $title_ml
 * @property string $title_en
 * @property string $sub_title_en
 * @property string $sub_title_ml
 * @property int $featured_image_id
 * @property string $description_en
 * @property string $description_ml
 * @property string $slug
 * @property string $date
 * @property int $post_type
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CmsPost extends \yii\db\ActiveRecord
{
  public $location_name,$map_input;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'sub_title_ml', 'post_type'], 'required'],
            [['name', 'title_ml', 'title_en', 'sub_title_en', 'sub_title_ml', 'description_en', 'description_ml', 'slug'], 'string'],
            [['featured_image_id', 'post_type', 'status'], 'integer'],
            [['date', 'created_at', 'modified_at','short_description_ml','short_description_en','lat','lng','location_name'], 'safe'],
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
            ],
            [
             'class' =>SluggableBehavior::className(),
             'attribute'=>'name',
             'immutable'=>true,
             'ensureUnique'=>true
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
            'name' => Yii::t('app', 'Name'),
            'lat' => Yii::t('app', 'Latitude'),
            'lng' => Yii::t('app', 'Longitude'),
            'title_ml' => Yii::t('app', 'Title Malayalam'),
            'title_en' => Yii::t('app', 'Title English'),
            'sub_title_en' => Yii::t('app', 'Sub Title English'),
            'sub_title_ml' => Yii::t('app', 'Sub Title Malayalam'),
            'featured_image_id' => Yii::t('app', 'Featured Image ID'),
            'description_en' => Yii::t('app', 'Description English'),
            'description_ml' => Yii::t('app', 'Description Malayalam'),
            'short_description_en' => Yii::t('app', 'Short Description English'),
            'short_description_ml' => Yii::t('app', 'Short Description Malayalam'),
            'slug' => Yii::t('app', 'Slug'),
            'date' => Yii::t('app', 'Date'),
            'post_type' => Yii::t('app', 'Post Type'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery($postType=null) {
     $query = static::find()->where(['status'=>1])
     ->orderby('id DESC');
     if($postType){
        $query->andWhere(['post_type'=>$postType]);
      }
     return $query;
   }
    public function search($params,$type=null)
    {
      $query = CmsPost::find()->where(['status'=>1])->orderby('id ASC');
       if($type){
        $query->andWhere(['post_type'=>$type]);
      }
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
    }
    public function deletePost($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('cms_post', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkType()
      {
        return $this->hasOne(PostType::className(), ['id' => 'post_type']);
      }
     public function getTypes()
    {
        $type =  CmsPostTypes::find()->where(['status'=> 1])->all();
        return $type;
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
    return $this->hasOne(Image::className(), ['id' => 'featured_image_id']);
  }
}
