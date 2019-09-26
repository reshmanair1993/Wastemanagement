<?php
namespace common\components;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use app\models\Metadata;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\View;
use frontend\models\Meta;
use backend\models\PageMaster;
use backend\models\TourCategory;
use backend\models\PageChild;
use yii\jui\AutoComplete;
class UtilityComponent extends Component
{
	public function getSeoParameters($slug=null) {
		$modelPage = PageMaster::find()->where((['page_title'=>$slug]))->one();
		if($modelPage){
			$modelPageChild = PageChild::find()->where(['page'=>$modelPage->id])->one();
			return $modelPageChild;
		}
		

	}
	public function getCategory($slug=null) {
		$category = TourCategory::find()->where((['slug'=>$slug]))->one();
		
			return $category;
		

	}
	public function calculateVat($amount,$vatPercentage = null) {
		$ret = [
			'amount' => 0,
			'percentage' => 0
		];
		if(!$vatPercentage)
			$vatPercentage = $this->getMeta('vat_percentage');
		if($vatPercentage) {
			$ret['percentage'] =  (float)$vatPercentage;
			$vatAmount = (float)($amount*$vatPercentage/100);
			$ret['amount'] = $vatAmount;
		}
		return $ret;
	}
	public function getCurrencySymbol() {
		$symbol =isset(Yii::$app->params['currency'])&&isset(Yii::$app->params['currency']['symbol'])?Yii::$app->params['currency']['symbol']:'AED';
		return $symbol;
	}
	
	public function formatAmount($amount) {
		$currencySymbol = $this->getCurrencySymbol();
		$amount = $currencySymbol.' '. sprintf("%.2f", $amount);
		return $amount;
	}
	 public function getMeta($field) {
		 $ret = null;
		 $model =  Meta::get($field);
		 if($model) {
			 $ret = $model->{$field};
		 }
		 return $ret;
	 }
	public function getDefaultCancellationPeriod() {
		$configParams = Yii::$app->params;
		$ret = isset($configParams['default-booking-cancellation-period-days'])?$configParams['default-booking-cancellation-period-days']:5;
		return $ret;

	}
	public function getTransactionRefNoFormat() {
		$configParams = Yii::$app->params;
		$format = isset($configParams['transaction-no-format'])?$configParams['transaction-no-format']:['TRN{id}D{date}NHT'];
		return $format;

	}
	public function getBookingRefNoFormat() {
		$configParams = Yii::$app->params;
		$format = isset($configParams['booking-no-format'])?$configParams['booking-no-format']:['BK{id}D{date}NHT'];
		return $format;

	}
	public function getDateFormat() {
		$configParams = Yii::$app->params;
		$format = isset($configParams['date-format'])?$configParams['date-format']:'d-m-Y';
		return $format;
	}
	public function getDateFormatDB() {
		$configParams = Yii::$app->params;
		$format = isset($configParams['date-format-db'])?$configParams['date-format-db']:'Y-m-d';
		return $format;
	}
	public function getDateTimeFormatDB() {
		$configParams = Yii::$app->params;
		$format = isset($configParams['datetime-format-db'])?$configParams['datetime-format-db']:'Y-m-d H:i:s';
		return $format;
	}
	public function getDateFormatJS() {
		$configParams = Yii::$app->params;
		$format = isset($configParams['date-format-JS'])?$configParams['date-format-JS']:'dd-MM-yyyy';
		return $format;
	}
	public function renderStarRating($rating,$maxRating=5) {
		$fullStar = "<li><i class = 'fa fa-star'></i></li>";
		$halfStar = "<li><i class = 'fa fa-star-half-full'></i></li>";
		$emptyStar = "<li><i class = 'fa fa-star-o'></i></li>";
		$rating = $rating <= $maxRating?$rating:$maxRating;

		$fullStarCount = (int)$rating;
		$halfStarCount = ceil($rating)-$fullStarCount;
		$emptyStarCount = $maxRating -$fullStarCount-$halfStarCount;

		$html = str_repeat($fullStar,$fullStarCount);
		$html .= str_repeat($halfStar,$halfStarCount);
		$html .= str_repeat($emptyStar,$emptyStarCount);
		$html = '<ul>'.$html.'</ul>';
		return $html;
	}

