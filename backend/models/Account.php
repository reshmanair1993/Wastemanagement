<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $role
 * @property string $password_reset_token
 * @property string $activation_token
 * @property string $password_token_expiry
 * @property string $auth_key
 * @property int $is_verified
 * @property int $person_id
 * @property int $lsgi_id
 * @property int $green_action_unit_id
 * @property int $is_banned
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Account extends \yii\db\ActiveRecord
{
  public $district_id,$block_id,$assembly_constituency_id,$password_repeat,$residence_association,$gt_id;
    public $confirm_password;
    public $password;
    public $compare_password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    public function rules()
    {
        return [
            // [['password_hash'],'required','on'=>['update']],
            [['username', 'password_hash', 'lsgi_id', 'green_action_unit_id','supervisor_id'], 'required', 'on' => 'create'],
            [['username', 'password_hash', 'lsgi_id', 'green_action_unit_id','supervisor_id'], 'required', 'on' => 'gt'],
            [['username', 'password_hash', 'lsgi_id'], 'required', 'on' => 'create-admin-lsgi'],
            [['username', 'password_hash',], 'required', 'on' => 'create-super-admin'],
            [['username', 'password_hash', 'lsgi_id','green_action_unit_id'], 'required', 'on' => 'create-admin-hks'],
            [['username', 'password_hash', 'lsgi_id','green_action_unit_id'], 'required', 'on' => 'supervisor'],
            [['username','lsgi_id','green_action_unit_id','survey_agency_id'], 'required', 'on' => 'coordinator'],
             // [['username'],'unique'],
          ['username','validateUserName','on' => 'add'],
            [['password_hash'],'string','min' => 8],
            [['password_token_expiry','role','compare_password','created_at', 'modified_at'], 'safe'],
            [['is_verified', 'person_id', 'lsgi_id', 'green_action_unit_id', 'is_banned', 'status','supervisor_id','survey_agency_id','residential_association_id'], 'integer'],
            [['username', 'password_hash', 'role', 'password_reset_token', 'activation_token', 'auth_key'], 'string', 'max' => 255],
            [['password_hash'],'password_exist','on'=>['update'],'skipOnEmpty' => false],
            // [['password_hash'],'same_password'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password_hash', 'message'=>"Passwords don't match" ],
            [['password', 'confirm_password'], 'required','on'=>'reset-user-password'],
            [['username','password_hash','compare_password'],'string','on'=>'create-technician'],
            [['username'],'if_username_exists','on'=>'create-technician'],
            [['password_hash'],'if_new_password_exists','on'=>'create-technician'],
        ];
    }
    // public function same_password($attribute){
    //   $newPassword = $this->password_hash;
    //   $reTypePassword = $this->password_repeat;
    //   if($newPassword != null)
    //   {
    //     if($newPassword!=$reTypePassword) {
    //       $this->addError('re_type_password', Yii::t('app', 'Re type does not match with new password'));
    //       return;
    //      }
    //   }
    // }
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
 public function if_new_password_exists($attribute){
   // echo "string";exit;
   $newPassword = $this->password_hash;
   $reTypePassword = $this->compare_password;
   if($newPassword != null)
   {
     if($newPassword!=$reTypePassword) {
       $this->addError('compare_password','Re type does not match with new password');
      }
   }
 }
 public function if_username_exists($attribute){
   $username = $this->username;
   $modelUser = Account::find()->where(['username'=>$username])->andWhere(['status'=>1])->one();
   if($modelUser){
     $this->addError('username','Username must be unique');
     return;
   }
 }
     public function validateUserName(){
    $username = $this->username;
    $modelUser = Account::find()->where(['username'=>$username])->andWhere(['status'=>1])->one();
    if($modelUser){
        $this->addError('username','Username must be unique');
    }
}
    public function scenarios() {
      return [
                self::SCENARIO_DEFAULT => [
                  'username','password_hash','lsgi_id', 'green_action_unit_id','supervisor_id','survey_agency_id','compare_password'
                ],
                self::SCENARIO_CREATE => ['password_hash','username','lsgi_id', 'green_action_unit_id','supervisor_id'],
                self::SCENARIO_UPDATE => ['username','lsgi_id', 'green_action_unit_id','password_hash','supervisor_id','survey_agency_id'],
                'create-admin-hks' => ['username','lsgi_id', 'green_action_unit_id','password_hash','district_id','assembly_constituency_id','block_id'],
                'create-admin-lsgi' => ['username','lsgi_id','password_hash','district_id','assembly_constituency_id','block_id'],
                'supervisor' => ['username','lsgi_id','password_hash','district_id','assembly_constituency_id','block_id'],
                'gt' => ['username','password_hash','lsgi_id', 'green_action_unit_id','supervisor_id'],
                'coordinator' => ['username','password_hash','lsgi_id', 'survey_agency_id','green_action_unit_id'],
                 'create-super-admin' => ['username','password_hash'],
                 'add' => ['username'],
                  'reset-user-password' => ['confirm_password','password'],
        'update' => ['username'],
        'create-technician' =>['password_hash','compare_password','username','lsgi_id'],

      ];
    }
    public function password_exist($attribute){
      $password = $this->$attribute;
      if(!$password)
      {
        $modelAccount = $this->find()->where(['id'=>$this->id])->one();
        $oldPassword = $modelAccount->password_hash;
        $this->password_hash = $oldPassword;
        // $this->addError($attribute,'cannot be blank');
      }
    }
    public function hashPassword() {
		 $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password_hash);
		}

    public function getFkPerson()
		{
				return $this->hasOne(Person::className(), ['id' => 'person_id']);
		}
    public function getFkCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
    public function getFkCustomerGt()
    {
        return $this->hasOne(AccountAuthority::className(), ['account_id_customer' => 'account_id']);
    }
    public function getFkAccountAuthority()
    {
        return $this->hasOne(AccountAuthority::className(), ['account_id_customer' => 'id'])->andWhere(['status'=>1]);
    }
		public function getFkLsgi()
		{
				return $this->hasOne(Lsgi::className(), ['id' => 'lsgi_id']);
		}
		public function getFkGreenActionUnit()
		{
				return $this->hasOne(GreenActionUnit::className(), ['id' => 'green_action_unit_id']);
		}
    public function getLsgi()
	 {
			 $lsgi =  Lsgi::find()->where(['status'=> 1])->all();
			 return $lsgi;
	 }
	 public function getPerson()
	{
			$person =  Person::find()->where(['status'=> 1])->all();
			return $person;
	}

	 public function getGreenActionUnit($lsgi=null)
	{
    // print_r($lsgi);die();
			$greenActionUnit =  GreenActionUnit::find()->where(['status'=> 1]);
      if($lsgi)
      {
        $greenActionUnit = $greenActionUnit->andWhere(['lsgi_id'=>$lsgi]);
      }
      $greenActionUnit = $greenActionUnit->all();
			return $greenActionUnit;
	}
  public function getResidenceAssociation($lsgi=null,$unit=null)
  {
    // print_r($lsgi);die();
      $residenceAssociation =  ResidentialAssociation::find()->where(['residential_association.status'=> 1]);
      if($lsgi)
      {
        $residenceAssociation = $residenceAssociation->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=residential_association.ward_id')
        ->leftjoin('ward','ward.id=green_action_unit_ward.ward_id')
        ->andWhere(['ward.lsgi_id'=>$lsgi])
        ;
      }
      if($unit)
      {
        $residenceAssociation = $residenceAssociation->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
      }
      $residenceAssociation = $residenceAssociation->all();
      return $residenceAssociation;
  }
  public function getResidenceAssociations($ward=null)
  {
    // print_r($lsgi);die();
      $residenceAssociation =  ResidentialAssociation::find()->where(['residential_association.status'=> 1])->andWhere(['ward_id'=>$ward]);
      $residenceAssociation = $residenceAssociation->all();
      return $residenceAssociation;
  }

    public function deleteAccount($id)
   {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('account', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();

    $connectionaccount = Yii::$app->db;
    $connectionaccount->createCommand()->update('account_authority', ['status' => 0], 'account_id_supervisor=:id')->bindParam(':id',$id)->execute();

    $connectionaccountnew = Yii::$app->db;
    $connectionaccountnew->createCommand()->update('account_authority', ['status' => 0], 'accountid_gt=:id')->bindParam(':id',$id)->execute();
    return true;
   }
    public static function getAllQuery($lsgi=null,$unit=null,$keyword=null,$supervisor=null) {
        $agency = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id']))
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt_id = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
    // if($userRole=='admin-lsgi'&&isset($modelUser->lsgi_id))
    //     {
    //         $lsgi = $modelUser->lsgi_id;
    //     }
    // elseif($userRole=='admin-hks'&&isset($modelUser->green_action_unit_id))
    // {
    //   $unit = $modelUser->green_action_unit_id;
    // }
    // elseif($userRole=='coordinator')
    // {
    //   $agency = $modelUser->survey_agency_id;
    //   // $unit = $modelUser->green_action_unit_id;
    // }
    elseif($userRole=='supervisor'&&isset($modelUser->id))
    {
      $supervisor = $modelUser->id;
    }
     $query = static::find()->where(['account.status'=>1]);
     if($lsgi)
     {
        $query->andWhere(['account.lsgi_id'=>$lsgi]);
     }
     if($unit)
     {
        $query->andWhere(['account.green_action_unit_id'=>$unit]);
     }
     if($supervisor)
     {
        $query->andWhere(['account.supervisor_id'=>$supervisor]);
     }
     if($agency)
     {
      $query->andWhere(['account.survey_agency_id'=>$agency]);
     }
     if($keyword){
        $query->leftJoin('person', 'person.id=account.person_id')->andFilterWhere(['or', ['LIKE', 'person.first_name', $keyword], ['LIKE', 'person.middle_name', $keyword], ['LIKE', 'person.last_name', $keyword]]);
      }
     return $query;
   }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password_hash' => Yii::t('app', 'Password'),
            'role' => Yii::t('app', 'Role'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'activation_token' => Yii::t('app', 'Activation Token'),
            'password_token_expiry' => Yii::t('app', 'Password Token Expiry'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'person_id' => Yii::t('app', 'Person'),
            'lsgi_id' => Yii::t('app', 'Lsgi'),
            'district_id' => Yii::t('app', 'District'),
            'block_id' => Yii::t('app', 'Block'),
            'supervisor_id' => Yii::t('app', 'Supervisor'),
            'assembly_constituency_id' => Yii::t('app', 'Assembly Constituency'),
            'green_action_unit_id' => Yii::t('app', 'Haritha Karma Sena'),
            'is_banned' => Yii::t('app', 'Is Banned'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'survey_agency_id' => Yii::t('app', 'Survey Agency'),
        ];
    }
    public function getLsgis($id)
    {
        $name  = null;
        $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
            $name = $lsgi->name;
        }
      return $name;
    }
     public function getUnit($id)
    {
        $name  = null;
        $unit =  GreenActionUnit::find()->where(['id'=> $id])->one();
        if($unit){
            $name = $unit->name;
        }
      return $name;
    }
    public function getAgency($id)
    {
        $name  = null;
        $unit =  SurveyAgency::find()->where(['id'=> $id])->one();
        if($unit){
            $name = $unit->name;
        }
      return $name;
    }
    public function getSuperVisor($id)
    {
        $name  = null;
        $account =  Account::find()->where(['id'=> $id])->one();
        if($account){
            $person = Person::find()->where(['id'=>$account->person_id])->one();
            if($person)
            $name = $person->first_name;
        }
      return $name;
    }
     public function getBlock($id)
    {
        $name  = null;
        $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
        $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $name = $block->name;
        }
         }
      return $name;
    }
    public function getConstituency($id)
    {
        $name = null;
         $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
         $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            $name = $assembly_constituency->name;
        }

    }

        return $name;
    }
     public function getDistrict($id)
    {
        $name = null;
       $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
         $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            if($assembly_constituency)
            {
                $district = District::find()->where(['id'=> $assembly_constituency->district_id])->one();
                $name = $district->id;
            }

        }
    }
        return $name;
    }
     public function getDistricts()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
    }
    public function getUnits($lsgi=null)
    {
        $units =  GreenActionUnit::find()->where(['status'=> 1])->andWhere(['lsgi_id'=>$lsgi])->all();
        return $units;
    }
    public function getSurveyAgency($lsgi=null)
    {
        $agency =  SurveyAgency::find()->where(['status'=> 1])->andWhere(['lsgi_id'=>$lsgi])->all();
        return $agency;
    }
     public function getSupervisors($hks=null)
    {
       $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['ward_id']))
        {
            $wardId = $associations['ward_id'];
            $wardId = json_decode($wardId);
        }
         if(isset($associations['hks_id']))
        {
            $hks = $associations['hks_id'];
        }
            $supervisor = Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                ->andWhere(['account.role' => 'supervisor']);
                if($hks)
                {
                  $supervisor = $supervisor->andWhere(['green_action_unit_id'=>$hks]);
                }
                $supervisor = $supervisor->all();
            return $supervisor;
    }

    public function getGt($hks=null)
    {
       $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['ward_id']))
        {
            $wardId = $associations['ward_id'];
            $wardId = json_decode($wardId);
        }
         if(isset($associations['hks_id']))
        {
            $hks = $associations['hks_id'];
        }
            $gt = Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                ->andWhere(['account.role' => 'green-technician']);
                if($hks)
                {
                  $gt = $gt->andWhere(['green_action_unit_id'=>$hks]);
                }
                if($modelUser->role=='supervisor')
                {
                  $gt->andWhere(['account.supervisor_id'=>$modelUser->id]);
                }
                $gt = $gt->all();
            return $gt;
    }

     public static function getAllQuerySurvey($keyword=null,$agency = null) {
        $unit = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id']))
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
     $query = static::find()->where(['account.status'=>1])
     ->andWhere(['role'=>'surveyor'])
     ->orderby('id DESC');

     if($agency)
     {
      $query->andWhere(['survey_agency_id'=>$agency]);
     }
     if($keyword){
        $query->leftJoin('person', 'person.id=account.person_id')->andFilterWhere(['or', ['LIKE', 'person.first_name', $keyword], ['LIKE', 'person.middle_name', $keyword], ['LIKE', 'person.last_name', $keyword]]);
      }
       // if($from && $to==null)
       //  {
       //      $query->leftJoin('customer', 'customer.creator_account_id=account.id')->andWhere(['>=', 'customer.created_at', $from]);
       //  }
       //  if($to && $from==null)
       //  {
       //      $query->leftJoin('customer', 'customer.creator_account_id=account.id')->andWhere(['<=', 'customer.created_at', $to]);
       //  }
       //  if($from && $to
       //      )
       //  {
       //      $query->leftJoin('customer', 'customer.creator_account_id=account.id')
       //          ->andWhere(['>=', 'customer.created_at', $from])
       //          ->andWhere(['<=', 'customer.created_at', $to]);
       //  }
     return $query;
   }
    public function getSurveyors()
    {
            $surveyor = Person::find()
                ->select('account.id as id,account.username as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                ->andWhere(['account.role' => 'surveyor'])
                ->all();
            return $surveyor;
    }
    public function getName($id)
    {
       $modelPerson =  Person::find()->where(['id' => $id,'status'=> 1])->one();
       if($modelPerson)
       return $modelPerson->first_name;
    }
    public function getCameraTechnician($id)
   {
       $modelPerson =  Person::find()->where(['id' => $id,'status'=> 1])->one();
       if($modelPerson)
       return $modelPerson->email;
   }
   public function getMonitoringPerson($id)
  {
      $modelPerson =  Person::find()->where(['id' => $id,'status'=> 1])->one();
      if($modelPerson)
      return $modelPerson->email;
  }
  public function getLsgiId($id)
  {
    $modelLsgi =  Ward::find()->where(['id' => $id,'status'=> 1])->one();
    return $modelLsgi;
  }
  public function deleteMonitoringPerson($id)
   {
  $connection = Yii::$app->db;
  $connection->createCommand()->update('account', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
  return true;
}
   public function deleteCameraTechnician($id)
    {
   $connection = Yii::$app->db;
   $connection->createCommand()->update('account', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
   return true;
   }
   public function getWard($ward = null)
   {
       $name  = null;
       $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
       if ($wards)
       {
           $name = $wards->name;
       }

       return $name;
   }
   public function getHks($lsgi_id)
  {
      $greenActionUnit =  GreenActionUnit::find()->where(['status'=> 1])->andWhere(['lsgi_id'=>$lsgi_id])->all();
      return $greenActionUnit;
  }
  public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    public function getLsgiAccount($id)
    {
        $name  = null;
        $accountLsgi =  AccountDistrictLsgi::find()->where(['account_id'=> $id])->one();
        if($accountLsgi){
          $lsgis = json_decode($accountLsgi->lsgi_id);
            foreach ($lsgis as $lsgi) {

              $modelLsgi = Lsgi::find()->where(['id'=>$lsgi])->andWhere(['status'=>1])->one();
              if($modelLsgi)
              {
                $name = $name.$modelLsgi->name.',';
              }
            }
        }
      return rtrim($name,",");
    }
}
