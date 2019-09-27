<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//  public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $css = [
        'css/admin.css',
        'css/switchery.min.css',
        'css/site.css',
        'css/bootstrap.min.css',
        'css/default.css',
        'css/sidebar-nav.min.css',
        'css/jquery.toast.css',
        'css/morris.css',
        'css/chartist.min.css',
        'css/chartist-plugin-tooltip.css',
        'css/fullcalendar.css',
        'css/animate.css',
        'css/style.css?a=27',
        'css/vehicle.css',
        'css/materialdesignicons.min.css',
        'font-awesome-4.7.0/css/font-awesome.min.css',
        'themify-icons/themify-icons.css',
        'css/sweetalert.css',
        'css/bootstrap-datepicker.min.css',
        'css/daterangepicker.css',
        'css/dropify.min.css',
        'css/dropzone.css',
        'css/ekko-lightbox.min.css',
        'css/animated-masonry-gallery.css',
        'css/bootstrap-timepicker.min.css',
        'css/jquery-clockpicker.min.css',
        'css/spinners.css',
        'css/googlemap.css',
        'css/magnific-popup.css',
        'https://fonts.googleapis.com/css?family=Heebo:400,500,700" rel="stylesheet'
      //  'css/blue-dark.css',
    ];
    public $js = [
      //'js/jquery.min.js',
       'js/switchery.min.js',
       'js/bootstrap.min.js',
       'js/sidebar-nav.min.js',
       'js/jquery.slimscroll.js',
       'js/waves.js',
       'js/jquery.waypoints.js',
       'js/jquery.counterup.min.js',
       'js/raphael-min.js',
       'js/morris.js',
       'js/chartist.min.js',
       'js/chartist-plugin-tooltip.min.js',
       'js/moment.js',
       //'js/dashboard1.js',
       'js/cbpFWTabs.js',
       'js/custom.min.js',
       'js/jquery.toast.js',
       'js/jQuery.style.switcher.js',
       'js/dropzone.js',
       'js/track.js',
       'js/sweetalert.min.js',
       'js/bootstrap-datepicker.min.js',
       'js/daterangepicker.js',
       'js/dropify.min.js',
       'js/animated-masonry-gallery.js',
       'js/jquery.isotope.min.js',
       'js/ekko-lightbox.min.js',
       'js/bootstrap-timepicker.min.js',
       'js/jquery-clockpicker.min.js',
        'js/FileSaver.js',
       'js/Blob.js',
       'js/xlsx.core.js',
        'js/tableexport.js',
          'js/jquery.magnific-popup.min.js',
        'js/script.js',
        'theme/morris.js/morris.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
