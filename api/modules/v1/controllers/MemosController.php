<?php
namespace mvdapi\modules\v1\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use mvdapi\modules\v1\models\Memo;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use mvdapi\modules\v1\models\MemoPenalty;

class MemosController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = '\mvdapi\modules\v1\models\Memo';

    /**
     * @return mixed
     */
    public function actions()
    {
        $actions      = parent::actions();
        $unsetActions = ['create', 'update', 'index', 'delete'];
        foreach ($unsetActions as $action)
        {
            unset($actions[$action]);
        }

        return $actions;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST']
                ]
            ],
            'auth'  => [
                'class' => HttpBearerAuth::className()
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $query        = Memo::getAllQuery();
        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);
        $models = $dataProvider->getModels();
        $ret    = [];
        foreach ($models as $model)
        {
            $ret[] = [
                'id'           => $model->id,
                'name'         => $model->name,
                'email'        => $model->email,
                'address'      => $model->address,
                'amount'       => $model->amount,
                'incident_id'  => $model->incident_id,
                'memo_type_id' => $model->memo_type_id,
                'lsgi_id'      => $model->lsgi_id,
                'memo_url'     => $model->id
            ];
        }

        $ret = [
            'memo_base' => 'http://139.162.54.79/wastemanagement/backend/web/memos/preview?id=',
            'items'     => $ret
        ];

        return $ret;
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function actionMemoId($id = null)
    {
        $query        = Memo::getAllQuery()->andWhere(['id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);
        $models = $dataProvider->getModels();
        $ret    = [];
        foreach ($models as $model)
        {
            $ret = [
                'memo_base'    => 'http://139.162.54.79/wastemanagement/backend/web/memos/preview?id=',
                'id'           => $model->id,
                'name'         => $model->name,
                'email'        => $model->email,
                'address'      => $model->address,
                'amount'       => $model->amount,
                'incident_id'  => $model->incident_id,
                'memo_type_id' => $model->memo_type_id,
                'lsgi_id'      => $model->lsgi_id,
                'memo_url'     => $model->id
            ];
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function actionAdd()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];
        $params    = Yii::$app->request->post();

        while (true)
        {
            if (!isset($params['name']))
            {
                $msg   = ['Name is mandatory'];
                $error = ['name' => $msg];
                $ret   = ['errors' => $error];
                break;

                return $ret;
            }
            if (!isset($params['incident_id']))
            {
                $msg   = ['Incident is mandatory'];
                $error = ['incident_id' => $msg];
                $ret   = ['errors' => $error];
                break;

                return $ret;
            }
            if (!isset($params['memo_type_id']))
            {
                $msg   = ['Memo type is mandatory'];
                $error = ['memo_type_id' => $msg];
                $ret   = ['errors' => $error];
                break;

                return $ret;
            }
            if ($params)
            {
                $amount           = 0;
                $modelMemoPenalty = MemoPenalty::find()->where(['status' => 1])->andWhere(['memo_type_id' => $params['memo_type_id']])->one();
                if ($modelMemoPenalty)
                {
                    $amount = $modelMemoPenalty->amount ? $modelMemoPenalty->amount : 0;
                }
                $modelMemo = new Memo;
                if ($params['incident_id'])
                {
                    $modelMemoIncident = Memo::find()->where(['incident_id' => $params['incident_id']])->andWhere(['status' => 1])->one();
                    if ($modelMemoIncident)
                    {
                        $msg   = ['Incident is invalid'];
                        $error = ['incident_id' => $msg];
                        $ret   = ['errors' => $error];
                        break;

                        return $ret;
                    }
                }

                $modelMemo->name                         = isset($params['name']) ? $params['name'] : null;
                $modelMemo->email                        = isset($params['email']) ? $params['email'] : null;
                $modelMemo->address                      = isset($params['address']) ? $params['address'] : null;
                $modelMemo->subject                      = isset($params['subject']) ? $params['subject'] : null;
                $modelMemo->description                  = isset($params['description']) ? $params['description'] : null;
                $modelMemo->amount                       = isset($params['amount']) ? $params['amount'] : $amount;
                $modelMemo->incident_id                  = isset($params['incident_id']) ? $params['incident_id'] : null;
                $modelMemo->memo_type_id                 = isset($params['memo_type_id']) ? $params['memo_type_id'] : null;
                $modelMemo->lsgi_id                      = isset($params['lsgi_id']) ? $params['lsgi_id'] : null;
                $modelMemo->lsgi_authorized_signatory_id = isset($params['lsgi_authorized_signatory_id']) ? $params['lsgi_authorized_signatory_id'] : null;
                $modelMemo->account_id                   = $userId;
                $modelMemo->save(false);
                $ret = [
                    'id'           => $modelMemo->id,
                    'name'         => $modelMemo->name,
                    'email'        => $modelMemo->email,
                    'address'      => $modelMemo->address,
                    'amount'       => $modelMemo->amount,
                    'incident_id'  => $modelMemo->incident_id,
                    'memo_type_id' => $modelMemo->memo_type_id,
                    'lsgi_id'      => $modelMemo->lsgi_id
                ];
            }
            break;
        }

        return $ret;
    }
}
