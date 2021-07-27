<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        //да-да, работает
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        // 'imagemanager' => [
        //     'class' => 'noam148\imagemanager\components\ImageManagerGetPath',
        //     //set media path (outside the web folder is possible)
        //     'mediaPath' => '@app/media/imagemanager',
        //     //path relative web folder. In case of multiple environments (frontend, backend) add more paths 
        //     //'cachePath' =>  ['assets/images'],
        //     //use filename (seo friendly) for resized images else use a hash
        //     'useFilename' => true,
        //     //show full url (for example in case of a API)
        //     'absoluteUrl' => false,
        //     'databaseComponent' => 'db' // The used database component by the image manager, this defaults to the Yii::$app->db component
        // ],
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // $config['modules']['imagemanager'] =  [
	// 	'class' => 'noam148\imagemanager\Module',
	// 	//set accces rules ()
	// 	'canUploadImage' => true,
	// 	'canRemoveImage' => function(){
	// 		return true;
	// 	},
	// 	'deleteOriginalAfterEdit' => false, // false: keep original image after edit. true: delete original image after edit
	// 	// Set if blameable behavior is used, if it is, callable function can also be used
	// 	'setBlameableBehavior' => false,
	// 	//add css files (to use in media manage selector iframe)
	// 	'cssFiles' => [
	// 		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css',
    //     ],
    // ];
}

return $config;
