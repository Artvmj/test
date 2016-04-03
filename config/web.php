<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'components' => [

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [],
                ],
            ],
        ],
        'request' => [
            'baseUrl' => '/',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'nvDiR9dtwJcSAh_iVp29Ysv79BCUE4tn',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User', // User must implement the IdentityInterface
            'enableAutoLogin' => true,
            'authTimeout' => 86400,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'baseUrl' => '/',
            'showScriptName' => false, // Only considered when enablePrettyUrl is set to true
            'rules' => [
                //  '<controller:\w+>/<action:[\w-]+>' => '<controller>/<action>'





                /* 'admin/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<controller>/<action>', */
                //  'admin/<module:\w+>/<action:[\w-]+>' => '<module>/default/<action>'

                'admin/' => 'transaction/default/index',
                '/auth' =>   'user/security/login/',
                '/logout' => 'user/security/logout/',
                'admin/<module:\w+>/<id:\d+>' => '<module>/default/view',
                'admin/<module:\w+>/<action:\w+>/<id:\d+>' => '<module>/default/<action>',
                'admin/<module:\w+>/<action:\w+>' => '<module>/default/<action>',
                '<action:[\w-]+>' => 'site/<action>',
            ],
        ],
    ],
    'modules' => [
        'transaction' => [
            'class' => 'app\modules\transaction\Transaction'
        ],
        'user' => [
            'class' => 'app\modules\user\User'
        ],
        'user' => [
            'class' => 'app\modules\user\UserModule',
        ],
        'transaction_category' => [
            'class' => 'app\modules\transaction_category\TransactionCategory',
        ],
        'transaction_type' => [
            'class' => 'app\modules\transaction_type\TransactionType',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['components']['assetManager']['forceCopy'] = true;

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
