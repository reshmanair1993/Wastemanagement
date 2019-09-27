<?php
namespace api\modules\v1\models;
use api\modules\v1\models\Account;

use Yii;
use yii\base\Model;



/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    // public $device_id;
    public $rememberMe = true;

    private $_user;
    public $redirect =null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username'], 'required','on' => 'login','message'=>'Please enter your username'],
            [['password'], 'required','on' => 'login','message'=>'Please enter your password'],
            [['redirect'],'safe'],
            [['username'],'required','on'=>'password-reset'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword','on'=>['login']],
        ];
    }

    public function mustExist($attribute,$params)
    {

          $user = $this->getUser();


    $ret  = $user==null?false:true;
    if(!$ret)
        $this->addError($attribute,'We could not find an account associated with '.$this->username);
    return  $ret;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {
            $user = $this->getUser();


            $modelAccessToken =  new AccessToken;
            $modelAccessToken->generateForUser($user);
            $this->loginFromUserObj($user);
            return $modelAccessToken;
        } else {
            return false;
        }
    }


    public function loginFromUserObj($userObj) {
      return Yii::$app->user->login($userObj, $this->rememberMe ? 3600 * 24 * 60 : 0);
    }
    public function scenarios() {
        return [
          'login-from-frontend'  => ['username','password','redirect'],
          'login' => ['username','password'],
          'password-reset' => ['username']
        ];
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    // protected function getUser()
    // {

    //     if ($this->_user === null) {
    //         $user = Account::find()->where(['username' => $this->username])->andWhere(['status' => 1])->andWhere(['is_banned'=>1])->one();

    //         if (!$user || !$user->validatePassword($this->password)) {
    //         } else {
    //           $this->_user = $user;
    //         }
    //     }
    //     return $this->_user;
    // }
     protected function getUser()
    {
        if ($this->_user === null) {

            $user = Account::find()->where(['username' => $this->username])->andWhere(['status' => 1])->andWhere(['is_banned'=>1])->one();
            if($user)
            {
                $user = $user;
            }
            else
            {
                $modelCustomer = Customer::find()->where(['lead_person_phone'=>$this->username])->andWhere(['status'=>1])->one();
                if($modelCustomer)
                {
                    $modelAccount = Account::find()->where(['status'=>1])->andWhere(['customer_id'=>$modelCustomer->id])->one();
                    if($modelAccount)
                    {
                        $user = $modelAccount;
                    }
                }
            }
            if (!$user || !$user->validatePassword($this->password)) {
            } else {
              $this->_user = $user;
            }
        }
        return $this->_user;
    }

    public function getUserObj() {
      return $this->_user;
    }
}
