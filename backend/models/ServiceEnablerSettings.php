<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "service_enabler_settings".
 *
 * @property int $id
 * @property int $service_id
 * @property string $customer_field
 * @property string $customer_field_value
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ServiceEnablerSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_enabler_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'customer_field', 'customer_field_value'], 'required'],
            [['service_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['customer_field', 'customer_field_value'], 'string', 'max' => 500],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'service_id' => Yii::t('app', 'Service ID'),
            'customer_field' => Yii::t('app', 'Customer Field'),
            'customer_field_value' => Yii::t('app', 'Customer Field Value'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getFields()
    {
        $fields =[
                'has_bio_waste'=>'Has bio waste',
                'has_non_bio_waste'=>'Has non bio waste',
                'has_disposible_waste'=>'Has disposible waste',
                'has_bio_waste_management_facility'=>'Has bio waste management facility',
                'has_non_bio_waste_management_facility'=>'Has non bio waste management facility',
                'bio_waste_management_facility_operational'=>'Bio waste management facility operational',
                'bio_waste_management_facility_repair_help_needed'=>'Bio waste management facility repair help needed',
                'bio_waste_collection_needed'=>'Bio waste collection needed',
                'has_terrace_farming_interest'=>'Has terrace farming interest',
                'daily_collection_needed_bio'=>'Daily collection needed bio',
                'space_available_for_bio_waste_management_facility'=>'Space avvailable for bio waste management facility',
                'space_available_for_non_bio_waste_management_facility'=>'Space avvailable for non bio waste management facility',
                'help_needed_for_bio_waste_management_facility_construction'=>'Help needed for bio waste management facility construction',
                'has_space_for_non_bio_waste_management_facility'=>'Has space for non bio waste management facility',
                'has_interest_for_allotting_space_for_non_bio_management_facility'=>'Has interest for alloting space for non bio management facility',
                'has_interest_in_bio_waste_management_facility'=>'Has interest in bio waste management facility',
                'green_protocol_system_implemented'=>'Green protocol system implemented',
                'bio_medical_waste_collection_facility'=>'Bio medical waste collection facility',
                'has_bio_medical_incinerator'=>'Has bio medical incinerator',
                'has_interest_in_system_provided_bio_facility'=>'Has interest in system provided bio facility',
                'bio_waste_collection_method_id'=>'Bio waste Collection Method',
                'non_bio_waste_collection_method_id'=>'Non Bio waste Collection Method',
                'bio_medical_waste_collection_method'=>'Bio medical waste Collection Method',

        ];
        // print_r($fields);die();
        return $fields;
    }
    public function getCustomerField($customerField){
        
        $arr = [];
            $answers = null;
            $answers['has_bio_waste'] = 'Has Bio Waste';
            $answers['has_non_bio_waste'] = 'Has non bio waste';
            $answers['has_disposible_waste'] = 'Has disposible waste';
            $answers['has_bio_waste_management_facility'] = 'Has bio waste management facility';
            $answers['has_non_bio_waste_management_facility'] = 'Has non bio waste management facility';
            $answers['bio_waste_management_facility_operational'] = 'Bio waste management facility operational';
            $answers['bio_waste_management_facility_repair_help_needed'] = 'Bio waste management facility repair help needed';
            $answers['bio_waste_collection_needed'] = 'Bio waste collection needed';
            $answers['has_terrace_farming_interest'] = 'Has terrace farming interest';
            $answers['daily_collection_needed_bio'] = 'Daily collection needed bio';
            $answers['space_available_for_bio_waste_management_facility'] = 'Space avvailable for bio waste management facility';
            $answers['space_available_for_non_bio_waste_management_facility'] = 'Space avvailable for non bio waste management facility';
            $answers['help_needed_for_bio_waste_management_facility_construction'] = 'Help needed for bio waste management facility construction';
            $answers['has_space_for_non_bio_waste_management_facility'] = 'Has space for non bio waste management facility';
            $answers['has_interest_for_allotting_space_for_non_bio_management_facility'] = 'Has interest for alloting space for non bio management facility';
            $answers['has_interest_in_bio_waste_management_facility'] = 'Has interest in bio waste management facility';
            $answers['green_protocol_system_implemented'] = 'Green protocol system implemented';
            $answers['bio_medical_waste_collection_facility'] = 'Bio medical waste collection facility';
            $answers['has_bio_medical_incinerator'] = 'Has bio medical incinerator';
            $answers['has_interest_in_system_provided_bio_facility'] = 'Has interest in system provided bio facility';


            $answers['bio_waste_collection_method_id'] = 'Bio waste Collection Method';
            $answers['non_bio_waste_collection_method_id'] = 'Non Bio waste Collection Method';
            $answers['bio_medical_waste_collection_method'] = 'Bio medical waste Collection Method';
        return $answers[$customerField];
    }
    public function getCustomerFieldValue($customerField=null,$customerFieldValue=null){
        $value = null;
        if($customerField=='bio_waste_collection_method_id'||$customerField=='non_bio_waste_collection_method_id'||$customerField=='bio_medical_waste_collection_method')
        {
            $method = WasteCollectionMethod::find()->where(['id'=>$customerFieldValue])->one();
            if($method)
            {
                $value = $method->name;
            }

        }
        else
        {
            if($customerFieldValue==1)
            {
                $value ='Yes';
            }elseif($customerFieldValue==0)
            {
                $value ='No';
            }

        }
        return $value;
    }
     public function deleteServiceEnabler($id)
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->update('service_enabler_settings', ['status' => 0], 'id=:id')->bindParam(':id', $id)->execute();

        return true;
    }
}