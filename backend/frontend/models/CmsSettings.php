<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cms_settings".
 *
 * @property int $id
 * @property string $address_en
 * @property string $address_ml
 * @property string $email
 * @property string $contact_number
 * @property double $lat
 * @property double $lng
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CmsSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address_en', 'address_ml'], 'string'],
            [['contact_number', 'lng'], 'required'],
            [['lat', 'lng'], 'number'],
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['email', 'contact_number'], 'string', 'max' => 500],
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
            'address_en' => Yii::t('app', 'Address English'),
            'address_ml' => Yii::t('app', 'Address Malayalam'),
            'email' => Yii::t('app', 'Email'),
            'contact_number' => Yii::t('app', 'Contact Number'),
            'lat' => Yii::t('app', 'Latitude'),
            'lng' => Yii::t('app', 'Longitude'),
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
      $query = CmsSettings::find()->where(['status'=>1])->orderby('id Desc');
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
    }
}
