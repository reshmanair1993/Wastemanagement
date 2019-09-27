<?php
namespace api\modules\v1\controllers;
use Yii;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Account;
use api\modules\v1\models\LoginForm;
use api\modules\v1\models\Person;
use api\modules\v1\models\Gender;
use api\modules\v1\models\Image;
use api\modules\v1\models\AccessToken;
use api\modules\v1\models\UserToken; 
use api\modules\v1\models\Customer;  
use api\modules\v1\models\LoginHistory;  
use yii\filters\auth\HttpBearerAuth;
/**
 * UsersController implements the CRUD actions for Account model.
 */
class UsersController extends ActiveController
{
    /**
     * @inheritdoc
     */
     public $modelClass = '\api\modules\v1\models\Account';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'auth' => [
                'class' => HttpBearerAuth::className(),
                'except' => ['signup','login','sendPasswordReset','send-password-reset','reset','tst','generate-otp','verify-otp'],

            ]
        ];
    }
	public function actionTst($pd) {
		return Yii::$app->getSecurity()->generatePasswordHash($pd);
	}
    public function actionGetToken($code) {
      $modelUser = Account::find()->where(['status'=>1])->andWhere(['password_reset_token'=>$code])->one();

      if($modelUser) {
        $modelAccessToken = new TbAccessToken;
        $modelAccessToken->generateForUser($modelUser);
        $modelUser->password_reset_token = null;
        $modelUser->save(false);
        $ret = [
          'access_token' => $modelAccessToken->access_token
        ];

        return $ret;

      } else {

          Yii::$app->response->statusCode = 404;
      }
    }

    public function actionHelp()
    {
      $ret = [];
      $params = Yii::$app->request->post();
      $modelHelp = new Enquiry;
      $proceed = $modelHelp->load(Yii::$app->request->post(), '') && $modelHelp->validate($params);
      if($proceed){
        $id = Yii::$app->user->id;
        $modelUser = Account::findOne($id);
        $modelPerson = $modelUser->fkPerson;
        $modelHelp->user_id = $modelPerson->id;
        $modelHelp->fk_country_home = $modelPerson->fk_country_home;
        $modelHelp->fk_country_foreign = $modelPerson->fk_country_foreign;
        $modelHelp->save(false);
       // $mail = Yii::$app->email->sendWebsiteEnquiry($modelHelp);
        $ret = ['status'=> 'success'];
      }

      if ($modelHelp->getErrors()) $ret = $modelHelp->getErrors();
      return $ret;
    }

	public function log($value) { ;
		$jsonfile= Yii::getAlias('@webroot/log.html');
		$fp = fopen($jsonfile, 'w+');
	    fwrite($fp, $value);
		fclose($fp);
	}
    public function actionUpdateProfile() {
      $ret = [];

      while(true) {
        $id = Yii::$app->user->id;
        $modelUser = Account::findOne($id);
        $modelPerson = $modelUser->fkPerson;
        $modelWord = new Word();
        $modelImage = new Image();
        if(!$modelPerson) break;

        $name = $modelPerson->first_name;
        $username = $modelUser->username;
        $email = $modelPerson->email;
        $phone1 = $modelPerson->phone1;
        $newPhotoId = $modelPerson->fk_image_profile_pic;
        $gender = $modelPerson->fkGender['name'];
        $dob = $modelPerson->dob;
        $password = null;
        $passwordHash = $modelUser->password_hash;

        $localityId = $modelPerson->getLocality();
        $districtId = null;
        $stateId = null;
        $homeCountryId = null;
        $homeLanguageId = null;
        $destinationCountryId = null;
        $destinationPhone = null;
        

        $modelUser->setScenario('update');
        $params = Yii::$app->request->post();

        $name = isset($params['name'])?$params['name']:$name;
        $phone1 = isset($params['phone'])?$params['phone']:$phone1;
        $gender = isset($params['gender'])?$params['gender']:$gender;
        $dob = isset($params['dob'])?$params['dob']:$dob;
        $dob = date('Y-m-d', strtotime($dob));

        $locality = null;
        $districtId = isset($params['district_id'])?$params['district_id']:$districtId;
        $stateId = isset($params['state_id'])?$params['state_id']:$stateId;
        $homeCountryId = isset($params['home_country_id'])?$params['home_country_id']:$homeCountryId;
        $homeLanguageId = isset($params['home_language_id'])?$params['home_language_id']:$homeLanguageId;
        $destinationCountryId = isset($params['destination_country_id'])?$params['destination_country_id']:$destinationCountryId;
        $destinationPhone = isset($params['destination_phone'])?$params['destination_phone']:$destinationPhone;

        $password = isset($params['password'])?$params['password']:null;

        if(null!=$password) {
           $passwordHash = Yii::$app->getSecurity()->generatePasswordHash($password);
        }
        $modelGender = Gender::find()->where(['name'=> $gender])->one();
        $gender = $modelGender?$modelGender->id:null;
        $modelPerson->fk_gender = $gender;

        $modelPerson->first_name = $name;
        $modelPerson->dob = $dob;
        $modelPerson->phone1 = $phone1;
        $modelPerson->fk_state = $stateId;
        $modelPerson->fk_district = $districtId;


        $modelPerson->fk_country_home = $homeCountryId;
        $modelPerson->fk_country_foreign = $destinationCountryId;
        // $modelPerson->fk_person_contact_foreign = $destinationPhone;
        $modelPerson->fk_language_home = $homeLanguageId;

        $modelUser->username = $username;
        // print_r($modelPerson);exit;

        $modelWord->name = $locality;


        $modelImage->setScenario("single-image-upload");
        $modelImage->load($params);
        $modelImage->uploaded_files = UploadedFile::getInstanceByName('image');
        $newPhotoOk = $modelImage->validate();

        $userOk = $modelUser->validate();
        $personOk = $modelPerson->validate();
        $wordOk = $modelWord->validate();

        $localityWordId = null;
        if($wordOk){
          $modelLocalityWord = $modelWord->saveWord();
          $localityWordId = $modelLocalityWord->id;
        }
        $modelPerson->fk_locality = $localityId;

        if ($newPhotoOk) {
           $newPhotoId = $modelImage->uploadAndSave($modelImage->uploaded_files);
        }
        $modelPerson->fk_image_profile_pic = $newPhotoId;

        if(!($userOk&& $personOk))
        break;
        
        $modelUser->password_hash = $passwordHash;
        
        $modelPerson->save(false);
        $modelUser->save(false);
        $ret = [
          'empId'=> $modelUser->id,
          'name'=> $modelPerson->first_name,
          'phone'=> $modelPerson->phone1,
          'email'=> $modelPerson->email,
          'dob'=> date('d-m-Y', strtotime($modelPerson->dob)),
          'gender'=> $modelPerson->getGender(),
          'profilePic'=> $modelPerson->getProfilePic(),
        ];
        break;
      }
      $errors = array_merge($modelPerson->errors,$modelUser->errors);

      if($errors) $ret = $errors;
      return $ret;
    }
    public function actionGetProfile() {
      $ret = [];
      $id = Yii::$app->user->id;
      $emergencyContacts = [];
      $name = null;
      $phone1 = null;
      $home_country_id = null;
      $home_language_id = null;
      $destination_country_id = null;
      $state_id = null;
      $district_id = null;
      $gender = null;
      $locality = null;
      $dob = null;

      $modelUser = Account::findOne($id);
      $modelPerson = $modelUser->fkPerson;
      if($modelPerson) {
        $emergencyContacts = $modelPerson->getEmergencyContacts();
        $name = isset($modelPerson->first_name)?$modelPerson->first_name:null;
        $phone1 = isset($modelPerson->phone1)?$modelPerson->phone1:null;
        $email = isset($modelPerson->email)?$modelPerson->email:null;
        $home_country_id = isset($modelPerson->fk_country_home)?$modelPerson->fk_country_home:null;
        $home_language_id = isset($modelPerson->fk_language_home)?$modelPerson->fk_language_home:null;
        $destination_country_id = isset($modelPerson->fk_country_foreign)?$modelPerson->fk_country_foreign:null;
        $state_id = isset($modelPerson->fk_state)?$modelPerson->fk_state:null;
        $district_id = isset($modelPerson->fk_district)?$modelPerson->fk_district:null;
        $gender = $modelPerson->getGender();
        $locality = $modelPerson->getLocality();
        $dob = $modelPerson->dob;
      }
      return $ret = [
          'empId'=> $modelUser->id,
          'name'=> $name,
          'phone'=> $phone1,
          'email'=> $email,
          'dob'=> date('d-m-Y', strtotime($modelPerson->dob)),
          'gender'=> $modelPerson->getGender(),
          'profilePic'=> $modelPerson->getProfilePic(),
          
        ];;
    }

    // public function actionSignup() {

    //   $post = Yii::$app->request->post();

    //   $name = null;
    //   foreach($post as $param => $val) ${$param} = $val;
    //   $gender = isset($gender)?$gender:"female";
    
    //   $modelUser = new Account;
    //   $modelPerson =  new Person;

    //   $modelPerson->fk_country_home = isset($home_country_id)?$home_country_id:null;
    //   $modelPerson->fk_country_foreign = isset($destination_country_id)!=null?$destination_country_id:null;
    //   $modelPerson->fk_language_home = isset($home_language)?$home_language:null;
    //   $modelPerson->email = isset($email)?$email:null;
    //   $modelPerson->phone1 = isset($phone)?$phone:null;
    //   $password = isset($password)?$password:'';
    //   $modelUser->password_hash = $password;

    //   $modelContactEmergency =  new ContactEmergency;
     
    //   $modelUser->setScenario('create');
    //   $modelPerson->first_name = $name;

    //   $modelUser->username = $name;
    //   $modelGender = Gender::find()->where(['name'=> $gender])->one();
    //   $gender = $modelGender?$modelGender->id:null;
    //   $modelPerson->fk_gender = $gender;

    //   $personOk =  $modelPerson->validate();
    //   $userOk =  $modelUser->validate();
    //   $modelUser->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    //   $modelUser->password_hash = '1';
    //   //Mode load
    //   if( $personOk && $userOk ) {
    //       $modelPerson->save(false);

    //       $modelUser->type = 1;
    //       $modelUser->fk_person = $modelPerson->id;

    //       $modelUser->save(false);

    //       $modelAccessToken = new AccessToken;
    //       $modelAccessToken->generateForUser($modelUser);
    //       $accessToken = $modelAccessToken->access_token;
		  // $userId = $modelUser->id;
    //   $ret = $userId;
		  // $contacts = isset($post['emergency_numbers'])?$post['emergency_numbers']:"[]";
		  
		  // $contacts = json_decode($contacts); 
		  // $contacts = $contacts?$contacts:[];
		  // foreach ($contacts as $contact) {
			 //  $modelContactEmergency =  new ContactEmergency;
			 //  $modelContactEmergency->fk_account = $userId;
			 //  $name = isset($contact->name)?$contact->name:'';
			 //  $phone = isset($contact->phone)?$contact->phone:'';
			 //  $modelContactEmergency->name = $name;
			 //  $modelContactEmergency->phone = $phone;
			 //  if($name  || $phone) $modelContactEmergency->save(false);
 		 //  } 
		  
    //       $ret = [
    //         'empId' => $modelUser->id,
    //         'name'=>$modelUser->username,
    //         'email'=>$modelPerson->email,
    //         'phone'=>$modelPerson->phone1,
    //         'gender' => $modelPerson->getGender(),
    //         'access_token' => $accessToken
    //       ];
    //   } else {
    //     $errors = ['erros' => array_merge($modelPerson->errors,$modelUser->errors)];
    //     $ret =  $errors;
    //   }
    //   return $ret;

    // }
  public function actionSignup() {

      $params = Yii::$app->request->post();
      if(!isset($params['customer_phone']))
      {
        $msg   = ['Phone number is mandatory'];
        $error = ['customer_phone' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
      }
      if(!isset($params['customer_name']))
      {
        $msg   = ['Name is mandatory'];
        $error = ['customer_name' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
      }
      $modelCustomerData = Customer::find()
      ->where(['customer.lead_person_phone'=>$params['customer_phone']])
      ->andWhere(['customer.status'=>1])
      ->one();
$message = null;
      if($modelCustomerData)
      {
        if($modelCustomerData->sign_up==1)
        {
        $msg   = ['You already signed up'];
        $error = ['customer_phone' => $msg];
        $message   = ['errors' => $error];
        // return $ret;
        }
        $modelAccount = Account::find()->where(['customer_id'=>$modelCustomerData->id])->one();
        if($modelAccount)
        {
          if($params['ward_id']!=$modelCustomerData->ward_id)
            {
               $qry1 = "SELECT max(customer_id) as customer_id FROM `customer` where status=1 and ward_id=:ward_id and is_public_customer=0";
          $ward_id = $params['ward_id'];
          $command1 =  Yii::$app->db->createCommand($qry1);
          $command1->bindParam(':ward_id',$ward_id);
          $data1 = $command1->queryAll();
          $maxCount = $data1[0];
          $max = $maxCount['customer_id'];
          if($max):
          $modelCustomerData->customer_id = $max+1; 
        else:
          $modelCustomerData->customer_id = 1;
        endif;
      }
           $modelCustomerData->lead_person_phone = $params['customer_phone'];
        $modelCustomerData->lead_person_name = $params['customer_name'];
        $modelCustomerData->ward_id = $params['ward_id'];
        $modelCustomerData->residential_association_id = isset($params['residential_association_id'])?$params['residential_association_id']:$modelCustomerData->residential_association_id;
        $modelCustomerData->association_number = isset($params['association_number'])?$params['association_number']:$modelCustomerData->association_number;
        $modelCustomerData->sign_up = 1;
        $modelCustomerData->save(false);
        $modelAccessToken =  new AccessToken;
        $modelAccessToken->generateForUser($modelAccount);
        $lsgiId = isset($modelCustomerData->fkWard->fkLsgi)?$modelCustomerData->fkWard->fkLsgi->id:null;
          $lsgiName = isset($modelCustomerData->fkWard->fkLsgi)?$modelCustomerData->fkWard->fkLsgi->name:null;

      $accountAuthority = $modelAccount->fkAccountAuthority;
      if($accountAuthority)
      {
        $account = $accountAuthority->fkAccountSup;
        if($account)
        {
          $hksId = isset($account->green_action_unit_id)?$account->green_action_unit_id:null;
          $hksName = isset($account->green_action_unit_id)?$account->fkGreenActionUnit->name:null;
        }
        }
        $ret = [
          'access_token' => $modelAccessToken->access_token,
          'roles' => [$modelAccount->role],
           'lsgi'=>[
           'id'=>$lsgiId,
           'name'=>$lsgiName,
           ],
           'ward'=>[
           'id'=>isset($modelCustomerData->fkWard)?$modelCustomerData->fkWard->id:null,
           'name'=>isset($modelCustomerData->fkWard)?$modelCustomerData->fkWard->name:null,
           ],
           'hks'=>[
           'id'=>isset($hksId)?$hksId:null,
           'name'=>isset($hksName)?$hksName:null,
           ],
           'message'=>$message,
           'residential_association_number'=>isset($modelCustomerData->association_number)?$modelCustomerData->association_number:'',
           'residential_association_name'=>isset($modelCustomerData->fkAssociation)?$modelCustomerData->fkAssociation->name:'',
        ];
        return $ret;
        }
        else
        {
        $msg   = ['Invalid User'];
        $error = ['customer_phone' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
        }
      }
      else
      {
      if(!isset($params['ward_id']))
      {
        $msg   = ['Ward is mandatory'];
        $error = ['ward_id' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
      }
      if(!isset($params['password']))
      {
        $msg   = ['Password is mandatory'];
        $error = ['password' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
      }
        $modelAccount = new Account;
        $modelCustomer =  new Customer;
        $qry1 = "SELECT max(customer_id) as customer_id FROM `customer` where status=1 and ward_id=:ward_id and is_public_customer=1";
          $ward_id = $params['ward_id'];
          $command1 =  Yii::$app->db->createCommand($qry1);
          $command1->bindParam(':ward_id',$ward_id);
          $data1 = $command1->queryAll();
          $maxCount = $data1[0];
          $max = $maxCount['customer_id'];
          if($max):
          $modelCustomer->customer_id = $max+1; 
        else:
          $modelCustomer->customer_id = 1;
        endif;
        $modelCustomer->lead_person_phone = $params['customer_phone'];
        $modelCustomer->lead_person_name = $params['customer_name'];
        $modelCustomer->ward_id = $params['ward_id'];
        $modelCustomer->residential_association_id = isset($params['residential_association_id'])?$params['residential_association_id']:null;
        $modelCustomer->association_number = isset($params['association_number'])?$params['association_number']:null;
       $modelCustomer->is_public_customer = 1;
       $modelCustomer->sign_up = 1;
       $modelCustomer->status = 1;
       $modelCustomer->save(false);
       $modelAccount->username = $modelCustomer->lead_person_phone;
       $modelAccount->password_hash = Yii::$app->security->generatePasswordHash($params['password']);
       $modelAccount->person_id = 0;
       $modelAccount->role = 'cityzen';
       $modelAccount->customer_id = $modelCustomer->id;
       $modelAccount->save(false);
       $lsgiId = isset($modelCustomer->fkWard->fkLsgi)?$modelCustomer->fkWard->fkLsgi->id:null;
          $lsgiName = isset($modelCustomer->fkWard->fkLsgi)?$modelCustomer->fkWard->fkLsgi->name:null;

      $accountAuthority = $modelAccount->fkAccountAuthority;
      if($accountAuthority)
      {
        $account = $accountAuthority->fkAccountSup;
        if($account)
        {
          $hksId = isset($account->green_action_unit_id)?$account->green_action_unit_id:null;
          $hksName = isset($account->green_action_unit_id)?$account->fkGreenActionUnit->name:null;
        }
        }
        $modelAccessToken =  new AccessToken;
        $modelAccessToken->generateForUser($modelAccount);
       $ret = [
       'account_id'=>$modelAccount->id,
       'id'=>$modelCustomer->id,
       'access_token' => $modelAccessToken->access_token,
         'roles' => ['cityzen'],
           'lsgi'=>[
           'id'=>$lsgiId,
           'name'=>$lsgiName,
           ],
           'ward'=>[
           'id'=>isset($modelCustomer->fkWard)?$modelCustomer->fkWard->id:null,
           'name'=>isset($modelCustomer->fkWard)?$modelCustomer->fkWard->name:null,
           ],
           'hks'=>[
           'id'=>isset($hksId)?$hksId:null,
           'name'=>isset($hksName)?$hksName:null,
           ],
          'residential_association_number'=>isset($modelCustomer->association_number)?$modelCustomer->association_number:'',
           'residential_association_name'=>isset($modelCustomer->fkAssociation)?$modelCustomer->fkAssociation->name:'',
       ];
       return $ret;
      }

    }
    public function actionLogin() {
      $post = Yii::$app->request->post();
      foreach($post as $param => $val) ${$param} = $val;
      $username = isset($username)?$username:'';
      $password = isset($password)?$password:'';

      $modelLogin = new LoginForm;

      $modelLogin->setScenario('login');
      $modelLogin->username = $username;
      $modelLogin->password = $password;
      while(true) {
        if(!( $modelAccessToken = $modelLogin->login() ))  {
          $ret = ['errors' =>$modelLogin->errors];
          Yii::$app->response->statusCode = 401;
          break;
        }
        $modelUser = $modelLogin->getUserObj();
        $modelPerson = $modelUser->fkPerson;
        if($modelUser->role!='customer')
        {
           $modelLoginHistory = new LoginHistory;
           $modelLoginHistory->account_id = $modelUser->id ;
           $modelLoginHistory->login_datetime = date('Y-m-d H:i:s');
            $modelLoginHistory->role = $modelUser->role;
           $modelLoginHistory->save(false);
        }
        if($modelUser->role=='customer')
        {
          $lsgiId = isset($modelUser->fkCustomer->fkWard->fkLsgi)?$modelUser->fkCustomer->fkWard->fkLsgi->id:null;
          $lsgiName = isset($modelUser->fkCustomer->fkWard->fkLsgi)?$modelUser->fkCustomer->fkWard->fkLsgi->name:null;

      $accountAuthority = $modelUser->fkAccountAuthority;
      if($accountAuthority)
      {
        $account = $accountAuthority->fkAccountSup;
        if($account)
        {
          $hksId = isset($account->green_action_unit_id)?$account->green_action_unit_id:null;
          $hksName = isset($account->green_action_unit_id)?$account->fkGreenActionUnit->name:null;
        }
        }
        }
        else
        {
          $lsgiId = isset($modelUser->fkLsgi)?$modelUser->fkLsgi->id:null;
          $lsgiName = isset($modelUser->fkLsgi)?$modelUser->fkLsgi->name:null;

        }
		
         $ret = [    
           'access_token' => $modelAccessToken->access_token,
           'account_id' => $modelUser->id,
           'roles' => [$modelUser->role],
           'lsgi'=>[
           'id'=>$lsgiId,
           'name'=>$lsgiName,
           ],
           'ward'=>[
           'id'=>isset($modelUser->fkCustomer->fkWard)?$modelUser->fkCustomer->fkWard->id:null,
           'name'=>isset($modelUser->fkCustomer->fkWard)?$modelUser->fkCustomer->fkWard->name:null,
           ],
           'hks'=>[
           'id'=>isset($hksId)?$hksId:null,
           'name'=>isset($hksName)?$hksName:null,
           ],

         ];
		if($modelPerson) {
			$ret['name'] = $modelPerson->first_name?$modelPerson->first_name:'';
			$ret['email'] =  $modelPerson->email?$modelPerson->email:'';
			
		}

        $customerhash = "";

        break;
      }
       return $ret;
    }

    public function actionSendPasswordReset($email) {
      $modelUser = Account::find()->where(['username'=>$email])->andWhere(['status'=>1])->one();

      if($modelUser) {
        $modelUser->generatePasswordResetToken();
        $modelUser->generateUserToken();
        $modelUser->save(false);
        Yii::$app->otp->send($modelUser);
        $ret = [
            'user_token' => $modelUser->user_token,
            'api_token' => $modelUser->password_reset_token,
          ];
      } else {
          Yii::$app->response->statusCode = 404;
          $errors = [
              'message' =>"False"
            ];
          $ret =  $errors;
      }
      return $ret;
    }
    public function actionReset($apiToken,$userToken)
    {
      $modelAccount = Account::find()->where(['password_reset_token'=>$apiToken])->andWhere(['user_token'=>$userToken])->one();
      if($modelAccount)
      {
        $modelAccessToken =  new AccessToken;
        $modelAccessToken->generateForUser($modelAccount);
        $ret = [
          'access_token' => $modelAccessToken->access_token
        ];
      }
      else
      {
        $ret = [
          'message' => 'Invalid user token'
        ];
      }
    return $ret;

    }
     public function actionGenerateOtp()
    {
      $ret = [];
      $post = Yii::$app->request->post();
      foreach($post as $param => $val) ${$param} = $val;
        $username = isset($username)?$username:'';
        $user_token = substr(uniqid(rand(), true), 4, 4);
        $api_token = substr(uniqid(rand(), true), 1, 15);
        $expiry_timestamp = date('Y-m-d H:i:s', strtotime('+1 hours'));
        if(!$username)
        {
        $msg   = ['Username is mandatory'];
        $error = ['username' => $msg];
        $ret   = ['errors' => $error];
         return $ret;
        }
           $user = Account::find()->where(['username' => $username])->andWhere(['status' => 1])->andWhere(['is_banned'=>1])->one();
            if($user)
            {
                $user = $user;
                $modelCustomer = Customer::find()->where(['id'=>$user->customer_id])->andWhere(['status'=>1])->one();
            }
            else
            {
                $modelCustomer = Customer::find()->where(['lead_person_phone'=>$username])->andWhere(['status'=>1])->one();
                if($modelCustomer)
                {
                    $modelAccount = Account::find()->where(['status'=>1])->andWhere(['customer_id'=>$modelCustomer->id])->one();
                    if($modelAccount)
                    {
                        $user = $modelAccount;
                    }
                }
            }
        if(isset($user)&&isset($modelCustomer)){
        $modelUserToken =  new UserToken;
        $modelUserToken->api_token  =  $api_token;
        $modelUserToken->user_token  =  $user_token;
        $modelUserToken->expiry_timestamp  =  $expiry_timestamp;
        $modelUserToken->account_id  =  $user->id;
        if($modelUserToken->save(false))
        {
          $authKey = Yii::$app->params['authKeyMsg'];
          $phone = $modelCustomer->lead_person_phone;
          $content = "Your OTP is $user_token for greentrivandrum valid for 1 hour. Do not share this OTP with anyone";
          $countryCode = '91';
          $senderId = 'WMSMGMT';
          $msg = Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
          $ret = [
                'user_token' => $user_token,
                'api_token' => $api_token,
                'account_id' => $modelUserToken->account_id,
            ];
             return $ret;
        }
      }else
      {
         $modelUserToken =  new UserToken;
        $modelUserToken->api_token  =  $api_token;
        $modelUserToken->user_token  =  $user_token;
        $modelUserToken->expiry_timestamp  =  $expiry_timestamp;
        $modelUserToken->account_id  =  0;
        if($modelUserToken->save(false))
        {
          $authKey = Yii::$app->params['authKeyMsg'];
          $phone = $username;
          $content = "Your OTP is $user_token for greentrivandrum valid for 1 hour. Do not share this OTP with anyone";
          $countryCode = '91';
          $senderId = 'WMSMGMT';
          $msg = Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
          $ret = [
                'user_token' => $user_token,
                // 'api_token' => $api_token,
                // 'account_id' => $modelUserToken->account_id,
            ];
             return $ret;
        }
      }
     
    }
    public function actionVerifyOtp()
    {
      $base_url    = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
      $ret = [];
      $post = Yii::$app->request->post();
      $get = Yii::$app->request->get();
      $post = array_merge($post,$get);
      foreach($post as $param => $val) ${$param} = $val;
      $user_token = isset($user_token)?$user_token:''; 
      if(!$user_token)
      {
        $msg   = ['Otp is mandatory'];
        $error = ['user_token' => $msg];
        $ret   = [
        'otp_verified'=>0,
        'errors' => $error];
        return $ret;
      }
      $current_timestamp = date('Y-m-d H:i:s'); 
      $modelUserToken = UserToken::find()->where(['user_token'=>$user_token])->andWhere(['>','expiry_timestamp',$current_timestamp])->andWhere(['status'=>1])->one();
      if(!$modelUserToken)
      {
        $msg   = ['Otp is not valid'];
        $error = ['user_token' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
      }else
      {
        $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$modelUserToken->account_id])->one();
        if($modelAccount)
        {
        $modelAccessToken =  new AccessToken;
        $modelAccessToken->generateForUser($modelAccount);
         $modelCustomer = $modelAccount->fkCustomer;
      if($modelCustomer) {
        $name = isset($modelCustomer->lead_person_name)?$modelCustomer->lead_person_name:null;
        $phone1 = isset($modelCustomer->lead_person_phone)?$modelCustomer->lead_person_phone:null;
        $ward = isset($modelCustomer->ward_id)?$modelCustomer->getWard():null;
        $address = $modelCustomer->address;
        $image = $modelAccount->image_id?$modelAccount->getImageUrl():null;
      }else
      {
        $msg   = ['Invalid customer'];
        $error = ['account_id' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
      }
        $ret = [
          'access_token' => $modelAccessToken->access_token,
          'base_url'=>$base_url,
          'account_id'=> $modelAccount->id,
          'username'=> $modelAccount->username,
          'name'=> $name,
          'phone'=> $phone1,
          'address'=> $address,
          'house_name'=> isset($modelCustomer->building_name)?$modelCustomer->building_name:null,
          'ward'=> isset($modelCustomer->fkWard)?$modelCustomer->fkWard->name:null,
          'block'=> isset($modelCustomer->fkWard)?$modelCustomer->getLsgi():null,
          'profilePic'=> $image,
          'otp_verified'=>1,
        ];
        return $ret;
        }else
        {
        $msg   = ['Invalid user'];
        $error = ['user_token' => $msg];
        $ret   = [
        'otp_verified'=>1,
        'errors' => $error];
        return $ret;
        }
      }
    }
}
?>
