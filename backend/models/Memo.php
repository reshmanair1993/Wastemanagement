<?php

namespace backend\models;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;


use Yii;

/**
 * This is the model class for table "generate_Memo".
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
class Memo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'memo';
    }
    /**
     * {@inheritdoc}
     */
    // const SCENARIO_SEARCH = 'search';
    public $date_to, $date_from,$incident_type_id,$ward_id;
    public function scenarios()
    {
      return [
                self::SCENARIO_DEFAULT => [
                'name', 'email', 'amount','memo_type_id','lsgi_authorized_signatory_id','subject', 'description','address'
                ],
                'search' => ['id']
              ];
    }
    public function rules()
    {
        return [
            [['name', 'email', 'amount','memo_type_id','lsgi_authorized_signatory_id','subject', 'description','address'], 'required'],
            [['amount'], 'number'],
            [['incident_id', 'account_id','memo_type_id','lsgi_authorized_signatory_id','lsgi_id','payment_counter_id','payment_counter_account_id'], 'integer'],
            [['name', 'email', 'address', 'subject', 'description'], 'string', 'max' => 255],
            // [['id'],'check_memo_exist'],
            [['id'],'check_memo_exist','on' =>'search'],
        ];
    }

    public function check_memo_exist()
    {
      if($this->id == ''){
        $this->addError('id','Not Found');
      }
      else {
         $memos = static::find()->where(['status'=>1])
         ->andWhere(['id'=>$this->id])->one();
         if($memos == ''){
           $this->addError('id','This id not exist');
         }
      }
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Memo Id',
            'name' => 'Name',
            'email' => 'Email',
            'address' => 'Address',
            'subject' => 'Subject',
            'description' => 'Description',
            'amount' => 'Amount',
            'incident_id' => 'Incident ID',
            'account_id' => 'Account ID',
            'memo_type_id' =>'Memo Type',
            'lsgi_authorized_signatory_id' => 'Lsgi Authorized Signatory'
        ];
    }
    public function getPaymentCounterAdmin(){
      $modelAccount = Person::find()
      ->leftjoin('account','account.person_id = person.id')
      ->where(['account.role' => "payment-counter-admin"])
      ->andwhere(['account.status' => 1,'person.status'=>1])->all();
      return $modelAccount;
    }
    public function searchCounterWise($params,$counter=null,$user=null)
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;
      if($userRole == 'admin-lsgi'){
        // print_r($modelUser->lsgi_id);exit;
        $query = Memo::getAllQuery()->where(['lsgi_id' => $modelUser->lsgi_id, 'status' =>1, 'is_paid'=>1])->andwhere(['not', ['payment_counter_id' => null]]);
      }
      else{
        $query = Memo::getAllQuery()->where(['status' =>1, 'is_paid'=>1])->andwhere(['not', ['payment_counter_id' => null]]);
      }

        if($counter!=null)
        {
          $query
          ->andWhere(['memo.payment_counter_id'=>$counter]);

        }
        if($user != null){
          // print_r($user);exit;
          $query
          ->leftjoin('account','account.id = memo.payment_counter_account_id')
          // ->leftJoin('person','person.id = account.person_id')
          ->where(['account.status'=>1,'memo.status'=> 1])
          // ->andWhere(['person.id' => $user]);
          ->andWhere(['account.id' => $user]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }
    public function findPaymentCounterAdmin($counter_id){
    $paymentCounterAdmins = PaymentCounterAccount::find()->where(['payment_counter_id' => $counter_id,'status' => 1])->all();
    $modelAccount = [];
    if($paymentCounterAdmins){
      foreach ($paymentCounterAdmins as $paymentCounterAdmin) {
        $modelAccount[] = Account::find()->where(['id' => $paymentCounterAdmin->account_id])->one();
      }
    }
    return $modelAccount;
    }
    public function searchOutstandingPayment($params,$lsgi=null,$ward=null)
    {
      $modelUser  = Yii::$app->user->identity;
      $userRole  = $modelUser->role;
      $associations = Yii::$app->rbac->getAssociations($modelUser->id);
      if(isset($associations['lsgi_id']))
      {
          $lsgi = $associations['lsgi_id'];
      }
      if(isset($associations['ward_id']))
      {
          $ward = $associations['ward_id'];
      }
        $query = Memo::getAllQuery()
          ->innerJoin('incident','incident.id = memo.incident_id')
          ->where(['memo.status' =>1,'incident.status' =>1, 'is_paid'=>0]);
        if($lsgi!=null)
        {
          $query->where(['memo.lsgi_id'=>$lsgi,'memo.status' =>1, 'memo.is_paid'=>0,'incident.status' =>1]);
        }
        if($ward!=null)
        {
          $query
          ->innerJoin('camera','camera.id = incident.camera_id')
          ->where(['camera.ward_id'=>$ward,'camera.status'=>1,'memo.status' =>1, 'memo.is_paid'=>0,'incident.status' =>1]);
        }
        // if($ward!=null)
        // {
        //   $query
        //   ->leftjoin('incident','incident.id = memo.incident_id')
        //   ->leftjoin('camera','camera.id = incident.camera_id')
        //   ->leftJoin('ward','ward.id=camera.ward_id')
        //   ->where(['ward.id'=>$ward])
        //   ->andWhere(['ward.status'=>1,'camera.status'=>1,'incident.status'=>1]);
        // }
        // if($lsgi!=null&&$ward!=null)
        // {
        //     $query
        //     ->leftjoin('incident','incident.id = memo.incident_id')
        //     ->leftjoin('camera','camera.id = incident.camera_id')
        //     ->leftjoin('ward','ward.id=camera.ward_id')
        //     ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
        //     ->where(['camera.ward_id' => $ward])
        //     ->andWhere(['incident.status' =>1, 'camera.status' =>1, 'ward.status'=>1, 'lsgi.status' => 1]);
        // }
        //  if($district!=null&&$lsgi!=null)
        // {
        //     $query->innerJoin('camera','camera.id=incident.camera_id')
        //     ->leftjoin('ward','ward.id=incident.ward_id')
        //     ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
        //     ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
        //     ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
        //     ->leftjoin('district','district.id=assembly_constituency.district_id')
        //     ->andWhere(['district.id'=>$district]);
        // }
        // if($district!=null&&$lsgi==null)
        // {
        //     $query->innerJoin('camera','camera.id=incident.camera_id')
        //     ->leftjoin('ward','ward.id=incident.ward_id')
        //     ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
        //     ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
        //     ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
        //     ->leftjoin('district','district.id=assembly_constituency.district_id')
        //     ->andWhere(['district.id'=>$district]);
        // }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
    public function search($params,$keyword=null)
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;

      if($userRole == 'admin-lsgi'){
        $query = Memo::find()
        ->leftJoin('incident','incident.id = memo.incident_id')
        ->where(['memo.status'=>1,'incident.status'=>1,'memo.lsgi_id' =>$modelUser->lsgi_id])
        ->orderby('memo.id DESC');
      }
      elseif ($userRole == 'payment-counter-admin') {

        // $paymentCounters = PaymentCounterAccount::find()->where(['account_id' => $modelUser->id,'status'=> 1])->all();
        // print_r($paymentCounters);exit;
        $query = Memo::find()
        ->leftJoin('payment_counter','payment_counter.lsgi_id = memo.lsgi_id')
        ->leftJoin('payment_counter_account','payment_counter_account.payment_counter_id = payment_counter.id')
        ->innerJoin('incident','incident.id = memo.incident_id')
        ->where(['memo.lsgi_id' => $modelUser->lsgi_id])
        ->andWhere(['incident.status'=>1,'memo.status'=> 1,'payment_counter.status'=> 1,'payment_counter_account.status'=> 1])
        ->orderby('memo.id DESC');
      }
      else {

        $query = Memo::find()
        ->leftJoin('incident','incident.id = memo.incident_id')
        ->where(['memo.status'=>1,'incident.status'=>1])
        ->orderby('memo.id DESC');
      }

        if($keyword!=null)
        {
            $query->andFilterWhere(['memo.id' => $keyword]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
   //  public static function getAllQuery() {
   //   $query = static::find()->where(['status'=>1]);
   //   if($memo)
   //   {
   //      $query->andWhere(['memo.id'=>$memo]);
   //   }
   //   return $query;
   // }
   // public function check_memo_exist($attribute){
   //   $memoId = $this->attribute;
   //   $memos = static::select(['id'])->find()->where(['status'=>1])->all();
   //   print_r($memos);exit;
   //   if (in_array("100", $memos))
   //   {
   //   echo "found";
   //   }
   //  else
   //   {
   //   echo "not found";
   //   }
   // }
   public static function getAllQuery()
   {
     $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
     return $query;
   }

  //  public function getIncidentsCount($id = null,$from=null,$to=null)
  // {
  //     $count  = 0;
  //     $incidents = Incident::find()
  //     ->leftjoin('camera','camera.id=incident.camera_id')
  //     ->leftjoin('ward','ward.id=camera.ward_id')
  //     ->where(['incident.status' => 1])
  //     ->andWhere(['ward.id'=>$id])
  //     ->andWhere(['camera.status' =>1,'ward.status'=> 1]);
  //       // $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1]);
  //     // $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1]);
  //     // $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1])->all();
  //     if($from!=null)
  //     {
  //         $incidents->andWhere(['>=', 'incident.created_at', $from]);
  //     }
  //     if($to!=null)
  //     {
  //         $incidents->andWhere(['<=', 'incident.created_at', $to]);
  //     }
  //     $incidents=$incidents->all();
  //     if ($incidents)
  //     {
  //         $count = count($incidents);
  //     }
  //
  //     return $count;
  // }

   public function getPaidMemoCount($id = null,$from=null,$to=null)
  {
      $count  = 0;
      $modelMemos = Memo::find()
      ->leftjoin('incident','incident.id = memo.incident_id')
      ->leftJoin('camera','camera.id = incident.camera_id')
      ->leftjoin('ward','ward.id=camera.ward_id')
      ->where(['memo.status' => 1])
      ->andWhere(['ward.id'=>$id])
      ->andWhere(['camera.status' =>1,'ward.status'=> 1,'incident.status' =>1])
      ->andWhere(['memo.is_paid' => 1]);

      if($from!=null)
      {
          $modelMemos->andWhere(['>=', 'memo.payment_date', $from]);
      }
      if($to!=null)
      {
          $modelMemos->andWhere(['<=', 'memo.payment_date', $to]);
      }
      $modelMemos=$modelMemos->all();
      if ($modelMemos)
      {
          $count = count($modelMemos);
      }
      return $count;
  }
  public function getUnpaidMemoCount($id = null,$from=null,$to=null)
 {
     $count  = 0;
     $modelMemos = Memo::find()
     ->leftjoin('incident','incident.id = memo.incident_id')
     ->leftJoin('camera','camera.id = incident.camera_id')
     ->leftjoin('ward','ward.id=camera.ward_id')
     ->where(['memo.status' => 1])
     ->andWhere(['ward.id'=>$id])
     ->andWhere(['camera.status' =>1,'ward.status'=> 1,'incident.status' =>1])
     ->andWhere(['memo.is_paid' => 0]);
     if($from!=null)
     {
         $modelMemos->andWhere(['>=', 'memo.payment_date', $from]);
     }
     if($to!=null)
     {
         $modelMemos->andWhere(['<=', 'memo.payment_date', $to]);
     }
     $modelMemos=$modelMemos->all();
     if ($modelMemos)
     {
         $count = count($modelMemos);
     }
     return $count;
 }
    public function getIncidentType($id){
      $modelIncident= Incident::find()->where(['id' => $id,'status'=>1])->one();
      // print_r($modelIncident);exit;
      if($modelIncident){
        $modelIncidentType = IncidentType::find()->where(['id'=>$modelIncident->incident_type_id,'status'=>1])->one();
        return $modelIncidentType;
      }
    }
    public function getMapUrl(){
      $url = 'https://maps.google.com/?q=';
      return $url;
    }
    public function getIncident($id){
      $modelIncident= Incident::find()->where(['id' => $id,'status'=>1])->one();
      if($modelIncident){
        return $modelIncident;
      }
    }
    public function getCamera($id){
      $modelCamera= Camera::find()->where(['id' => $id,'status'=>1])->one();
      if($modelCamera){
        return $modelCamera;
      }
    }
    public function getAuthorizedSignatory($id){
      $modelAuthorizedSignatory= LsgiAuthorizedSignatory::find()->where(['id' => $id,'status'=>1])->one();
      if($modelAuthorizedSignatory){
        return $modelAuthorizedSignatory;
      }
    }
    public function getMemoType($id){
      $modelMemoType= MemoType::find()->where(['id' => $id,'status'=>1])->one();
      if($modelMemoType){
        return $modelMemoType;
      }
    }
    public function getImage($id)
    {
      $modelImage = Image::find()->where(['id' => $id,'status'=>1])->one();
      if($modelImage)
        return $modelImage;
    }
    public function getVideo($id)
    {
      $modelVideo = FileVideo::find()->where(['id' => $id,'status'=>1])->one();
      if($modelVideo)
        return $modelVideo;
    }
    public function getPreviewUrl(){
      return Url::to(['memos/preview','id' =>$this->id]);
    }
    // public function getFkImage()
    // {
    //     $modelImage = Image::find()
    //     ->innerJoin('lsgi','lsgi.image_id=image.id')
    //     ->innerJoin('ward','ward.lsgi_id = lsgi.id')
    //     ->innerJoin('camera','camera.id = ward.id')
    //     ->innerJoin('incident','incident.camera_id = camera.id')
    //     ->where(['image.status' =>1])
    //     ->andWhere(['ward.status' =>1,'camera.status' =>1,'incident.status' =>1,'lsgi.status' =>1]);
    //     // return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status' => 1]);
    // }
    public function getLsgi($id){
      $lsgi =Lsgi::find()->where(['id'=>$id])->one();
      return $lsgi;
    }
    public function deleteMemo($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('memo', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }

    // public function getLsgi($id)
    // {

        // $modelIncident = Incident::find()->where(['id'=>$id])->one();
        // if(isset($modelIncident)?$modelIncident:''){
        //   $modelCamera = Camera::find()->where(['id'=>$modelIncident->camera_id])->one();
        //   if($modelCamera)
        //     $modelWard = Ward::find()->where(['id'=>$modelCamera->ward_id])->one();
        //     if($modelWard){
        //       $modelLsgi = Lsgi::find()->where(['id'=>$modelWard->lsgi_id])->one();
        //       return $modelLsgi;
        //     }
        //   }
        // print_r($modelCamera);exit;
        // $modelLsgi = Lsgi::find()
        // ->leftJoin('ward','ward.lsgi_id = lsgi.id')
        // ->leftJoin('camera','camera.id = ward.id')
        // ->leftJoin('incident','incident.camera_id = camera.id')
        // ->leftJoin('memo','memo.incident_id=incident.id')
        // ->where(['lsgi.status' =>1])
        // ->andWhere(['memo.id' => $id])
        // ->andWhere(['ward.status' =>1,'camera.status' =>1,'incident.status' =>1,'memo.status' =>1]);
        // return $modelLsgi;

        // return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status' => 1]);
    // }
}
