<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "service_status".
 *
 * @property int $id
 * @property int $service_id
 * @property int $account_id
 * @property string $remark
 * @property int $remark_status 1.Completed 2. Not Completed 3. Deligated
 * @property string $created_at
 * @property string $modified_at
 */
class ServicingStatusOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicing_status_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'value'], 'required'],
            [['service_id', 'image_id'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'service_id' => Yii::t('app', 'Service ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public static function getAllQuery()
    {
      return static::find()->where(['servicing_status_option.status'=>1])->orderBy(['sort_order' => SORT_ASC]);
    }
    public function getFkImage()
     {
       return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status'=>1]);
     }
}
