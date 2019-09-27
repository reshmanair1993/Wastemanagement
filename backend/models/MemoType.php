<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "memo_type".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $title
 * @property string $rule_url
 * @property string $other_legal_actions
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class MemoType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'memo_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name', 'description', 'title', 'rule_url', 'other_legal_actions'], 'string', 'max' => 255],
            [['name', 'description', 'title', 'rule_url'],'required']
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'title' => 'Title',
            'rule_url' => 'Rule Url',
            'other_legal_actions' => 'Other Legal Actions',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
      return $query;
    }
    public function deleteMemoType($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('memo_type', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
}
