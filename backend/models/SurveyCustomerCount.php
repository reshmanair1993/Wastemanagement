<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "residential_association".
 *
 * @property int $id
 * @property string $name
 * @property double $penalty
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class SurveyCustomerCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_customer_count';
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
            [[
              'status','account_id','count'
            ], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }
  
}
