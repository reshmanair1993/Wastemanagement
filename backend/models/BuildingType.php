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
class BuildingType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'building_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['fk_image', 'status','residence_category_id'], 'integer'],
            [['created_at', 'modified_at','sort_order'], 'safe'],
            [['name'], 'string', 'max' => 250],
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
            'name' => Yii::t('app', 'Name'),
            'fk_image' => Yii::t('app', 'Fk Image'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteType($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('building_type', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getType()
    {
        // $types =  BuildingType::find()->where(['status'=> 1])->all();
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
       $association = null;
       $unit = null;
        if(isset($associations['residential_association_id']))
        {
            $association = $associations['residential_association_id'];
        }
        $types =  BuildingType::find()->where(['building_type.status'=> 1]);
        // if($association)
        // {
        //     $types->andWhere(['residence_category_id'=>$association]);
        // }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if($unit)
        {
           $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['green_action_unit.status'=>1])->one();
            if($modelUnit)
            {
                $category = $modelUnit->residence_category_id;
            }
            else
            {
                $category = null;
            }
            if($category==3)
            {
                $types->andWhere(['residence_category_id'=>$category]);
            }
        }
        $types = $types->all();
        return $types;
    }
     public function getCategory()
    {
        $category =  ResidenceCategory::find()->where(['status'=> 1])->all();
        return $category;
    }
    public function getFkCategory()
    {
        return $this->hasOne(ResidenceCategory::className(), ['id' => 'residence_category_id']);
    }
     public function getTypeNonResidential()
    {
        $types =  BuildingType::find()->where(['status'=> 1])->andWhere(['!=','residence_category_id',1])->all();
        return $types;
    }
}
