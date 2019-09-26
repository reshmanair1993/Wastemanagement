<?php
return [
            'timeZone'        => 'Asia/Kolkata',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
		'utilities' => [
			'class' => 'common\components\UtilityComponent'
			],
      'formatter'    =>
        [
            'timeZone'        => 'Asia/Kolkata',
        ],
      'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'rbac' => [
                'class' => 'common\components\RbacComponent',
        ],
        'cms' => [
                'class' => 'common\components\CmsComponent',
        ],
        'message' => [
                'class' => 'common\components\MessageComponent',
        ],
        'cron' => [
                   'class' => 'common\components\CronComponent'],
                   'cron1' => [
                   'class' => 'common\components\Cron1Component'],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
      'jwt' => [
                   'class' => 'common\components\JWTComponent',

                   'PRIVATE_KEY' => '-----BEGIN RSA PRIVATE KEY-----
MIICWgIBAAKBgHxG6r3zYM1knSwqVD8Se07BA6fYH2YO93Yo0UH2Mr40xmkpZvXi
E9UMeqoN6fRlBeqxZlDzcnSRXCg1O/+3dYn2wslpZtLw1aq8ivqLlC45wb/D6OEj
uiLDpssCMqLxb6Gi67WwLq/xyFJqAdx4JzFa7SdDN1oro4+B63uwtuQrAgMBAAEC
gYBQFA4sSYelsWBJVhkk7w6/Z0WowG0zAQ/ZdmGoJDD8ONtkZcYvR/bJgBoGO6L1
1KakXJz2Kngkvoloayz3EErOrerk4aVJnd/20Td8M4D8rudbgi2FgwKmYb0ZsUUB
lRc4Dr9VBCqwZbk6ruU1JzRsisEpdw0ztPMkNHDo6ltMuQJBAL7XCjn49PJUyjqT
+nBG6K93QxJUR3p0lMSGQz/tjU0YhJuXXroOH8MKSKSdmRd36Yk/pwIOosIauEU+
7ylAaj8CQQCmtbvlc4ihrPzAE38H2mwjh9cHc32PTOGoj+0opl2FTAr+a/coX1Ph
KSeNxyAAmHbJq9//102C0iGzi/TblJMVAkBAEkxxqD78uTDoN9RmK7hlaMIQ/lC9
MTTdQkKDzQqarree0VRRXPqW7fXzpqHGelDi7obwrt9AEd56CSYckG7bAkB4dugw
tJytn3AAZ9YqWZY80oL6WmUHsNl7UY1hC16W3M0w7dlqbgARuwhe9d3VMFbeAfna
SL005B0APgkQxrrFAkBjSc3qi90seg3M8PWcNyBkXcFKRlcTCXRebPMgDi4bkHG1
QGUBIHou2mvqtUL4FqJlw1I+yik4WSdZ96dDZFHW
-----END RSA PRIVATE KEY-----',

               'PUBLIC_KEY' => '-----BEGIN PUBLIC KEY-----
MIGeMA0GCSqGSIb3DQEBAQUAA4GMADCBiAKBgHxG6r3zYM1knSwqVD8Se07BA6fY
H2YO93Yo0UH2Mr40xmkpZvXiE9UMeqoN6fRlBeqxZlDzcnSRXCg1O/+3dYn2wslp
ZtLw1aq8ivqLlC45wb/D6OEjuiLDpssCMqLxb6Gi67WwLq/xyFJqAdx4JzFa7SdD
N1oro4+B63uwtuQrAgMBAAE=
-----END PUBLIC KEY-----',
                ]
    ],
];
