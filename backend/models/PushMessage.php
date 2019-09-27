<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "building_type".
 *
 * @property int $id
 * @property string $name
 * @property int $fk_image
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class PushMessage extends \yii\db\ActiveRecord
{
    public $type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'push_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'required'],
            [['status','lsgi_id','ward_id','hks_id'], 'integer'],
            [['created_at', 'modified_at','type','account_id'], 'safe'],
            [['message','message_ml'], 'string'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'Message English'),
            'message_ml' => Yii::t('app', 'Message Malayalam'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getLsgi()
     {

        $lsgi = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        $lsgis =  Lsgi::find()->where(['status'=> 1]);
        if($lsgi)
        {
          $lsgis = $lsgis->andWhere(['id'=>$lsgi]);  
        }
        $lsgis = $lsgis->all();
        return $lsgis;
     }
     public function getHks()
     {

        $ward = null;
        $unit = null;
        $lsgi = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        $greenActionUnit =  GreenActionUnit::find()->where(['status'=> 1]);
        if($unit)
        {
          $greenActionUnit = $greenActionUnit->andWhere(['id'=>$unit]);  
        }
        if($lsgi)
        {
            $greenActionUnit = $greenActionUnit->andWhere(['lsgi_id'=>$lsgi]);
        }
        if($ward)
        {
            $greenActionUnit = $greenActionUnit->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            ->andWhere(['green_action_unit_ward.ward_id'=>$ward]);
        }
        $greenActionUnit = $greenActionUnit->all();
        return $greenActionUnit;
     }
     public function getWards()
    {
        $ward = null;
        $unit = null;
        $lsgi = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }

        $wardsList =  Ward::find()->where(['ward.status'=> 1]);
        if($unit)
        {
            $wardsList = $wardsList->leftJoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
            ->andWhere(['green_action_unit_ward.status'=>1])
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
           $wardsList =  $wardsList->all();
           return $wardsList;
        }
        public function getAllQuery()
    {
        $query = PushMessage::find()->where(['status' => 1])->orderBy(['id' => SORT_DESC]);

        return $query;
    }
    public function getFkWard()
    {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
    }
    public function getFkLsgi()
    {
        return $this->hasOne(Ward::className(), ['id' => 'lsgi_id']);
    }
    public function getFkHks()
    {
        return $this->hasOne(GreenActionUnit::className(), ['id' => 'hks_id']);
    }
}
