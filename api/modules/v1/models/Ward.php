<?php

namespace api\modules\v1\models;


use Yii;

/**
 * This is the model class for table "ward".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $isgi_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Ward extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ward';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'lsgi_id'], 'required'],
            [['lsgi_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name', 'code'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'lsgi_id' => Yii::t('app', 'Lsgi'),
            'fkLsgi.name' => Yii::t('app', 'Lsgi'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery() {
      return static::find()->where(['status'=>1])->orderby('ward.sort_order ASC');
    }
    public function deleteWard($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('ward', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkLsgi()
    {
        return $this->hasOne(Lsgi::className(), ['id' => 'lsgi_id']);
    }
     public function getIsgi()
    {
        $isgi =  Lsgi::find()->where(['status'=> 1])->all();
        return $isgi;
    }
}
