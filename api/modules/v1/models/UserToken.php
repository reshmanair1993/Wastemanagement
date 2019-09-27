<?php

namespace api\modules\v1\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "tb_access_token".
 *
 * @property integer $id
 * @property integer $fk_access_token_user
 * @property string access_token
 * @property string $created_at
 * @property string $modified_at
 * @property string $expiry
 * @property integer $status
 */


class UserToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_token';
    }
    public function rules()
    {
        return [
            [['account_id', 'api_token','user_token'], 'required'],
            [['created_at', 'modified_at'], 'safe'],
            [['status'], 'integer'],
        ];
    }
		public function behaviors()
		{
			 return [
					 [

					 'class' => TimestampBehavior::className(),
					 'createdAtAttribute' => 'created_at',
					 'updatedAtAttribute' => 'modified_at',
					 'value' => new Expression('NOW()'),
							 // if you're using datetime instead of UNIX timestamp:
							 // 'value' => new Expression('NOW()'),
					 ],
			 ];
		}
    public function getFkTokenUser() {
      return $this->hasOne(Account::className(),['id'=>'account_id'])->andWhere(['status'=>1]);
    }
		
}
/* $connection = Yii::$app->db;
$connection->createCommand()->update('user', ['sha_password' => $password], 'id='.$id)->execute(); */