	public function realUrl($url) {
		$url = str_replace('http://','',$url);
		$label = trim($url,'/');
		$label =  trim($label);
		$link = 'http://'.$url;
		$ret = [
			'label' => $label,
			'link' => $link
		];
		return $ret;
	}
	public function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
	}

	public function frontendRenderStarRating($rating,$maxRating=5,$flag=true) {
		$rating = $rating?$rating:0;
		$fullStar = "<li><i class = 'fa fa-star fa-star-yellow'></i></li>";
		$halfStar = "<li><i class = 'fa fa-star-half-o fa-star-yellow'></i></li>";
		$emptyStar = "<li><i class = 'fa fa-star fa-star-gray'></i></li>";
		$rating = $rating <= $maxRating?$rating:$maxRating;

		$fullStarCount = (int)$rating;
		$halfStarCount = ceil($rating)-$fullStarCount;
		$emptyStarCount = $maxRating -$fullStarCount-$halfStarCount;

		$html = str_repeat($fullStar,$fullStarCount);
		$html .= str_repeat($halfStar,$halfStarCount);
		$html .= str_repeat($emptyStar,$emptyStarCount);

		$html = '
		<ul class ="rating">'.$html."<li><span>$rating/$maxRating</span></li></ul>";
		if($flag)$html = '<div class="filter-rate">'.$html.'</div>';


		return $html;
	}

	// PHP strtotime compatible strings
   public function dateDiff($time1, $time2, $precision = 6) {
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }

    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }

    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();

    // Loop thru all intervals
    foreach ($intervals as $interval) {
      // Create temp time from time1 and interval
      $ttime = strtotime('+1 ' . $interval, $time1);
      // Set initial values
      $add = 1;
      $looped = 0;
      // Loop until temp time is smaller than time2
      while ($time2 >= $ttime) {
        // Create new temp time from time1 and interval
        $add++;
        $ttime = strtotime("+" . $add . " " . $interval, $time1);
        $looped++;
      }

      $time1 = strtotime("+" . $looped . " " . $interval, $time1);
      $diffs[$interval] = $looped;
    }

    $count = 0;
    $times = array();
    // Loop thru all diffs
    foreach ($diffs as $interval => $value) {
      // Break if we have needed precission
      if ($count >= $precision) {
        break;
      }
      // Add value and interval
      // if value is bigger than 0
      if ($value > 0) {
        // Add s if value is not 1
        if ($value != 1) {
          $interval .= "s";
        }
        // Add value and interval to times array
        $times[] = $value . " " . $interval;
        $count++;
      }
    }

    // Return string with times
    return implode(", ", $times);
  }
	public function renderError($message) {
		return
    	"<div class='has-error'>
      	<ul class='parsley-errors-list filled'>
          	<li class='parsley-required'><div class='help-block'>$message</div></li>
      	</ul>
    	</div>";

	}
	public function generateFieldTemplate($icon='',$errors=false,$label='',$gridColumns='12') {
		$label = $label?'{label}':'';
		$fieldTemplate = '<div class="col-xs-'.$gridColumns.' form-group has-feedback " style ="margin-top:0px !important">'.$label.'

								{input}
								<span class="fa :icon form-control-feedback left"></span>
								:error
								{hint}
							</div>';
		$errorTemplate = '<ul class="parsley-errors-list filled"><li class="parsley-required">{error}</li></ul>';
		$fieldTemplate = str_replace(':icon',$icon,$fieldTemplate);
	//	$errorTemplate = $errors?$errorTemplate:'';
		$fieldTemplate = str_replace(':error',$errorTemplate,$fieldTemplate);
		return $fieldTemplate;

	}
	public function renderField($model,$form,$settings,$echo = true) {

		if(!isset($settings['field'])) {
			$ret = '';
			if($echo){
				echo $ret;
				return $ret;

			}
		};
		$fieldName = $settings['field'];
		$type = isset($settings['type'])?$settings['type']:'textInput';
		$placeHolder = isset($settings['placeholder'])?str_replace('_',' ',ucfirst($settings['placeholder'])):'';
		$label = isset($settings['label'])?$settings['label']:$placeHolder;
		$icon = isset($settings['icon'])?$settings['icon']:'fa-tag';
		$error_class= isset($model->errors[$fieldName])?'parsley-error':'';
		$optionsDefault = [
							'class'=>"form-control has-feedback-left  $error_class ",
							'placeholder' => $placeHolder
		];

		$options = isset($settings['options'])?$settings['options']:[];
		if(isset($settings['class']) ) {
			$options['class'] = 'form-control has-feedback-left '.$settings['class'].' '.$error_class ;
		}
		if(!isset($options['class']))
		$options['class'] = $optionsDefault['class'];

		$extraOptions = [
			'timepicker' => ['type' => 'time']

		];
		if($type == 'datepicker' || $type == 'daterangepicker'  || $type == 'timepicker') {
			$options['class'] .= " $type";
			$type = 'textInput';
		}

		if(isset($extraOptions[$type])){
		$options = array_merge($options,$extraOptions[$type]);
		}
		$value = isset($options['value'])?$options['value']:null;
		$value =  isset($settings['value'])?$settings['value']:$value;
		$options['value'] = $value;
		$options = array_merge($optionsDefault,$options);
		$gridColumns = isset($settings['grid-columns'])?$settings['grid-columns']:12;
		$template = $type != 'hiddenInput'? $this->generateFieldTemplate($icon,$error_class,$label,$gridColumns):'{input}';
		$widgetConfig = isset($settings['widgetConfig'])?$settings['widgetConfig']:[];
		$fld = $form->field($model,$fieldName,
						[
								'template' =>$template
						]
		 );
		 if($type != 'autocomplete' && $type != 'dropDownList') {
			$ret =  $fld->{$type}($options)->label($label);
		 }
		 else if($type ==  'dropDownList') {
			$ret =  $fld->{$type}($settings['items'],$options)->label($label);

		 } else  {


			 $widgetConfig['options'] = $options;

			 if(!isset($widgetConfig['model']))
			 $widgetConfig['model'] = $model;
			 if(!isset($widgetConfig['placeholder']))
			 $widgetConfig['options']['placeholder'] = $placeHolder;



			 $ret = $fld->widget(AutoComplete::classname(), $widgetConfig)->label($label);

		 }

		 if($echo){
			 echo $ret;
		 }

		 return $ret;
	}

	public function renderFields($model,$form,$settings) {
		foreach($settings as $setting) {
			$modelToUse = !isset($setting['model'])?$model:$setting['model'];
			$this->renderField($modelToUse,$form,$setting);
		}

	}

  public function cloneModel($className,$model) {
		$attributes = $model->attributes;
		$newObj = new $className;
		foreach($attributes as  $attribute => $val) {
			$newObj->{$attribute} = $val;
		}
		return $newObj;
	}
	public  function insert($model) {
		$model = $this->cloneModel($model::className(),$model);
		$model->save(false);
		$ret = $model->id?$model:null;
		return $ret;
	}
	public function registerPjaxSuccess($view,$params) {//fires javascript callback, onPjaxSuceess with params
		$params = json_encode($params);
		$view->registerJs("
		if (typeof window.onPjaxSuccess === 'undefined') {
			window.onPjaxSuccess = function() {
			}
		}
		window.onPjaxSuccess($params);
		",View::POS_END);
	}
	public function googleDirectionsUrl($latitude,$longitude) {
		$url = "https://www.google.com/maps/dir/Current+Location/$latitude,$longitude";
		return $url;
	}
	public function show404() {

				throw new NotFoundHttpException('The requested page does not exist.');
	}
}
