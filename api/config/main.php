<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
          'v1' => [
              'basePath' => '@api/modules/v1',
              'class' => 'api\modules\v1\Module'   // here is our v1 modules
          ],
      ],
    'defaultRoute' => 'site/index',
    'components' => [
       'twilio' => [
                   'class' => dpodium\yii2\Twilio\TwilioManager::class,
                   'config' => [
                                   'sid'   => ACaf53e97dce44e612134ea7a81e86ab8b, //from twilio
                                   'token' => d3e168426070697db534f36d1a1320ab, //from twilio
                               ],
               ],
               'onesignal' => [
        'class' => '\vasadibt\onesignal\OneSignal',
        'appId' => '820c2ad1-5896-4450-bb6d-ab33388d25d9',
        'appAuthKey' => 'N2ZiMThiNGEtYWRhYy00MjQyLTk1NWEtMzFkMmU2NzFkMjZk',
        'userAuthKey' => 'YmY5YzJhOWYtOWY0OC00M2I1LTlkMmYtYmVmZmY4OTFjMWNj',
        'enabled' => true,
    ],
      'user' => [
          'identityClass' => 'api\modules\v1\models\Account',
          'enableAutoLogin' => true,
          'enableSession' => true,
          'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
      ],
      'urlManager' => [
        'enablePrettyUrl' => true,
        'enableStrictParsing' => false, //if true users/ will throw error and users will work
        'showScriptName' => false,
        'rules' => [
          // [
          //   'class' => 'yii\rest\UrlRule',
          //   'controller' => 'v1/assembly-constituency',
          //   'extraPatterns' => [
          //     'GET assembly' => 'assembly-constituency/index',
          //   ]
          // ],

        

        ],
      ],
      'response' => [
        'format' => 'json'
      ],
      'request' => [
        'parsers' => [
          'application/json' => 'yii\web\JsonParser',
        ],
      ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-api',
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
            'errorAction' => '/v1/site/error',
        ],
		/* 'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                // ...
            ],
        ], */


  ],
  'params' => $params,
];
