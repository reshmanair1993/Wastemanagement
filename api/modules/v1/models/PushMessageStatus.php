<?php

namespace  api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "building_type".
 *
 * @property int $id
 * @property string $name
 * @property int $fk_image
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class PushMessageStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'push_message_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status','notification_id','notification_status','account_id'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
}
