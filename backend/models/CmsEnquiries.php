<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cms_enquiries".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CmsEnquiries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_enquiries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['message'], 'string'],
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name', 'email', 'phone'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'message' => Yii::t('app', 'Message'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteEnquiry($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('cms_enquiries', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
