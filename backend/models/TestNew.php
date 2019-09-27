<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "district".
 *
 * @property int $id
 * @property string $name
 * @property int $fk_state
 * @property string $created_at
 * @property string $modified_at
 */
class TestNew extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_new';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id'], 'required'],
            [['send'], 'integer'],
        ];
    }
    
}
