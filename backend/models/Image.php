<?php

// namespace frontend\models;
//
// use Yii;
// use yii\helpers\Url;
// use yii\web\UploadedFile;

namespace backend\models;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $title
 * @property string $url_small
 * @property string $url_medium
 * @property string $url_large
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 *
 * @property Person[] $people
 * @property PersonImage[] $personImages
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
   public $uploaded_files;
   public $uploaded_files1;
   public $uploaded_files2;
   public $uploaded_files3;
   public $uploaded_files4;
   public $uploaded_files5;
   public $uploaded_files6;
   public $uploaded_files7;
   public $uploaded_files8;
   public $uploaded_files9;
   public $uploaded_files10;
   public $uploaded_files11;
   public $uploaded_files12;
   public $uploaded_files13;
   public $attributeLabels = [];
   public $imageFiles;
   // public $file;

   public function __construct($attributeLabels = []) {
     $this->attributeLabels = $attributeLabels;
   }

    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
     public function rules()
    {
      // echo "string";
      // exit;
        return [
            // [['title'], 'required'],
            // [['title', 'status'], 'integer'],
            [['uploaded_files','uploaded_files1','uploaded_files2','uploaded_files3','uploaded_files4','uploaded_files5','uploaded_files6','uploaded_files7','uploaded_files8','uploaded_files9','uploaded_files10','uploaded_files11','uploaded_files12','uploaded_files13'],'image', 'skipOnEmpty' => true, 'extensions'=>['png', 'jpg'],'on'=>['single-image-upload','default']],
            [['uploaded_files'],'image', 'skipOnEmpty' => false, 'extensions'=>['png', 'jpg'],'maxFiles'=>5,'on'=>'multiple-image-upload'],
            [['uploaded_files'],'image', 'skipOnEmpty' => true, 'extensions'=>['png', 'jpg'],'on'=>['single-image-upload-image-optional']],
            [['uploaded_files'],'image', 'skipOnEmpty' => true, 'extensions'=>['png', 'jpg'],'maxFiles'=>6,'on'=>'multiple-image-upload-image-optional'],

            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['title', 'uri_thumb', 'uri_medium', 'uri_full'], 'string', 'max' => 255],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 4],
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
    //
    // /**
    //  * {@inheritdoc}
    //  */
    //
    // public function rules()
    // {
    //     return [
    //         [['title', 'status'], 'integer'],
    //         [['uploaded_files'],'image', 'skipOnEmpty' => false, 'extensions'=>['png', 'jpg'],'on'=>['single-image-upload','default']],
    //         [['uploaded_files'],'image', 'skipOnEmpty' => false, 'extensions'=>['png', 'jpg'],'maxFiles'=>5,'on'=>'multiple-image-upload'],
    //         [['uploaded_files'],'image', 'skipOnEmpty' => true, 'extensions'=>['png', 'jpg'],'on'=>['single-image-upload-image-optional']],
    //         [['uploaded_files'],'image', 'skipOnEmpty' => true, 'extensions'=>['png', 'jpg'],'maxFiles'=>6,'on'=>'multiple-image-upload-image-optional'],
    //
    //         [['created_at', 'modified_at'], 'safe'],
    //         [['uri_full', 'uri_medium', 'uri_thumb'], 'string', 'max' => 255],
    //     ];
    // }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'uri_thumb' => 'Uri Small',
            'uri_medium' => 'Uri Medium',
            'uri_full' => 'Uri Large',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];

        foreach($labels as $key => &$val) {
          if(isset($this->attributeLabels[$key])) {
            $val = $this->attributeLabels[$key];
          }
        }
        return $labels;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['fk_image' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonImages()
    {
        return $this->hasMany(PersonImage::className(), ['fk_image' => 'id']);
    }

    public function uploadAndSave($images=null,$uploads_path_image=null)
    {
       $retId = false;
       if(!$images)
  	     $images = UploadedFile::getInstances($this,'uploaded_files');
       if(!is_array($images))
       {
         $images = [$images];
         $retId = true;
       }
       $ret= [];
       if($uploads_path_image){
         $uploads_path = $uploads_path_image;
       }
       else{
       $uploads_path = Yii::$app->params['profile_uploads_path'];
       }
       // print_r($uploads_path);exit;
       // $uploads_path = Yii::$app->params['profile_uploads_path'];
       foreach($images as $image) {
        // print_r($image);die();
          $this->renameImage($image);
          $image_path = $uploads_path.$image->name;
          $image_full_path = Yii::getAlias( $image_path);
          $image->saveAs($image_full_path);
          $this->uri_full = $image->name ;
          $newRec = $this->insertNew(false);
          if($newRec->id) {
             $ret[] = $newRec->id;
          }
       }
       // print_r($ret);die();
       $singleId = isset($ret[0])?$ret[0]:null;
       // $ret = !$retId?$ret:$singleId;
      $ret = $singleId;
       return $ret;
    }
    public function uploadAndSaveMultiple($images=null,$uploads_path_image=null)
    {
       $retId = false;
       if(!$images)
         $images = UploadedFile::getInstances($this,'uploaded_files');
       if(!is_array($images))
       {
         $images = [$images];
         $retId = true;
       }
       $ret= [];
       if($uploads_path_image){
         $uploads_path = $uploads_path_image;
       }
       else{
       $uploads_path = Yii::$app->params['profile_uploads_path'];
       }
        // print_r($images);exit;
       // $uploads_path = Yii::$app->params['profile_uploads_path'];
       foreach($images as $image) {
          $this->renameImage($image);
          $image_path = $uploads_path.$image->name;
          $image_full_path = Yii::getAlias( $image_path);
          $image->saveAs($image_full_path);
          $this->uri_full = $image->name ;
          // if($this->insertNew(false))
          // {
           $newRec = $this->insertNew(false);
           if($newRec->id){
            $ret[] = $newRec->id;
          }
       }
       return $ret;
    }
    public  function insertNew()
    {
        $model = Yii::$app->utilities->cloneModel(Image::className(),$this);
        $model->status = 1;
        $model->save(false);
        $id = $model->id;
        $ret = $id?$model:null;
        return $ret;
    }
    public static function renameImage($image)
    {
        $name_tmp = $image->name;
        $name_tmp = explode('.',$name_tmp );
        $ext = $name_tmp[sizeof($name_tmp)-1];
        array_pop($name_tmp);
        $unique_num  = sha1(time());
        $name_tmp[] = $unique_num;
        $name_tmp = implode('-',$name_tmp).'.'.$ext;
        $image->name = $name_tmp;
        return $image;
    }
    public static function getFullUrl($img_url=null,$signature_path=null,$whole_path=true)
    {
        $path = null;
        $img_url = $img_url?$img_url:'';
        if($img_url) {
          if($signature_path)
          $path =  $signature_path.$img_url;
          else{
          $path =  Yii::$app->params['incident_image_base_url'].$img_url;
          if($whole_path) {
            $path = Url::to($path, 'http');
          }
        }
        }
        return $path;
    }
    public  function fullUrl($whole_path=false) {
     $path = null;
     $img_url = $this->uri_full;
     $img_url = $img_url?$img_url:'';
     if($img_url) {
              $path =  Yii::$app->params['base_url'].$img_url;
         if($whole_path) {
           $path = Url::to($path, 'http');
         }
       }
       return $path;
     }
      public  function fullUrlEvents($whole_path=false) {
     $path = null;
     $img_url = $this->uri_full;
     $img_url = $img_url?$img_url:'';
     if($img_url) {
              $path =  Yii::$app->params['dumping_base_url'].$img_url;
         if($whole_path) {
           $path = Url::to($path, 'http');
         }
       }
       return $path;
     }
      public  function fullUrlMrc($whole_path=false) {
     $path = null;
     $img_url = $this->uri_full;
     $img_url = $img_url?$img_url:'';
     if($img_url) {
              $path =  Yii::$app->params['mrc_base_url'].$img_url;
         if($whole_path) {
           $path = Url::to($path, 'http');
         }
       }
       return $path;
     }
     public  function fullUrlLogo($whole_path=false) {
     $path = null;
     $img_url = $this->uri_full;
     $img_url = $img_url?$img_url:'';
     if($img_url) {
              $path =  Yii::$app->params['image_base'].$img_url;
         if($whole_path) {
           $path = Url::to($path, 'http');
         }
       }
       return $path;
     }
     public  function fullUrlLogoHome($whole_path=false) {
     $path = null;
     $img_url = $this->uri_full;
     $img_url = $img_url?$img_url:'';
     if($img_url) {
              $path =  Yii::$app->params['home_image_base_url'].$img_url;
         if($whole_path) {
           $path = Url::to($path, 'http');
         }
       }
       return $path;
     }
     public  function fullUrlCamera($whole_path=false) {
     $path = null;
     $img_url = $this->uri_full;
     $img_url = $img_url?$img_url:'';
     if($img_url) {
              $path =  Yii::$app->params['camera_image_base_url'].$img_url;
         if($whole_path) {
           $path = Url::to($path, 'http');
         }
       }
       return $path;
     }


}
