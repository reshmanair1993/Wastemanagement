<?php

namespace backend\models;


use Yii;

/**
 * This is the model class for table "generate_chalan".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $address
 * @property string $subject
 * @property string $description
 * @property double $amount
 * @property int $incident_id
 * @property int $account_id
 */
class GenerateChalan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generate_chalan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'amount'], 'required'],
            [['amount'], 'number'],
            [['incident_id', 'account_id'], 'integer'],
            [['name', 'email', 'address', 'subject', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'address' => 'Address',
            'subject' => 'Subject',
            'description' => 'Description',
            'amount' => 'Amount',
            'incident_id' => 'Incident ID',
            'account_id' => 'Account ID',
        ];
    }
}
