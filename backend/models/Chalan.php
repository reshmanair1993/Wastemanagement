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
class Chalan extends \yii\db\ActiveRecord
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
    public function getIncident($id){
      $modelIncident= Incident::find()->where(['id' => $id,'status'=>1])->one();
      // print_r($modelIncident);exit;
      if($modelIncident){
        $modelIncidentType = IncidentType::find()->where(['id'=>$modelIncident->incident_type_id,'status'=>1])->one();
        return $modelIncidentType;
      }
    }
    public function getFkImage()
    {
        $modelImage = Image::find()
        ->innerJoin('lsgi','lsgi.image_id=image.id')
        ->innerJoin('ward','ward.lsgi_id = lsgi.id')
        ->innerJoin('camera','camera.id = ward.id')
        ->innerJoin('incident','incident.camera_id = camera.id')
        ->where(['image.status' =>1])
        ->andWhere(['ward.status' =>1,'camera.status' =>1,'incident.status' =>1,'lsgi.status' =>1]);
        // return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status' => 1]);
    }
    public function getLsgi($id)
    {

        $modelIncident = Incident::find()->where(['id'=>$id])->one();
        $modelCamera = Camera::find()->where(['id'=>$modelIncident->camera_id])->one();
        $modelWard = Ward::find()->where(['id'=>$modelCamera->ward_id])->one();
        $modelLsgi = Lsgi::find()->where(['id'=>$modelWard->lsgi_id])->one();
        return $modelLsgi;
        // print_r($modelCamera);exit;
        // $modelLsgi = Lsgi::find()
        // ->innerJoin('ward','ward.lsgi_id = lsgi.id')
        // ->innerJoin('camera','camera.id = ward.id')
        // ->innerJoin('incident','incident.camera_id = camera.id')
        // ->innerJoin('chalan','chalan.incident_id=incident.id')
        // ->where(['lsgi.status' =>1])
        // ->andWhere(['chalan.id' => $id])
        // ->andWhere(['ward.status' =>1,'camera.status' =>1,'incident.status' =>1,'chalan.status' =>1]);
        // return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status' => 1]);
    }
}
