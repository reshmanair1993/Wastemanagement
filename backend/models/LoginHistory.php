<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "memo_penalty".
 *
 * @property int $id
 * @property int $memo_type_id
 * @property int $lsgi_id
 * @property double $amount
 * @property int $status
 * @property string $created_at
 * @property string $modified_At
 */
class LoginHistory extends \yii\db\ActiveRecord
{
  public $type;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id'], 'integer'],
            [['login_datetime','role'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }
    public static function getAllQuery($keyword=null,$type=null,$from=null,$to=null)
    {
      // print_r($type);die();
      $query = static::find()
      // ->groupBy('account_id')
      ;
      if($keyword)
      {
        $query->leftjoin('account','account.id=login_history.account_id')
        ->leftjoin('person','account.person_id=person.id')
        ->andFilterWhere(['or', ['LIKE', 'person.first_name', $keyword], ['LIKE', 'person.middle_name', $keyword], ['LIKE', 'person.last_name', $keyword]]);
      }
       if($type)
      {
        $query->andWhere(['login_history.role'=>$type]);
      }
      if($from!=null)
        {
            $query->andWhere(['>=', 'login_history.login_datetime', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'login_history.login_datetime', $to]);
        }

      return $query;
    }
    public function getFkAccount()
    {
      return $this->hasOne(Account::classname(), ['id'=> 'account_id']);
    }
}
