<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "lsgi_authorized_signatory".
 *
 * @property int $id
 * @property string $name
 * @property string $position
 * @property int $lsgi_id
 * @property int $image_id_signature
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class LsgiAuthorizedSignatory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lsgi_authorized_signatory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lsgi_id', 'image_id_signature', 'status'], 'integer'],
            [['name', 'position'], 'required'],
            [['created_at', 'modified_at'], 'safe'],
            [['name', 'position'], 'string', 'max' => 255],
            [['lsgi_id'],'check_lsgi_exist']
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
 public function check_lsgi_exist()
 {
      $lsgiAuthorizedSignatories = static::find()->where(['status'=>1])
      ->andWhere(['lsgi_id'=>$this->lsgi_id])->one();
      if($lsgiAuthorizedSignatories){
        $this->addError('lsgi_id','This Lsgi already exist');
      }
 }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'position' => 'Position',
            'lsgi_id' => 'Lsgi',
            'image_id_signature' => 'Image Id Signature',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
      return $query;
    }
    public function deleteLsgiAuthorizedSignatory($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('lsgi_authorized_signatory', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
    public function getLsgi($id){
      $modelLsgi= Lsgi::find()->where(['id' => $id,'status'=>1])->one();
      if($modelLsgi){
        return $modelLsgi;
      }
    }
    public function getSignatureImage($id){
      $modelImage= Image::find()->where(['id' => $id,'status'=>1])->one();
      if($modelImage){
        $url = $modelImage->uri_full;
        $path =  Yii::$app->params['signature_image_base_url'];
        $modelSignatureImage = $modelImage->getFullUrl($url,$path);
        return $modelSignatureImage;
      }
    }
}
