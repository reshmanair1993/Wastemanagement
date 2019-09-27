<?php
namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tb_user".
 *
 * @property integer $id
 * @property integer $is_verified
 * @property integer $fk_company
 * @property integer $fk_person
 * @property integer $type
 * @property integer $status
 * @property integer $is_baned
 * @property integer $is_email_verified
 * @property integer $is_phone_verified
 * @property string $username
 * @property string $password
 * @property string $password_reset_token
 * @property string $user_token
 * @property string $activation_token
 * @property string $auth_key
 * @property string $password_token_expiry
 * @property string $created_at
 * @property string $modified_at
 */

class Account extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ROLE_GT        		  = 'green-technician';
    // public $password_hash;
    /**
     * @var mixed
     */
    public $password_repeat;
    /**
     * @var mixed
     */
    public $old_password;
    /**
     * @var mixed
     */
    public $password;
    // public $role;
    /**
     * @inheritdoc
     */
    public function incrementalHash(
        $len,
        $charset
    )
    {
        $base   = strlen($charset);
        $result = '';
        $now    = explode(' ', microtime())[1];
        while ($now >= $base)
        {
            $i      = $now % $base;
            $result = $charset[$i] . $result;
            $now /= $base;
        }

        return substr($result, -5);
    }

    public function generatePasswordResetToken()
    {
        $len                        = 5;
        $charset                    = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $this->password_reset_token = $this->incrementalHash($len, $charset);
    }

    public function generateUserToken()
    {
        $len              = 7;
        $charset          = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $this->user_token = $this->incrementalHash($len, $charset);
    }

    public static function tableName()
    {
        return 'account';
    }

    /**
     * @param $id
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken(
        $token,
        $type = null
    )
    {
        $ret       = null;
        $currentTs = date('Y-m-d H:i:s');
        $data      = Yii::$app->jwt->decode($token);
        if ($data)
        {
            $decoded_array = (array) $data;
            $userId        = $decoded_array['uid'];
            $modelUser     = Account::find()->where(['id' => $userId])->andWhere(['status' => 1])->one();
            if ($modelUser)
            {
                $ret = $modelUser;
            }
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkImageUrl()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        $ret        = null;
        $modelImage = $this->fkImageUrl;
        if ($modelImage)
        {
            $ret = $modelImage->uri_full;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return mixed
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     */
    public function validatePassword($password)
    {
        // print_r($this);die();
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('name', $name);
        $session->close();
    }

    /**
     * @param $username
     * @return mixed
     */
    public static function findByUsername($username)
    {
        $ret = static::find()->where(['username' => $username])->andWhere(['type' => 1])->andWhere(['status' => 1])->one();

        return $ret;
    }

    /**
     * @param $insert
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if ($this->isNewRecord)
            {
                $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required', 'on' => ['upgradePackage']],
            [['password_hash'], 'required', 'on' => ['create']],
            [['id'], 'required', 'on' => ['update']],
            [['phone'], 'existing_or_unique', 'on' => ['update']],
            [['id'], 'required', 'on' => ['email_update', 'password_change']],
            [['status', 'fk_person'], 'integer'],
            [['current_credit_balance'], 'number'],
            [['created_at', 'modified_at', 'role', 'type', 'auth_key', 'share_code', 'package_expiry', 'customer_id', 'parent_account_id','image_id'], 'safe'],
            [['username', 'password_hash'], 'string', 'max' => 255],
            [['fk_person'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fk_user_person' => 'id']],
            [['password', 'password_repeat', 'old_password'], 'required', 'on' => ['changeExistingPassword']]
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return mixed
     */
    public function existingOrUnique(
        $attribute,
        $params
    )
    {
        $ok = $this->find()->where([$attribute => $this->{$attribute}])->andWhere(['!=', 'id', $this->id])->one() == null ? true : false;

        if (!$ok)
        {
            $this->addError($attribute, "The $attribute is already taken ");
        }

        return $ok;
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function newAndUnique(
        $attribute,
        $params
    )
    {
        $err = $this->find()->where(['username' => $this->username])->andWhere(['!=', 'id', $this->id])->one() != null ? true : false;
        if ($err)
        {
            $this->addError($attribute, 'The email is already taken ');
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function oldPasswordShouldMatch(
        $attribute,
        $params
    )
    {
        $data = $this->find()->where(['id' => $this->id])->one();

        $ok = Yii::$app->security->validatePassword($this->old_password, $data->password);

        if (!$ok)
        {
            $this->addError($attribute, 'Incorrect old password');
        }
    }

    public function scenarios()
    {
        return [
            'changeExistingPassword' => ['password', 'password_repeat', 'old_password'],
            'createAdmin'            => ['username', 'password', 'password_repeat', 'role'],
            'updateAdmin'            => ['username', 'password', 'password_repeat', 'role'],
            'email_update'           => ['username', 'id'],
            'upgradePackage'         => ['id', 'fk_user_package'],
            'password_change'        => ['password', 'password_repeat', 'id'],
            'create'                 => ['password_hash', 'password_repeat', 'fk_user_person', 'id', 'username', 'type', 'share_code'],
            'update'                 => ['password', 'password_repeat', 'fk_user_person', 'id', 'username', 'status', 'type']
        ];
    }

    public function validateUsername()
    {
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => Yii::t('app', 'ID'),
            'username'       => Yii::t('app', 'Username'),
            'password'       => Yii::t('app', 'Password'),
            'status'         => Yii::t('app', 'Status'),
            'created_at'     => Yii::t('app', 'Ts Created At'),
            'modified_at'    => Yii::t('app', 'Ts Modified At'),
            'fk_user_person' => Yii::t('app', 'Fk User Person')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkCompany()
    {
        return $this->hasOne(Company::className(), ['fk_account' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return mixed
     */
    public function getFkLsgi()
    {
        return $this->hasOne(Lsgi::className(), ['id' => 'lsgi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountDevices()
    {
        return $this->hasMany(AccountDevice::className(), ['fk_account' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountHapps()
    {
        return $this->hasMany(AccountHapp::className(), ['fk_account' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountProviders()
    {
        return $this->hasMany(AccountProvider::className(), ['fk_account' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['fk_account' => 'id']);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function toggleStatus($id)
    {
        $connection = Yii::$app->db;
        $colVal     = $connection->createCommand('SELECT status from tb_user WHERE id =:id')->bindParam(':id', $id)->queryOne();
        if (!$colVal)
        {
            return -1;
        }

        $status = $colVal['status'];
        $status = $status == 1 ? 0 : 1;

        $connection->createCommand()->update('tb_user', ['status' => $status], 'id=:id')->bindParam(':id', $id)->execute();

        return $status;
    }

    /**
     * @return mixed
     */
    public function getListinguser()
    {
        $query = new Query;
        $model = Yii::$app->db->createCommand('SELECT first_name from tb_person join tb_user on tb_person.id= tb_user.fk_user_person join tb_listing on tb_listing.fk_listing_user = tb_user.id')
        ->queryOne();
        $data = $model['first_name'];

        return $data;
    }

    /**
     * @param $id
     * @param $data
     */
    public function updateUser(
        $id,
        $data
    )
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->update('tb_person', $data, 'id=:id')->bindParam(':id', $id)->execute();

        return true;
    }

    /**
     * @param $id
     * @param $new_pass
     */
    public function changepassword(
        $id,
        $new_pass
    )
    {
        $connection = Yii::$app->db;
        $new_pass   = ['password' => $new_pass];
        $connection->createCommand()->update('tb_user', $new_pass, 'fk_user_person=:id')->bindParam(':id', $id)->execute();

        return true;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function verifyAccount($id)
    {
        $connection = Yii::$app->db;
        $colVal     = $connection->createCommand('SELECT account_verfied from tb_user WHERE fk_user_person =:id')->bindParam(':id', $id)->queryOne();

        if (!$colVal)
        {
            return -1;
        }

        $account_verfied = $colVal['account_verfied'];
        $account_verfied = $account_verfied == 1 ? 0 : 1;

        $connection->createCommand()->update('tb_user', ['account_verfied' => $account_verfied], 'fk_user_person=:id')->bindParam(':id', $id)->execute();

        return $account_verfied;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function verifyPhone($id)
    {
        $connection = Yii::$app->db;
        $colVal     = $connection->createCommand('SELECT phone_verified from tb_user WHERE fk_user_person =:id')->bindParam(':id', $id)->queryOne();

        if (!$colVal)
        {
            return -1;
        }

        $phone_verified = $colVal['phone_verified'];
        $phone_verified = $phone_verified == 1 ? 0 : 1;

        $connection->createCommand()->update('tb_user', ['phone_verified' => $phone_verified], 'fk_user_person=:id')->bindParam(':id', $id)->execute();

        return $phone_verified;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function activateUser($id)
    {
        $connection = Yii::$app->db;
        $colVal     = $connection->createCommand('SELECT status from tb_user WHERE id =:id')->bindParam(':id', $id)->queryOne();

        if (!$colVal)
        {
            return -1;
        }

        $status = $colVal['status'];
        $status = $status == 1 ? 0 : 1;

        $connection->createCommand()->update('tb_user', ['status' => $status], 'id=:id')->bindParam(':id', $id)->execute();

        return $status;
    }

    public function behaviors()
    {
        return [
            [

                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value'              => new Expression('NOW()')
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function insertNew()
    {
        $model = Yii::$app->utilities->cloneModel(Account::className(), $this);
        $model->save(false);
        $userId = $model->id;
        $ret    = $userId ? $model : null;

        return $ret;
    }

    /**
     * @param $modelPerson
     * @return mixed
     */
    public function insertFromPersonIfNotExisting($modelPerson)
    {
        // checks whether a person with same email exists and inserts if not
        $user = $this->find()->where(['username' => $modelPerson->email])->one(); //current record

        while (1)
        {
            if (!$user)
            {
                //user exists already
                $modelPerson->setScenario('createdFromListing');
                $modelPerson->status              = 1;
                Yii::$app->formatter->nullDisplay = ''; // date will show error if null.
                $modelPerson                      = $modelPerson->insertNew();
                if (!$modelPerson)
                {
                    break;
                }

                $this->username        = $modelPerson->email;
                $this->password        = sha1(time());
                $this->fk_user_person  = $modelPerson->id;
                $this->account_verfied = 0;
                $this->phone_verified  = 0;
                $this->status          = 1;
                $user                  = $this->insertNew();
            }
            break;
        }

        return $user;
    }

    /**
     * @param $packageId
     */
    public function upgradePackage($packageId)
    {
        $this->fk_user_package = $packageId;
        $expiry                = date('Y-m-d H:i:s', strtotime("+360 day"));
        $this->package_expiry  = $expiry;
        $this->update(false);
    }

    /**
     * @param $keyword
     * @return mixed
     */
    public function getAllQuery($keyword = null)
    {
        $query = Account::find()->andWhere(['!=', 'id', Yii::$app->user->id])->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC]);
        if ($keyword)
        {
            $query = $query->andWhere(['LIKE', 'username', $keyword]);
        }

        return $query;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getFcmTokens($id)
    {
        $data = AccountMobileMap::find()->where(['account_id' => $id])->andWhere(['status' => 1])->all();

        return $data;
    }
    public function getFkGreenActionUnit()
        {
                return $this->hasOne(GreenActionUnit::className(), ['id' => 'green_action_unit_id']);
        }

    /**
     * @return mixed
     */
    public function getFkCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
    public function getFkAccountAuthority()
    {
        return $this->hasOne(AccountGt::className(), ['account_id_customer' => 'id'])->andWhere(['status'=>1]);
    }
     public function getFkServiceRequest()
    {
        return $this->hasOne(ServiceRequest::className(), ['account_id_customer' => 'id']);
    }

    /* public function change_pass($old_pass)
{
$connection = Yii::$app->db;
$connection->createCommand()->update('tb_user', ['password' => $old_pass], 'password=:old_pass')->bindParam(':old_pass',$old_pass)->execute();
echo "tes";

// return $phone_verified;
} */
}
/* $connection = Yii::$app->db;
$connection->createCommand()->update('user', ['sha_password' => $password], 'id='.$id)->execute(); */
