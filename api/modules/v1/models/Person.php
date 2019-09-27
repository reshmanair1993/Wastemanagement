<?php

namespace api\modules\v1\models;

use Yii;

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
 * @property int $fk_gender
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 * @property int $fk_image_profile_pic
 * @property int $fk_country_home
 * @property int $fk_country_foreign
 * @property int $fk_person_contact_home
 * @property int $fk_person_contact_foreign
 *
 * @property Country $fkCountryForeign
 * @property Person $fkPersonContactForeign
 * @property Person[] $people
 * @property Person $fkPersonContactHome
 * @property Person[] $people0
 * @property Country $fkCountryHome
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
            [['dob', 'created_at', 'modified_at'], 'safe'],
            // [['address'], 'string'],
            // [['fk_gender', 'status', 'fk_image_profile_pic', 'fk_country_home', 'fk_country_foreign', 'fk_person_contact_home', 'fk_person_contact_foreign'], 'integer'],
            // [['email', 'first_name', 'middle_name', 'last_name'], 'string', 'max' => 255],
            // [['phone1', 'phone2'], 'string', 'max' => 45],
            // [['fk_country_foreign'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['fk_country_foreign' => 'id']],
            // [['fk_person_contact_foreign'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fk_person_contact_foreign' => 'id']],
            // [['fk_person_contact_home'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fk_person_contact_home' => 'id']],
            // [['fk_country_home'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['fk_country_home' => 'id']],
        ];
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
            'phone1' => Yii::t('app', '	'),
            'phone2' => Yii::t('app', 'Phone2'),
            'dob' => Yii::t('app', 'Dob'),
            'address' => Yii::t('app', 'Address'),
            'fk_gender' => Yii::t('app', 'Fk Gender'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
            'fk_image_profile_pic' => Yii::t('app', 'Fk Image Profile Pic'),
            'fk_country_home' => Yii::t('app', 'Fk Country Home'),
            'fk_country_foreign' => Yii::t('app', 'Fk Country Foreign'),
            'fk_person_contact_home' => Yii::t('app', 'Fk Person Contact Home'),
            'fk_person_contact_foreign' => Yii::t('app', 'Fk Person Contact Foreign'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkCountryForeign()
    {
        return $this->hasOne(Country::className(), ['id' => 'fk_country_foreign']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkImageProfilePic()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkPersonContactForeign()
    {
        return $this->hasOne(Person::className(), ['id' => 'fk_person_contact_foreign']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['fk_person_contact_foreign' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkContactHome()
    {
        return $this->hasOne(Contact::className(), ['id' => 'fk_contact_home']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkLocality()
    {
        return $this->hasOne(Locality::className(), ['id' => 'fk_locality']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkContactForeign()
    {
        return $this->hasOne(Contact::className(), ['id' => 'fk_contact_foreign']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkGender()
    {
        return $this->hasOne(Gender::className(), ['id' => 'fk_gender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkPersonContactHome()
    {
        return $this->hasOne(Person::className(), ['id' => 'fk_person_contact_home']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople0()
    {
        return $this->hasMany(Person::className(), ['fk_person_contact_home' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkCountryHome()
    {
        return $this->hasOne(Country::className(), ['id' => 'fk_country_home']);
    }

    /**
     * @inheritdoc
     * @return PersonQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PersonQuery(get_called_class());
    }
    public function getActiveUsersCount()
    {
        return Person::find()->where(['status'=> 1])->count();
    }
    public function getContactHome()
    {
        $contactHome = '';
        $contactHomeRelation = $this->fkContactHome;
        if($contactHomeRelation!=null){
            $contactHome = $this->fkContactHome->phone;
        }

        return $contactHome;
    }
    public function getContactForeign()
    {
        $contactForeign = '';
        $contactForeignRelation = $this->fkContactForeign;
        if($contactForeignRelation!=null){
            $contactForeign = $this->fkContactForeign->phone;
        }

        return $contactForeign;
    }
    public function getGender()
    {
        $gender = null;
        if($this->fkGender){
            $genderRelation = $this->fkGender;
            $gender = $genderRelation->name?$genderRelation->name:'';
        }
        return $gender;
    }
    public function getLocality()
    {
        $locality = null;
        if($this->fkLocality){
            $locality = $this->fkLocality->name;
        }

        return $locality;
    }
    public function getEmergencyContacts()
    {
        $contactList = [];
        $query = ContactEmergency::find()->where(['fk_account'=> Yii::$app->user->id])->andwhere(['status'=> 1]);
        if($query){
            $contacts = $query->asArray()->all();
            foreach ($contacts as $contact) {
                $contactList[] = [
                    'name'=> isset($contact['name'])?$contact['name']:null,
                    'phone'=> isset($contact['phone'])?$contact['phone']:null,
                ]; 
            }
        }
        return $contactList;
    }
    public function getProfilePic()
    {
        $profilePic = isset(Yii::$app->params['defaultImage'])?(Yii::$app->params['base_url'].Yii::$app->params['defaultImage']):'!#';

        $fkProfilePic = $this->fkImageProfilePic;
        if($fkProfilePic!=null){
            $profilePic = $fkProfilePic->fullUrl();
        }
        
        return $profilePic;
    }
}
