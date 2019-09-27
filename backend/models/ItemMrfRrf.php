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
class ItemMrfRrf extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_mrf_rrf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id','mrf_id','qty','rrf_id'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'modified_at','item_id','mrf_id','rrf_id','qty','lsgi_id'], 'safe'],
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
            'green_action_unit_id' => Yii::t('app', 'Haritha karma sena'),
            'item_id' => Yii::t('app', 'Item'),
            'rrf_id' => Yii::t('app', 'RRF'),
            'mrf_id' => Yii::t('app', 'MCF'),
            'status' => Yii::t('app', 'Status'),
            'qty' => Yii::t('app', 'Quantity(In Kg)'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'lsgi_id' => Yii::t('app', 'Lsgi'),
        ];
    }
    public function deleteItem($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('item_mrf_rrf', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function search($params,$item=null,$mrf=null,$rrf=null,$from=null,$to=null,$lsgi=null)
    {
      $query = ItemMrfRrf::find()->where(['status'=>1])->orderby('id DESC');
      if($item)
      {
        $query->andWhere(['item_id'=>$item]);
      }
      if($mrf)
      {
        $query->andWhere(['mrf_id'=>$mrf]);
      }
      if($rrf)
      {
        $query->andWhere(['rrf_id'=>$rrf]);
      }
      if($lsgi)
      {
        $query->andWhere(['lsgi_id'=>$lsgi]);
      }
      if($from!=null)
        {
            $query->andWhere(['>=', 'item_mrf_rrf.created_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'item_mrf_rrf.created_at', $to]);
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
    public function getFkHks()
    {
        return $this->hasOne(GreenActionUnit::className(), ['id' => 'green_action_unit_id']);
    }
    public function getFkMrf()
    {
        return $this->hasOne(Mrc::className(), ['id' => 'mrf_id']);
    }
    public function getFkRrf()
    {
        return $this->hasOne(Mrc::className(), ['id' => 'rrf_id']);
    }
}
