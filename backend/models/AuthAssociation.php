<?php

namespace backend\models;
use yii\behaviors\TimestampBehavior;

use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "auth_association".
 *
 * @property int $id
 * @property int $user_id
 * @property int $lsgi_id
 * @property int $ward_id
 * @property int $hks_id
 * @property int $gt_id
 * @property int $survey_agency_id
 * @property int $district_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class AuthAssociation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_association';
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
            [['user_id', 'lsgi_id', 'hks_id', 'gt_id', 'survey_agency_id', 'district_id', 'status','residential_association_id'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['ward_id'],'number']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'lsgi_id' => Yii::t('app', 'Lsgi ID'),
            'ward_id' => Yii::t('app', 'Ward ID'),
            'hks_id' => Yii::t('app', 'Hks ID'),
            'gt_id' => Yii::t('app', 'Gt ID'),
            'survey_agency_id' => Yii::t('app', 'Survey Agency ID'),
            'district_id' => Yii::t('app', 'District ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteUser($acc_id){
      $connection = Yii::$app->db;
      $modelAccount = Account::find()->where(['status'=>1,'id'=>$acc_id])->one();
      $modelPerson = Person::find()->where(['status'=>1,'id'=>$modelAccount->person_id])->one();
      $id = $modelPerson->id;
      $connection->createCommand()->update('person', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
      $connection->createCommand()->update('account', ['status' => 0], 'id=:id')->bindParam(':id',$acc_id)->execute();
      $connection->createCommand()->update('auth_association', ['status' => 0], 'user_id=:id')->bindParam(':id',$acc_id)->execute();
       return true;
    }
}
