<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Account;
use api\modules\v1\models\PushMessage;
use api\modules\v1\models\PushMessageStatus;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
/**
 * AccountController implements the CRUD actions for Account model.
 */
class MessagesController extends ActiveController
{
     public function behaviors()
    {
        return [
            'auth'  => [
                'class' => HttpBearerAuth::className()
            ],
            'access' => [
                'class' => AccessControl::className(),
                 'only' => ['notification-count'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['notification-count'],
                        'roles' => ['customer'],
                    ],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public $modelClass = '\api\modules\v1\models\PushMessage';

    public function actionNotificationCount()
    {
        $hksId = 0;
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $modelAccount = Account::find()->where(['id'=>$userId])->andWhere(['status'=>1])->one();
        $lsgiId = isset($modelAccount->fkCustomer->fkWard->fkLsgi)?$modelAccount->fkCustomer->fkWard->fkLsgi->id:0;
        $wardId = isset($modelAccount->fkCustomer->fkWard)?$modelAccount->fkCustomer->fkWard->id:0;
        $accountAuthority = $modelAccount->fkAccountAuthority;
          if($accountAuthority)
          {
            $account = $accountAuthority->fkAccountSup;
            if($account)
            {
              $hksId = isset($account->green_action_unit_id)?$account->green_action_unit_id:0;
            }
            }
         $query     = PushMessage::find()
                    ->leftjoin('push_message_status','push_message_status.notification_id=push_message.id')
                    ->where(['push_message.status' => 1])
                    ->andWhere(['push_message_status.notification_id'=>null])
                     ->andWhere(['or',
                       ['lsgi_id'=>$lsgiId],
                       ['ward_id'=>$wardId],
                       ['hks_id'=>$hksId],
                       ['push_message.account_id'=>$userId]
                   ]);
        $count = (int) $query->count();

        return [
            'count' => $count
        ];
    }
    public function actionNotificationList($account_id = null,$page = 1,
        $per_page = 30)
    {
        $ret =[];
        $hksId = 0;
        $query = PushMessage::find()->where(['status'=>1]);
        if ($account_id)
        {
        $modelAccount = Account::find()->where(['id'=>$account_id])->andWhere(['status'=>1])->one();
        $createdDate = $modelAccount->created_at;
        $lsgiId = isset($modelAccount->fkCustomer->fkWard->fkLsgi)?$modelAccount->fkCustomer->fkWard->fkLsgi->id:0;
        $wardId = isset($modelAccount->fkCustomer->fkWard)?$modelAccount->fkCustomer->fkWard->id:0;
        $accountAuthority = $modelAccount->fkAccountAuthority;
          if($accountAuthority)
          {
            $account = $accountAuthority->fkAccountSup;
            if($account)
            {
              $hksId = isset($account->green_action_unit_id)?$account->green_action_unit_id:0;
            }
            }
            $query->andWhere(['or',
                       ['lsgi_id'=>$lsgiId],
                       ['ward_id'=>$wardId],
                       ['hks_id'=>$hksId],
                       ['push_message.account_id'=>$account_id]
                   ])
            ->andWhere(['>=','push_message.created_at',$createdDate]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

            ]);
            $models = $dataProvider->getModels();
            $amount = 0;
            foreach ($models as $model)
            {
                $ret[] = [
                'id' => $model->id,
                'message'=>$model->message,
                'message_ml'=>$model->message_ml,
                'status'=>$model->getStatus($model->id),
            ];
            }
        }
        else
        {
            $msg   = ['Account id is mandatory'];
            $error = ['account_id' => $msg];
            $ret   = ['errors' => $error];
        }

        return $ret;
    }
    public function actionMarkAsRead()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $params = Yii::$app->request->post();
        $ret = [];
        while (true)
        {
            if (isset($params['status']))
            {
                $modelPushMessageStatus = new PushMessageStatus;
                $modelPushMessageStatus->notification_status = $params['status'];
                $modelPushMessageStatus->notification_id = isset($params['notification_id'])?$params['notification_id']:null;
                $modelPushMessageStatus->account_id = $userId;
                $modelPushMessageStatus->save(false);
                $ret = [
                        'notification_id' => $modelPushMessageStatus->notification_id,
                        'status' => $modelPushMessageStatus->notification_status,
                    ];
               
            }
            else
            {
                $msg   = ['Status is mandatory'];
                $error = ['status' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }
       public function actionDetails($id = null)
    {
        if ($id)
        {
        $modelPushMessage = PushMessage::find()->where(['id'=>$id])->andWhere(['status'=>1])->one();
        if($modelPushMessage){
                $ret= [
                'id' => $modelPushMessage->id,
                'message'=>$modelPushMessage->message,
                'message_ml'=>$modelPushMessage->message_ml,
            ];
            }
        }
        else
        {
            $msg   = ['Notification id is mandatory'];
            $error = ['id' => $msg];
            $ret   = ['errors' => $error];
        }

        return $ret;
    }
    public function actionNotificationForAccount($account_id = null,$page = 1,
        $per_page = 30)
    {
        $ret = [];
        $hksId = null;
        
        if ($account_id)
        {
        $query = PushMessage::find()->where(['status'=>1])->andWhere(['account_id'=>$account_id]);
       
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

            ]);
            $models = $dataProvider->getModels();
            if($models){
            foreach ($models as $model)
            {
                $ret[] = [
                'id' => $model->id,
                'message'=>$model->message,
                'message_ml'=>$model->message_ml,
                'status'=>$model->getStatus($model->id),
            ];
            }
        }
        }
        else
        {
            $msg   = ['Account id is mandatory'];
            $error = ['account_id' => $msg];
            $ret   = ['errors' => $error];
        }

        return $ret;
    }
}
