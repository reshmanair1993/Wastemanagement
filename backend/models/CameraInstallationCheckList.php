<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "residential_association".
 *
 * @property int $id
 * @property string $name
 * @property double $penalty
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CameraInstallationCheckList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camera_installation_check_list_item';
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
    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [[
              'status','image_id'
            ], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [[
              'name'
            ], 'string', 'max' => 255],
            [['name'],'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'image_id'=> 'Image',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    public static function getAllQuery()
    {
      $query = static::find()->where(['status' => 1]);
      return $query;
    }
    public function search($params)
    {
      $query = CameraInstallationCheckList::find()->where(['status'=>1])->orderby('id ASC');
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
    }
    public function deleteCameraInstallation($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('camera_installation_check_list_item', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status' => 1]);
    }
    public function getImage($imgId){
      $ret = null;
      $modelImage = $this->fkImage;
      if($modelImage){
        $ret = $modelImage->uri_full;
      }
      $path = "http://139.162.54.79/wastemanagement/common/uploads/customer-photos/";
      $image = Image::getFullUrl($ret,$path,$whole_path=true);
      return $image;
    }

}
