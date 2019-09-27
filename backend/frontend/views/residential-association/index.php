<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Ward;
use yii\widgets\ListView;
use yii\helpers\Url;
foreach($params as $param => $val)
  ${$param} = $val;
?>

  <section class="page-banner">
    <img src="../img/residential/banner.jpg" alt="Smart Trivandrum Banner" class="bg-img">
    <div class="container ta-center">
      <h1>Residential Association</h1>
      <h4>WEAREHARD,WEPAYHARD</h4>
      <div class="heading-underline">
        <span></span>
        <span class="white-bar"></span>
        <span></span>
      </div>
    </div>
  </section>
  <section class="res-cont1">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-12"><h4 class="section-head">Residents Association in Trivandrum</h4></div>
        <div class="col-lg-7 col-md-12 col-sm-12 col-12">
         <?php $form = ActiveForm::begin(['action' =>Url::to(['residential-association/index','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search m-r-10']]);?>
            <div class="row">
              <div class="col-lg-7 col-md-7 col-sm-7 col-12">
              <?php $keyword =  isset($_POST['name'])?$_POST['name']:'';?>
                <!-- <input type="text" placeholder="Search" class="form-control" name="keyword"> -->
                <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>"  placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>
              </div>
              <div class="col-lg-5 col-md-5 col-sm-5 col-12">
                <div class="row">
                  
                  <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group custom-select-group">
                      <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::find()->where(['status'=>1])->all();
            $listData=ArrayHelper::map($ward, 'id', 'name');

            echo $form->field($modelResidentialAssociation, 'ward')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-12 ta-center">
                    <button type="submit" class="bt-primary">Search</button>
                  </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
  <?php
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper',
            'id' => 'list-wrapper',
        ],
        'layout' => "{items}",
        'itemView' => function ($model, $key, $index, $widget) {
          return $this->render('residential-association-list', [
            'model' => $model,
            'index' => $index+1,
          ]);
        },
    ]);
  ?>
    </div>
  </section>

