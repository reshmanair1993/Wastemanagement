<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "memo_penalty".
 *
 * @property int $id
 * @property int $memo_type_id
 * @property int $lsgi_id
 * @property double $amount
 * @property int $status
 * @property string $created_at
 * @property string $modified_At
 */
class LoginHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id'], 'integer'],
            [['login_datetime'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->orderBy(['id' => SORT_DESC]);
      return $query;
    }
}
