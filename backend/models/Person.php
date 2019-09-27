<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property string $email
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $phone1
 * @property string $phone2
 * @property string $dob
 * @property string $address
 * @property int $fk_state
 * @property int $fk_district
 * @property int $fk_locality
 * @property int $gender_id
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 * @property int $fk_image_profile_pic
 * @property int $fk_country_home
 * @property int $fk_country_foreign
 * @property int $fk_person_contact_home
 * @property int $fk_person_contact_foreign
 * @property int $fk_language_home
 * @property int $fk_language_foreign
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email','phone1', 'first_name','fk_gender'],'required'],
            [['dob', 'created_at', 'modified_at'], 'safe'],
            [['email'],'email'],
            [['address'], 'string'],
            [['fk_state', 'fk_district', 'fk_locality', 'fk_gender', 'status', 'image_id', 'fk_country_home', 'fk_country_foreign', 'fk_person_contact_home', 'fk_person_contact_foreign', 'fk_language_home', 'fk_language_foreign'], 'integer'],
            [['email', 'first_name', 'middle_name', 'last_name'], 'string', 'max' => 255],
            // [['phone1', 'phone2'], 'min' => 10],
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
    public function getGender()
  	{
  			$genders =  Gender::find()->where(['status'=> 1])->all();
  			return $genders;
  	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'phone1' => Yii::t('app', 'Phone Number'),
            'phone2' => Yii::t('app', 'Phone Number'),
            'dob' => Yii::t('app', 'Dob'),
            'address' => Yii::t('app', 'Address'),
            'fk_state' => Yii::t('app', 'State'),
            'fk_district' => Yii::t('app', 'District'),
            'fk_locality' => Yii::t('app', 'Locality'),
            'fk_gender' => Yii::t('app', 'Gender'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
            'image_id' => Yii::t('app', 'Image'),
            'fk_country_home' => Yii::t('app', 'Fk Country Home'),
            'fk_country_foreign' => Yii::t('app', 'Fk Country Foreign'),
            'fk_person_contact_home' => Yii::t('app', 'Fk Person Contact Home'),
            'fk_person_contact_foreign' => Yii::t('app', 'Fk Person Contact Foreign'),
            'fk_language_home' => Yii::t('app', 'Fk Language Home'),
            'fk_language_foreign' => Yii::t('app', 'Fk Language Foreign'),
        ];
    }
}
