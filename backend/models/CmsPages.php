<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "cms_pages".
 *
 * @property int $id
 * @property string $name
 * @property string $title_en
 * @property string $title_ml
 * @property string $sub_title_en
 * @property string $sub_title_ml
 * @property int $featured_image_id
 * @property string $description_en
 * @property string $decsription_ml
 * @property string $slug
 * @property string $date
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CmsPages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_pages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'title_en', 'title_ml', 'sub_title_en', 'sub_title_ml', 'description_en', 'description_ml'], 'string'],
            [['featured_image_id', 'status'], 'integer'],
            [['date', 'created_at', 'modified_at'], 'safe'],
            [['slug'], 'string', 'max' => 500],
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
            'title_en' => Yii::t('app', 'Title En'),
            'title_ml' => Yii::t('app', 'Title Ml'),
            'sub_title_en' => Yii::t('app', 'Sub Title En'),
            'sub_title_ml' => Yii::t('app', 'Sub Title Ml'),
            'featured_image_id' => Yii::t('app', 'Featured Image ID'),
            'description_en' => Yii::t('app', 'Description En'),
            'decsription_ml' => Yii::t('app', 'Decsription Ml'),
            'slug' => Yii::t('app', 'Slug'),
            'date' => Yii::t('app', 'Date'),
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
    public function search($params)
    {
      $query = CmsPages::find()->where(['status'=>1])->orderby('id ASC');
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
    }
    public function deletePage($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('cms_pages', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
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
