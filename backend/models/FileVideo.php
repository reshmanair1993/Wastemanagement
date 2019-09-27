<?php

// namespace frontend\models;
//
// use Yii;
// use yii\helpers\Url;
// use yii\web\UploadedFile;

namespace backend\models;
use yii\web\UploadedFile;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "video".
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
 * @property Personvideo[] $personvideos
 */
class FileVideo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
   public $uploaded_files;
   public $attributeLabels = [];
   // public $file;

   public function __construct($attributeLabels = []) {
     $this->attributeLabels = $attributeLabels;
   }

    public static function tableName()
    {
        return 'file_video';
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
            [['uploaded_files'],'file', 'skipOnEmpty' => false, 'extensions'=>['mp4', 'mpeg','mkv','flv','webm','avi'],'maxSize' => '2048000','on'=>['single-video-upload','default']],
            [['uploaded_files'],'file', 'skipOnEmpty' => false, 'extensions'=>['mp4', 'mpeg','mkv','flv','webm','avi'],'maxFiles'=>5,'on'=>'multiple-video-upload'],
            [['uploaded_files'],'file', 'skipOnEmpty' => true, 'extensions'=>['mp4', 'mpeg','mkv','flv','webm','avi'],'on'=>['single-video-upload-video-optional']],
            [['uploaded_files'],'file', 'skipOnEmpty' => true, 'extensions'=>['mp4', 'mpeg','mkv','flv','webm','avi'],'maxFiles'=>6,'on'=>'multiple-video-upload-video-optional'],

            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['title', 'url'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
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

    public function uploadAndSave($videos=null)
    {
       $retId = false;
       if(!$videos)
  	     $videos = UploadedFile::getInstances($this,'uploaded_files');
       if(!is_array($videos))
       {
         $videos = [$videos];
         $retId = true;
       }
       $ret= [];
       $uploads_path = Yii::$app->params['video_uploads_path'];
       foreach($videos as $video) {
          $this->renamevideo($video);
          $video_path = $uploads_path.$video->name;
          $video_full_path = Yii::getAlias( $video_path);
          $video->saveAs($video_full_path);
          $this->url = $video->name ;
          $newRec = $this->insertNew(false);
          if($newRec->id) {
              $ret[] = $newRec->id;
          }
       }
       $singleId = isset($ret[0])?$ret[0]:null;
       $ret = !$retId?$ret:$singleId;
       return $ret;
    }
    public  function insertNew()
    {
        $model = Yii::$app->utilities->cloneModel(FileVideo::className(),$this);
        $model->status = 1;
        $model->save(false);
        $id = $model->id;
        $ret = $id?$model:null;
        return $ret;
    }
    public static function renameVideo($video)
    {
        $name_tmp = $video->name;
        $name_tmp = explode('.',$name_tmp );
        $ext = $name_tmp[sizeof($name_tmp)-1];
        array_pop($name_tmp);
        $unique_num  = sha1(time());
        $name_tmp[] = $unique_num;
        $name_tmp = implode('-',$name_tmp).'.'.$ext;
        $video->name = $name_tmp;
        return $video;
    }
    public static function getFullUrl($img_url=null,$whole_path=true)
    {
        $path = null;
        $img_url = $img_url?$img_url:'';
        if($img_url) {
          $path =  Yii::$app->params['incident_video_base_url'].$img_url;
          if($whole_path) {
            $path = Url::to($path, 'http');
          }
        }
        return $path;
    }

}
