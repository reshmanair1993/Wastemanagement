<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "auth_controllers".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class AuthControllers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
     public $controller_id;
     public function scenarios() {
       return [
         self::SCENARIO_DEFAULT => [
           'status','created_at', 'modified_at','controller_id',
           'name'
         ],
         'create-role' => [
           'controller_id'
         ],
       ];
     }
    public static function tableName()
    {
        return 'auth_controllers';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['created_at', 'modified_at','controller_id'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'],'if_name_exists'],
            [['name'],'required'],
            [['controller_id'],'required','on'=>'create-role'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Controller name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function if_name_exists($attribute){
      $name = $this->name;
      $modelAuthControllers = static::find()->where(['status'=>1])->all();
      foreach ($modelAuthControllers as $modelAuthController) {
        $authName = $modelAuthController->name;
        if($authName == $name){
          $this->addError('name','Controller name already exists');
        }
      }
    }
    public function search($params)
    {
      $query = AuthControllers::find()->where(['status'=>1])->orderby('id ASC');
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
    }
    public function getActionName(){
      $authControllerId = $this->id;
      $modelAuthAction = AuthAction::find()->where(['status'=>1,'auth_controllers_id'=>$authControllerId])->one();
      return $modelAuthAction;
    }
    public function deleteAuthControllers($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('auth_controllers', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
