<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "auth_action".
 *
 * @property int $id
 * @property int $auth_controllers_id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class AuthAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_action';
    }

    /**
     * {@inheritdoc}
     */

    public $action_id;
    public function scenarios() {
      return [
        self::SCENARIO_DEFAULT => [
          'auth_controllers_id','status','created_at', 'modified_at','action_id',
          'name'
        ],
        'create-role' => [
          'action_id'
        ],
      ];
    }
    public function rules()
    {
        return [
            [['auth_controllers_id', 'status'], 'integer'],
            [['created_at', 'modified_at','action_id'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'],'required'],
            [['name'],'if_name_exist'],
            [['action_id'],'safe','on'=>'create-role'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'auth_controllers_id' => Yii::t('app', 'Auth Controllers ID'),
            'action_id' => Yii::t('app', 'Action name'),
            'name' => Yii::t('app', 'Action name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function if_name_exist($attribute){
      $authControllerId = $this->auth_controllers_id;
      $modelAuthActions = static::find()
      ->where(['status'=>1,'auth_controllers_id'=>$authControllerId])->all();
      foreach ($modelAuthActions as $modelAuthAction) {
        $controllerId = $modelAuthAction['auth_controllers_id'];
        if($authControllerId == $controllerId){
          if($modelAuthAction->name == $this->name){
            $this->addError('name','Action already exists');
          }
        }
      }
    }
    public function deleteAuthActionController($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('auth_action', ['status' => 0], 'auth_controllers_id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function deleteAuthAction($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('auth_action', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
