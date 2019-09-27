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
class MemoPenalty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'memo_penalty';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['memo_type_id', 'lsgi_id', 'status'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'modified_At'], 'safe'],
            [['memo_type_id', 'lsgi_id','amount'],'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'memo_type_id' => 'Memo Type',
            'lsgi_id' => 'Lsgi',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_At' => 'Modified  At',
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
      return $query;
    }
    public function getLsgi($id){
      $modelLsgi= Lsgi::find()->where(['id' => $id,'status'=>1])->one();
      if($modelLsgi){
        return $modelLsgi;
      }
    }
    public function getMemoType($id){
      $modelMemoType= MemoType::find()->where(['id' => $id,'status'=>1])->one();
      if($modelMemoType){
        return $modelMemoType->name;
      }
    }
    public function deleteMemoPenalty($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('memo_penalty', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
}
