<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "building_type".
 *
 * @property int $id
 * @property string $name
 * @property int $fk_image
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ItemMrcSurveyAgency extends \yii\db\ActiveRecord
{
  public $item,$mrc,$from,$to,$mrf,$rrf,$lsgi_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_mrc_survey_agency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id','mrc_id','qty','rate_per_kg','remarks'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'modified_at','item_id','mrc_id','qty','rate_per_kg','total','remarks'], 'safe'],
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
            'rate_per_kg' => Yii::t('app', 'Rate Per Kg'),
            'item_id' => Yii::t('app', 'Item'),
            'mrc_id' => Yii::t('app', 'MCF/RRF'),
            'status' => Yii::t('app', 'Status'),
            'qty' => Yii::t('app', 'Quantity(In Kg)'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteItem($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('item_mrc_survey_agency', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function search($params,$item=null,$mrc=null,$from=null,$to=null)
    {
      $query = ItemMrcSurveyAgency::find()->where(['status'=>1])->orderby('id DESC');
      if($item)
      {
        $query->andWhere(['item_id'=>$item]);
      }
      if($mrc)
      {
        $query->andWhere(['mrc_id'=>$mrc]);
      }
      if($from!=null)
        {
            $query->andWhere(['>=', 'item_mrc_survey_agency.created_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'item_mrc_survey_agency.created_at', $to]);
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
    public function getFkItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
    public function getFkMrc()
    {
        return $this->hasOne(Mrc::className(), ['id' => 'mrc_id']);
    }
}
