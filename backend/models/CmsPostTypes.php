<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "cms_post_types".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CmsPostTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_post_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 500],
        ];
    }

public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ],
            [
             'class' =>SluggableBehavior::className(),
             'attribute'=>'name',
             'immutable'=>true,
             'ensureUnique'=>true
           ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'slug' => Yii::t('app', 'Slug'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status' => 1]);
      return $query;
    }
    public function search($params)
    {
      $query = CmsPostTypes::find()->where(['status'=>1])->orderby('id ASC');
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
    }
    public function deleteType($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('cms_post_types', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
