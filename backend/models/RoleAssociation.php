<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "role_association".
 *
 * @property int $id
 * @property string $role
 * @property int $has_lsgi_association
 * @property int $has_ward_association
 * @property int $has_hks_association
 * @property int $has_gt_association
 * @property int $has_survey_agency_association
 * @property int $district_association
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class RoleAssociation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_association';
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
            [['has_lsgi_association', 'has_ward_association', 'has_hks_association', 'has_gt_association', 'has_survey_agency_association', 'district_association', 'status','has_supervisor_association','has_residential_association'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['role'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'role' => Yii::t('app', 'Role'),
            'has_lsgi_association' => Yii::t('app', 'Association with LSGI'),
            'has_ward_association' => Yii::t('app', 'Association with ward'),
            'has_hks_association' => Yii::t('app', 'Association with HKS'),
            'has_gt_association' => Yii::t('app', 'Association with GT'),
            'has_survey_agency_association' => Yii::t('app', 'Associate with a survey agency'),
            'has_supervisor_association' => Yii::t('app', 'Associate with a supervisor'),
            'has_residential_association' => Yii::t('app', 'Associate with residence association'),
            'district_association' => Yii::t('app', 'Associate with a district'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
}
