<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "evaluation_config_completion_time".
 *
 * @property int $id
 * @property int $lsgi_id
 * @property double $start_value_minutes
 * @property double $end_value_minutes
 * @property double $performance_point
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class EscalationSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'escalation_setttings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['complaint_escalation_min', 'service_escalation_min', 'role'], 'required'],
            [['lsgi_id', 'status'], 'integer'],
            [['complaint_escalation_min', 'service_escalation_min'], 'number'],
             [['service_escalation_min'],'new_and_unique','on'=>'create'],
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
            'lsgi_id' => Yii::t('app', 'Lsgi ID'),
            'start_value_minutes' => Yii::t('app', 'Start Value Minutes'),
            'end_value_minutes' => Yii::t('app', 'End Value Minutes'),
            'performance_point' => Yii::t('app', 'Performance Point'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function deleteEscalationSettings($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('escalation_setttings', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function new_and_unique($attribute,$params)
    {
        $err  = EscalationSettings::find()->where(['role'=>$this->role])->andWhere(['status'=>1])->one();
        if($err)
        $this->addError($attribute,'Data already taken ');
    }
    public static function getAllQuery()
    {
        return static::find()->where(['status'=> 1]);
    }
    // public function escalation($params,$keyword=null,$service=null,$gt=null,$from=null,$to=null,$type=null,$status=null,$ward=null,$newParams=null)
    // {
    //     $supervisor = null;
    //     $unit = null;
    //     $agency = null;
    //     $lsgi = null;
    //     $ward = null;
    //     $gt   = null;
    //     $modelUser  = Yii::$app->user->identity;
    //     $userRole = $modelUser->role;
    //     $associations = Yii::$app->rbac->getAssociations($modelUser->id);
    //     if(isset($associations['lsgi_id']))
    //     {
    //         $lsgi = $associations['lsgi_id'];
    //     }
    //     if(isset($associations['district_id']))
    //     {
    //         $district = $associations['district_id'];
    //     }
    //     if(isset($associations['district_id']))
    //     {
    //         $district = $associations['district_id'];
    //     }
    //     if(isset($associations['ward_id']))
    //     {
    //         $ward = $associations['ward_id'];
    //         $ward = json_decode($ward);
    //     }
    //     if(isset($associations['hks_id']))
    //     {
    //         $unit = $associations['hks_id'];
    //     }
    //     if(isset($associations['gt_id']))
    //     {
    //         $gt = $associations['gt_id'];
    //     }
    //     if(isset($associations['survey_agency_id']))
    //     {
    //         $agency = $associations['survey_agency_id'];
    //     }
    //     $dateToday= date('Y-m-d H:i:s');
    //     $from_date =$from?\Yii::$app->formatter->asDatetime($from, "php:Y-m-d"):'';
    //    $to_date = $to?\Yii::$app->formatter->asDatetime($to, "php:Y-m-d"):'';
    //     $query = ServiceRequest::find()->where(['service_request.status'=>1])
    //     ->leftJoin('service','service.id=service_request.service_id')
    //     ->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
    //     ->leftjoin('lsgi','lsgi.id=service_request.lsgi_id')
    //     ->leftjoin('escalation_settings','lsgi.id=escalation_settings.lsgi_id')
    //       ->andWhere(['service_assignment.servicing_status_option_id'=>null])
    //       // ->andWhere([date_diff($dateToday,'service_request.created_at')*1440>'escalation_settings.service_escalation_min'])
    //       ->andWhere(['escalation_settingsk.role'=>$userRole])
    //     ->orderby('id DESC');


    //     if($modelUser->role=='customer')
    //     {
    //         $query->andWhere(['service_request.account_id_customer'=>$modelUser->id]);
    //     }
    //     if($type)
    //     {
    //       $query->andWhere(['service.type'=>$type]);
    //     }
    //     if($userRole=='supervisor'&&isset($modelUser->id))
    // {
    //   $supervisor = $modelUser->id;
    //   // $unit = $modelUser->green_action_unit_id;
    // }
    // if($supervisor)
    // {
    //   $query
    //   ->leftjoin('account_authority','account_authority.account_id_customer=service_request.account_id_customer')
    //   ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id]);
    // }
    //     if($keyword||$ward||$unit||$lsgi)
    //     {
    //         $query->leftjoin('customer','customer.id=service_request.account_id_customer');
    //         if($lsgi!=null)
    //         {
    //           $query->leftjoin('ward','ward.id=customer.ward_id')
    //         ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
    //         -> andWhere(['lsgi.id'=>$lsgi]);
    //         }
    //         if($keyword){
    //            $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
    //         }
    //         if($ward!=null)
    //         {
    //           // $query->andWhere(['customer.ward_id'=>$ward]);
    //           $query->andWhere(['service_request.ward_id'=>$ward]);
    //         }
    //         if($unit!=null)
    //     {
    //         $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
    //         ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
    //     }
            
    //     }
    //     if($service)
    //     {
    //         $query
    //         ->andWhere(['service.id'=>$service]);
    //     }
    //     if(($from_date&&$to_date)){
    //     $query->andWhere(['>=', 'service_request.requested_datetime',$from_date])
    //     ->andWhere(['<=', 'service_request.requested_datetime',$to_date]);
    //   }
    //   if($from_date){
    //     $query->andWhere(['>=', 'service_request.requested_datetime',$from_date]);
    //   }
    //   if($to_date){
    //     $query->andWhere(['<=', 'service_request.requested_datetime',$to_date]);
    //   }
     
    //     // add conditions that should always apply here

    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $query,
    //         'pagination' => [
    //       'pageSize' => 50, 
    //   // 'params' =>  $newParams
    //     ],
    //     ]);

    //     $this->load($params);

    //     if (!$this->validate()) {
    //         // uncomment the following line if you do not want to return any records when validation fails
    //         // $query->where('0=1');
    //         return $dataProvider;
    //     }

    //     // grid filtering conditions
    //     $query->andFilterWhere([
    //         'id' => $this->id,
    //         'service_id' => $this->service_id,
    //         'account_id_customer' => $this->account_id_customer,
    //         'status' => $this->status,
    //         'created_at' => $this->created_at,
    //         'modified_at' => $this->modified_at,
    //     ]);

   

    //     return $dataProvider;
    // }
     
}
