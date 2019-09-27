<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "account_gt".
 *
 * @property int $id
 * @property int $account_id_customer
 * @property int $account_id_gt
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class AccountSupervisor extends \yii\db\ActiveRecord
{
    public $customer_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_supervisor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id_customer', 'account_id_supervisor'], 'required'],
            [['account_id_customer', 'account_id_supervisor', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'account_id_customer' => Yii::t('app', 'Account Id Customer'),
            'account_id_gt' => Yii::t('app', 'Account Id Gt'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getSupervisor($hks)
    {
        $supervisor =  Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                // ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
                // ->leftjoin('green_action_unit_ward','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                ->leftjoin('account_ward','account_ward.account_id=account.id')
                ->andWhere(['account.green_action_unit_id'=>$hks])
                ->andWhere(['account.role' => 'supervisor'])
                ->all();
                // print_r($gt);die();
        return $supervisor;

    }
}
