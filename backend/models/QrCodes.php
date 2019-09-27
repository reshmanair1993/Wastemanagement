<?php

namespace backend\models;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "qr_code".
 *
 * @property int $id
 * @property string $value
 * @property int $account_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class QrCodes extends \yii\db\ActiveRecord
{
    public $district_id,$block_id,$assembly_constituency_id,$lsgi;
    public $limit,$start,$columns;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qr_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value',], 'required'],
            [['limit','district_id','block_id','assembly_constituency_id','lsgi_id'], 'required'],
            [['start', 'district_id','block_id','assembly_constituency_id','lsgi_id','columns','limit'], 'required','on'=>'print-sheet'],
            [['account_id', 'status'], 'integer'],
            [['created_at', 'modified_at','limit','lsgi_id'], 'safe'],
            [['value'], 'string', 'max' => 255],
        ];
    }
     public function behaviors() {
    return [
      [
        'class' => TimestampBehavior::className(),
        'createdAtAttribute' => 'created_at',
        'updatedAtAttribute' => 'modified_at',
        'value' => new Expression('NOW()')
      ]
    ];

 }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
            'limit' => Yii::t('app', 'No.of Codes'),
            'account_id' => Yii::t('app', 'Account ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuerys() {
     $query = static::find()->where(['qr_code.status'=>1])
                            ;
     return $query;
   }
    public static function getAllQuery($start=null,$limit=null,$lsgi_id=null) {
     $query = static::find()->where(['qr_code.status'=>1])
                            ->andWhere(['qr_code.account_id'=>null])
                            ->andWhere(['=','qr_code.lsgi_id',$lsgi_id]);
     if($start)
     {
        $qr = static::find()->where(['value'=>$start])
        // ->andWhere(['lsgi_id'=>$lsgi_id])
        ->one();
        if($qr!=null){

        $query->andWhere(['>=','id',$qr->id])
             ->limit($limit);
         }
         if($limit!=0)
        {
        $query->limit($limit);
         }
     }
     // if($lsgi_id){
     //    $query->andWhere(['=','lsgi_id',$lsgi_id]);
     // }
     return $query;
   }
   public function getFkAccount()
        {
                return $this->hasOne(Account::className(), ['id' => 'account_id']);
        }
    public function getLsgi($customer)
    {
        $name =null;
        $account = Account::find()->where(['id'=>$customer])->one();
        if($account)
        {
            $customerData = Customer::find()->where(['id'=>$account->customer_id])->one();
            // 
            if($customerData)
            {

               $wards =  Ward::find()->where(['status'=> 1])->andWhere(['id'=>$customerData->ward_id])->one();

               if($wards)
               {
        $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
        if($lsgi){
            $name = $lsgi->name;  
        }
    }
    }
}
return $name;
}
public function getLsgis($id=null)
    {
        $name  = null;
        $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
            $name = $lsgi->name;  
        }
      return $name;
    }
     public function getUnit($id)
    {
        $name  = null;
        $unit =  GreenActionUnit::find()->where(['id'=> $id])->one();
        if($unit){
            $name = $unit->name;  
        }
      return $name;
    }
     public function getBlock($id)
    {
        $name  = null;
        $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
        $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $name = $block->name;  
        }
         }
      return $name;
    }
    public function getConstituency($id)
    {
        $name = null;
         $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
         $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one(); 
            $name = $assembly_constituency->name;
        }

    }
          
        return $name;
    }
     public function getDistrict($id)
    {
        $name = null;
       $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
         $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            if($assembly_constituency)
            {
                $district = District::find()->where(['id'=> $assembly_constituency->district_id])->one();
                $name = $district->id;  
            } 
            
        }
    }
        return $name;
    }
     public function getDistricts()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
    }
}
