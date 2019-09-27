<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cms_home".
 *
 * @property int $id
 * @property string $title
 * @property string $title_en
 * @property string $title_ml
 * @property string $sub_title_en
 * @property string $sub_title_ml
 * @property int $fk_image_banner
 * @property string $top_box_one_title_en
 * @property string $top_box_one_title_ml
 * @property string $top_box_one_sub_en
 * @property string $top_box_one_sub_ml
 * @property int $fk_image_top_box_one
 * @property string $top_box_two_title_en
 * @property string $top_box_two_title_ml
 * @property string $top_box_two_sub_en
 * @property string $top_box_two_sub_ml
 * @property int $fk_image_top_box_two
 * @property string $top_box_three_title_en
 * @property string $top_box_three_title_ml
 * @property string $top_box_three_sub_en
 * @property string $top_box_three_sub_ml
 * @property int $fk_image_top_box_three
 * @property string $abt_head_one_en
 * @property string $abt_head_one_ml
 * @property string $abt_head_two_en
 * @property string $abt_head_two_ml
 * @property string $abt_head_three_en
 * @property string $abt_head_three_ml
 * @property string $abt_head_four_en
 * @property string $abt_head_four_ml
 * @property int $fk_image_abt
 */
class CmsHome extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_home';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sub_title_en', 'sub_title_ml'], 'required'],
            [['fk_image_banner', 'fk_image_top_box_one', 'fk_image_top_box_two', 'fk_image_top_box_three', 'fk_image_abt'], 'integer'],
            [['title', 'title_en', 'title_ml', 'sub_title_en', 'sub_title_ml', 'top_box_one_title_en', 'top_box_one_title_ml', 'top_box_one_sub_en', 'top_box_one_sub_ml', 'top_box_two_title_en', 'top_box_two_title_ml', 'top_box_two_sub_en', 'top_box_two_sub_ml', 'top_box_three_title_en', 'top_box_three_title_ml', 'top_box_three_sub_en', 'top_box_three_sub_ml', 'abt_head_one_en', 'abt_head_one_ml', 'abt_head_two_en', 'abt_head_two_ml', 'abt_head_three_en', 'abt_head_three_ml', 'abt_head_four_en', 'abt_head_four_ml'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'title_en' => Yii::t('app', 'Title En'),
            'title_ml' => Yii::t('app', 'Title Ml'),
            'sub_title_en' => Yii::t('app', 'Sub Title En'),
            'sub_title_ml' => Yii::t('app', 'Sub Title Ml'),
            'fk_image_banner' => Yii::t('app', 'Fk Image Banner'),
            'top_box_one_title_en' => Yii::t('app', 'Top Box One Title En'),
            'top_box_one_title_ml' => Yii::t('app', 'Top Box One Title Ml'),
            'top_box_one_sub_en' => Yii::t('app', 'Top Box One Sub En'),
            'top_box_one_sub_ml' => Yii::t('app', 'Top Box One Sub Ml'),
            'fk_image_top_box_one' => Yii::t('app', 'Fk Image Top Box One'),
            'top_box_two_title_en' => Yii::t('app', 'Top Box Two Title En'),
            'top_box_two_title_ml' => Yii::t('app', 'Top Box Two Title Ml'),
            'top_box_two_sub_en' => Yii::t('app', 'Top Box Two Sub En'),
            'top_box_two_sub_ml' => Yii::t('app', 'Top Box Two Sub Ml'),
            'fk_image_top_box_two' => Yii::t('app', 'Fk Image Top Box Two'),
            'top_box_three_title_en' => Yii::t('app', 'Top Box Three Title En'),
            'top_box_three_title_ml' => Yii::t('app', 'Top Box Three Title Ml'),
            'top_box_three_sub_en' => Yii::t('app', 'Top Box Three Sub En'),
            'top_box_three_sub_ml' => Yii::t('app', 'Top Box Three Sub Ml'),
            'fk_image_top_box_three' => Yii::t('app', 'Fk Image Top Box Three'),
            'abt_head_one_en' => Yii::t('app', 'Abt Head One En'),
            'abt_head_one_ml' => Yii::t('app', 'Abt Head One Ml'),
            'abt_head_two_en' => Yii::t('app', 'Abt Head Two En'),
            'abt_head_two_ml' => Yii::t('app', 'Abt Head Two Ml'),
            'abt_head_three_en' => Yii::t('app', 'Abt Head Three En'),
            'abt_head_three_ml' => Yii::t('app', 'Abt Head Three Ml'),
            'abt_head_four_en' => Yii::t('app', 'Abt Head Four En'),
            'abt_head_four_ml' => Yii::t('app', 'Abt Head Four Ml'),
            'fk_image_abt' => Yii::t('app', 'Fk Image Abt'),
        ];
    }
    public function getFkImageBanner()
    {
        return $this->hasOne(Image::classname(), ['id' => 'fk_image_banner']);
    }
    public static function getImageUrlById($id = null)
    {
        $url = '';
        $modelImage = Image::find()->where(['id' => $id])->one();
        if (null != $modelImage) {
            $url = $modelImage->fullUrlById();
        }
        return $url;
    }
    public static function getHomeCms()
    {
        return CmsHome::find()->where(['id' => 1])->one();
    }
    public static function getVideoUrl()
    {
        $videoUrl = '';
        $modelCmsHome = CmsHome::find()->where(['id' => 1])->one();
        if (null != $modelCmsHome) {
            // print_r($modelCmsHome->video_url);exit;
            $videoUrl = Yii::$app->params['image_base'].$modelCmsHome->video_url;
        }
        return $videoUrl;
    }
}
