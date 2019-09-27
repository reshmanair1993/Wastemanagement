<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "monitoring_group_user".
 *
 * @property int $id
 * @property int $monitoring_group_id
 * @property int $account_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class MonitoringGroupUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitoring_group_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['monitoring_group_id', 'account_id', 'status'], 'integer'],
            [['account_id','monitoring_group_id'],'checkUniqueness'],
            // [['monitoring_group_id','account_id'], 'unique', 'targetAttribute' => ['monitoring_group_id','account_id']],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }
    public function checkUniqueness($attribute,$params)
    {
            $model = MonitoringGroupUser::find()->where(['account_id' => $this->account_id,'monitoring_group_id'=> $this->monitoring_group_id,'status' => 1])->all();
            if($model != null)
                $this->addError('account_id','This account already exist');
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'monitoring_group_id' => Yii::t('app', 'Monitoring Group ID'),
            'account_id' => Yii::t('app', 'Account ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getMonitoringGroupUsers($id)
    {
      $modelUsers = Account::find()->where(['id' => $id ,'status' => 1])->all();
      foreach ($modelUsers as $modelUser) {
        // $model = Account::find()->where(['id' => $modelUser->account_id,'status' => 1])->one();
        // if($model)
          return $modelUser->username;
      }
    }
    public function deleteMonitoringGroupUser($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('monitoring_group_user', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
}
