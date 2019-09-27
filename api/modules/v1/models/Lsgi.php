<?php

namespace api\modules\v1\models;

use Yii;

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
            [['name','code', 'block_id'], 'required'],
            [['block_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 250],
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
            'code' => Yii::t('app','code'),
            'fkBlock.name' => Yii::t('app', 'Block'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery() {
      return static::find(['status'=>1])->orderby('lsgi.sort_order ASC');
    }
     public function deleteLsgi($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('lsgi', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkLsgiBlock()
    {
        return $this->hasOne(LsgiBlock::className(), ['id' => 'block_id',])->andWhere(['status'=>1]);
    }
    public function getFkImageHeader()
    {
        return $this->hasOne(Image::className(), ['id' => 'header_image_id',])->andWhere(['status'=>1]);
    }
    public function getFkImageFooter()
    {
        return $this->hasOne(Image::className(), ['id' => 'footer_image_id',])->andWhere(['status'=>1]);
    }
     public function getBlocks()
    {
        $isgi =  LsgiBlock::find()->where(['status'=> 1])->all();
        return $isgi;
    }
    public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
}
