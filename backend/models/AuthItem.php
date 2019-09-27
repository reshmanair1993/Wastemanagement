<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function deleteAuthItem($name){
      $connection = Yii::$app->db;
      $connection->createCommand()->delete('auth_item', ['name' => $name])->execute();
      return true;
    }
    public function deleteAuthControllerItem($name){
      $connection = Yii::$app->db;
      $connection->createCommand()->delete('auth_item', ['like','name',$name])->execute();
      return true;
    }
    public function getPermissions($roleName){
      $query = AuthItemChild::find()->where(['parent'=>$roleName])->all();
      $permissionName = [];
      foreach ($query as $qry) {
        $permissionName[] = $qry->child;
      }
      return $permissionName;
    }
}
