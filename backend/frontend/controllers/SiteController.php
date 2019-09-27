<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\CmsHome;
use frontend\models\CmsEnquiries;
use frontend\models\CmsSettings;
use frontend\models\CmsTeam;
use frontend\models\KitchenBinRequest;
use frontend\models\Ward;
use frontend\models\Subscriber;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    // public function actionIndex($language = null)
    // {
    //     print_r(Yii::$app->request->get());
    //     $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
    //     $modelCmsHome = CmsHome::getHomeCMS();
    //     return $this->render('index', [
    //         'modelCmsHome' => $modelCmsHome,
    //         'language' => $language
    //     ]);
    // }
    public function actionIndex($language = null)
    {
        if($language=='ml'||$language=='en'){
        $session = Yii::$app->session;
        $session->set('language', $language);
    }
    $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelHome = Yii::$app->cms->getPage('Home');
        $modelHomeLinksOne = Yii::$app->cms->getPage('Home page link one');
        $modelHomeLinksTwo = Yii::$app->cms->getPage('Home page link two');
        $modelVideo = Yii::$app->cms->getPage('Video');
        $modelFacilitiesList = Yii::$app->cms->getPostsDataProvider(11);
        $modelHomeMenuOne = Yii::$app->cms->getPostsDataProvider(12);
        return $this->render('index-new', [
            'modelHome' => $modelHome,
            'modelFacilitiesList' => $modelFacilitiesList,
            'modelHomeLinksOne' => $modelHomeLinksOne,
            'modelHomeLinksTwo' => $modelHomeLinksTwo,
            'modelVideo' => $modelVideo,
            'modelHomeMenuOne' => $modelHomeMenuOne,
            'language' => $language
        ]);
    }
     public function actionAbout($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelAbout = Yii::$app->cms->getPage('About us');
        $modelTeam = Yii::$app->cms->getPage('Our Team');
        $modelPartner = Yii::$app->cms->getPage('Our Partners');
        $modelTeamListDataProvider = Yii::$app->cms->getPostsDataProvider(2);
        $modelPartnersListDataProvider = Yii::$app->cms->getPostsDataProvider(3);
        return $this->render('about', [
            'modelAbout' => $modelAbout,
            'modelTeam' => $modelTeam,
            'modelPartner' => $modelPartner,
            'modelTeamListDataProvider' => $modelTeamListDataProvider,
            'modelPartnersListDataProvider' => $modelPartnersListDataProvider,
            'language' => $language
        ]);
    }
    public function actionCustomerPortal($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelCustomerPortal = Yii::$app->cms->getPage('Customer portal');
        return $this->render('customer_portal', [
            'modelCustomerPortal' => $modelCustomerPortal,
            'language' => $language
        ]);
    }
    public function actionVendorPortal($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelVendorPortal = Yii::$app->cms->getPage('Vendor portal');
        return $this->render('vendor_portal', [
            'modelVendorPortal' => $modelVendorPortal,
            'language' => $language
        ]);
    }
    public function actionDepatmentPortal($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelDepartment = Yii::$app->cms->getPage('Department portal');
        return $this->render('department-portal', [
            'modelDepartment' => $modelDepartment,
            'language' => $language
        ]);
    }
    public function actionSmartSurvey($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelSmartSurvey = Yii::$app->cms->getPage('Smart survey');
        return $this->render('smart_survey', [
            'modelSmartSurvey' => $modelSmartSurvey,
            'language' => $language
        ]);
    }
    public function actionGarbageMonitoring($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelGarbageMonitoring = Yii::$app->cms->getPage('Garbage monitoring');
        return $this->render('garbage_monitoring', [
            'modelGarbageMonitoring' => $modelGarbageMonitoring,
            'language' => $language
        ]);
    }
    public function actionVehicleTrackingSystem($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelVehicleTracking = Yii::$app->cms->getPage('Vehicle tracking');
        return $this->render('vehicle_tracking', [
            'modelVehicleTracking' => $modelVehicleTracking,
            'language' => $language
        ]);
    }
    public function actionDepartments($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelDepartment = Yii::$app->cms->getPage('Departments');
        $modelDepartmentList = Yii::$app->cms->getPostsDataProvider(4);
        return $this->render('department', [
            'modelDepartment' => $modelDepartment,
            'modelDepartmentList' => $modelDepartmentList,
            'language' => $language
        ]);
    }
    public function actionDep($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelDepartment = Yii::$app->cms->getPage('Departments');
        $modelDepartmentList = Yii::$app->cms->getPostsDataProvider(4);
        return $this->render('dep', [
            'modelDepartment' => $modelDepartment,
            'modelDepartmentList' => $modelDepartmentList,
            'language' => $language
        ]);
    }
    public function actionActivities($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelActivity = Yii::$app->cms->getPage('Activities');
        $modelActivitySub = Yii::$app->cms->getPage('Activity sub');
        $modelProject = Yii::$app->cms->getPage('Projects');
        $modelActivitiesList = Yii::$app->cms->getPostsDataProvider(5);
        $modelProjectList = Yii::$app->cms->getPostsDataProvider(10);
        return $this->render('activities', [
            'modelActivity' => $modelActivity,
            'modelActivitySub' => $modelActivitySub,
            'modelProject' => $modelProject,
            'modelActivitiesList' => $modelActivitiesList,
            'modelProjectList' => $modelProjectList,
            'language' => $language
        ]);
    }
    public function actionNewsAndEvents($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelNewsAndEvents = Yii::$app->cms->getPage('News and Events');
        $modelNewsPage = Yii::$app->cms->getPage('News');
        $modelEventsPage = Yii::$app->cms->getPage('Events');
        $modelNews = Yii::$app->cms->getPostsDataProvider(6);
        $modelEvents = Yii::$app->cms->getPostsDataProvider(7);
        return $this->render('news-and-events', [
            'modelNewsAndEvents' => $modelNewsAndEvents,
            'modelNews' => $modelNews,
            'modelEvents' => $modelEvents,
            'modelNewsPage' => $modelNewsPage,
            'modelEventsPage' => $modelEventsPage,
            'language' => $language
        ]);
    }
    public function actionCentralizedFacilities($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelFacilitiesPage = Yii::$app->cms->getPage('Centralized facilities');
        $modelFacilitiesList = Yii::$app->cms->getPostsDataProvider(8);
        return $this->render('centralized-facilities', [
            'modelFacilitiesPage' => $modelFacilitiesPage,
            'modelFacilitiesList' => $modelFacilitiesList,
            'language' => $language
        ]);
    }
    public function actionTermsAndPolicy($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelTermsAndPolicy = Yii::$app->cms->getPage('Terms and Policy');
        return $this->render('terms-and-policy', [
            'modelTermsAndPolicy' => $modelTermsAndPolicy,
            'language' => $language
        ]);
    }
    public function actionFaq($language = null)
    {
        $language = Yii::$app->session->get('language');
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        $modelFaq = Yii::$app->cms->getPage('Faq');
        $modelFaqList = Yii::$app->cms->getPostsDataProvider(9);
        return $this->render('faq', [
            'modelFaq' => $modelFaq,
            'modelFaqList' => $modelFaqList,
            'language' => $language
        ]);
    }
    public function actionNews($language = null, $slug = null)
    {
        $language = Yii::$app->session->get('language');
        $modelNews = Yii::$app->cms->getPost($slug);
        $language = isset($language)?($language == 'ml'?'ml':'en'):'en';
        if (null == $modelNews) {
            throw new \yii\web\NotFoundHttpException('Data not found.');
        }   
        return $this->render('news-detail', [
            'modelNews' => $modelNews,
            'language' => $language
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    public function actionContactUs()
    {
        $model = new CmsEnquiries();
        $id = isset(Yii::$app->params['settingsId'])?Yii::$app->params['settingsId']:1; //Assumes first entry
        $modelSettings = $this->findSettingsModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    // if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
            if($model->save(false)){
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $mail = Yii::$app->email->sendContactMail($model);
                $adminMail = Yii::$app->email->sendContactMailToAdmin($model);
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact-us', [
                'model' => $model,
                'modelSettings' => $modelSettings,
            ]);
        }
    }
    protected function findSettingsModel($id)
    {
        if (($model = CmsSettings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
// public function actionKitchenBinRequests($success=null)
//     {
//         $success = isset($success)?$success:false;;
//         $model = new KitchenBinRequest();

//         return $this->render('kitchen-bin-requests', [
//             'model' => $model,
//             'success' => $success,
//         ]);
//     }
    public function actionKitchenBinRequests()
    {
        $success = false;
        $model = new KitchenBinRequest();

        if ($model->load(Yii::$app->request->post())&&$model->validate()) {
            $ward = Ward::find()->where(['id'=>$model->ward_id])->andWhere(['status'=>1])->one();
            if($ward)
            {
                $model->lsgi_id = $ward->lsgi_id;
            }
            $model->save();
            $success = true;
            $model = new KitchenBinRequest();
            return $this->redirect('index');
        // $model = new KitchenBinRequest();
        //    return $this->render('kitchen-bin-requests', [
        //     'model' => $model,
        //     'success' => $success,
        // ]);
        }

        return $this->render('kitchen-bin-requests', [
            'model' => $model,
            'success' => $success,
        ]);
    }
    public function actionSubscribe() {
      $modelSubscriber = new Subscriber();
      $params = Yii::$app->request->post();
      $subscribed = false;
      if($modelSubscriber->load($params)&&$modelSubscriber->validate()) {
        // $resp = Yii::$app->email->addSubscriber($modelSubscriber->email);
        // $subscribed = true;
        $modelSubscriber->save(false);
        
      }
          return $this->redirect('index');
      }
}
