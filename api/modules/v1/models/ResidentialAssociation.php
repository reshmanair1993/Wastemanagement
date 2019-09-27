<?php

namespace api\modules\v1\models;


use Yii;

/**
 * This is the model class for table "ward".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $isgi_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ResidentialAssociation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'residential_association';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [[
            'status','association_type_id','ward_id',
          ], 'integer'],
          [['ward_id'],'required'],
          [['created_at', 'modified_at'], 'safe'],
          [[
            'name','registration_number','address','email','year','president_name',
            'secretary_name','treasurer_name',
          ], 'string', 'max' => 255],
          [['email'],'email'],
          [[
            'president_phone_number','secretary_phone_number','treasurer_phone_number',
            'no_of_households_in_association'
          ],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'association_type_id' => Yii::t('app','Association type'),
            'name' => Yii::t('app','Association name'),
            'ward_id' => Yii::t('app','Ward'),
            'year' => Yii::t('app','Year of formation'),
            'president_phone_number' => Yii::t('app','President contact number'),
            'secretary_phone_number' => Yii::t('app','Secretary contact number'),
            'treasurer_phone_number' => Yii::t('app','Treasurer contact number'),
            'no_of_households_in_association' => Yii::t('app','number of households in association'),
            'status' => Yii::t('app','Status'),
            'created_at' => Yii::t('app','Created At'),
            'modified_at' => Yii::t('app','Modified At'),
        ];
    }
    public static function getAllQuery() {
      return static::find()->where(['status'=>1])->orderby('id ASC');
    }
}
