<?php

namespace api\modules\v1\models;


use Yii;

/**
 * This is the model class for table "ward".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $isgi_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CameraInstallationCheckList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'camera_installation_check_list_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [['status','image_id'],'integer'],
          [['name'],'string','max'=>255],
          [['created_at','modified_at'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app','Association name'),
            'image_id' => Yii::t('app','Image'),
            'status' => Yii::t('app','Status'),
            'created_at' => Yii::t('app','Created At'),
            'modified_at' => Yii::t('app','Modified At'),
        ];
    }
    public static function getAllQuery() {
      return static::find()->where(['status'=>1])->orderby('id ASC');
    }
}
