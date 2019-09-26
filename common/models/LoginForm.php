<?php
namespace common\models;

use Yii;
use yii\base\Model;
use api\modules\v1\models\Account;
use api\modules\v1\models\Customer;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
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
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    // protected function getUser()
    // {
    //     if ($this->_user === null) {
    //         $this->_user = User::findByUsername($this->username);
    //     }

    //     return $this->_user;
    // }
    //  protected function getUser()
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
    // public function getUserData() {
    //   $user = Account::find()->where(['username' => $this->username])->andWhere(['status' => 1])->andWhere(['is_banned'=>1])->one();
    //   if($user)
    //     return $user;
    // else
    //     return $this->_user;
    // }
    public function getUserData() {
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
            if($user)
        return $user;
    else
        return $this->_user;
    }
}
