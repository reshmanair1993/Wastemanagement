<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "gender".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class FirebaseToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'firebase_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status','account_id'], 'integer'],
            [['created_at', 'modified_at','token'], 'safe'],
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
