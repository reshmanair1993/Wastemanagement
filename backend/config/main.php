<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'account/login',
    'bootstrap' => ['log'],
    'modules' => ['gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],],
    'components' => [
      'sendGrid' => [
      'class' => 'bryglen\sendgrid\Mailer',
      'username' => 'cocoalabs',
      'password' => 'cocoalabs123',
      'viewPath' => '@app/views/mail'
      ],
      'onesignal' => [
        'class' => '\vasadibt\onesignal\OneSignal',
        'appId' => '820c2ad1-5896-4450-bb6d-ab33388d25d9',
        'appAuthKey' => 'N2ZiMThiNGEtYWRhYy00MjQyLTk1NWEtMzFkMmU2NzFkMjZk',
        'userAuthKey' => 'YmY5YzJhOWYtOWY0OC00M2I1LTlkMmYtYmVmZmY4OTFjMWNj',
        'enabled' => true,
    ],
      // 'mailer' => [
      //   'class' => 'wadeshuler\sendgrid\Mailer',
      //   'viewPath' => '@backend/views/mail',
      //   // send all mails to a file by default. You have to set
      //   // 'useFileTransport' to false and configure a transport
      //   // for the mailer to send real emails.
      //   'useFileTransport' => false,
      //   'apiKey' => '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',
      // ],
      'email' => [
            'class'=>'backend\components\EmailComponent',
            'SENDGRID_API_KEY' => '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',
            // other configurations for the component
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

    ],
    'params' => $params,
];
