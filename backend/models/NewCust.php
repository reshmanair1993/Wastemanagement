<?php
namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property int $ward_id
 * @property int $building_type_id
 * @property int $door_status
 * @property string $building_name
 * @property string $building_number
 * @property string $association_name
 * @property string $association_number
 * @property string $lead_person_name
 * @property string $lead_person_phone
 * @property string $address
 * @property string $building_owner_name
 * @property string $building_owner_phone
 * @property int $trading_type_id
 * @property int $shop_type_id
 * @property int $has_bio_waste
 * @property int $has_non_bio_waste
 * @property int $has_disposible_waste
 * @property double $lat
 * @property double $lng
 * @property int $fee_collection_interval_id
 * @property int $has_bio_waste_management_facility
 * @property int $bio_waste_management_facility_operational
 * @property int $bio_waste_management_facility_repair_help_needed
 * @property int $bio_waste_collection_method_id
 * @property int $bio_waste_collection_needed
 * @property int $non_bio_waste_collection_method_id
 * @property int $has_terrace_farming_interest
 * @property int $terrace_farming_help_type_id
 * @property int $creator_account_id
 * @property int $house_people_count
 * @property int $house_adult_count
 * @property int $house_children_count
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 */
class NewCust extends \yii\db\ActiveRecord
{
    /**
     * @var mixed
     */
    // public $district_id, $block_id, $assembly_constituency_id, $lsgi_id,$code,$no_association;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'new_cust';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'status'], 'integer'],
            [['username'], 'string'],
           
        ];
    }

}
