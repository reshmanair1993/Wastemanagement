<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "firebase_token".
 *
 * @property int $id
 * @property string $token
 * @property int $account_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class FirebaseToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firebase_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['token'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'account_id' => 'Account ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }
}
