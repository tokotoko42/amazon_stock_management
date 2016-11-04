<?php

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Amazon stock management',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.BaseModel',
        'application.models.*',
        'application.components.*',
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'stockmanagement',
             // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1','192.168.*'),
        ),
    ),

    // application components
    'components'=>array(
        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
            ),
            'showScriptName' => false,
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'RFileLogRoute',
                    'levels'=>'error, warning, profile, info, trace',
                    'logPath'=>'log',
                    'logFile'=>'application',
                ),
            ),
        ),
        'viewRenderer' => array(
            'class'=>'ext.yiiext.renderers.dwoo.EDwooViewRenderer',
            'fileExtension' => '.tpl',
        ),
        'securityManager'=>array(
            'validationKey'=>'ij39gfiekjbda0c0a4333db885aa85a8a',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'version'=>date('YmdHi'),
        'year'=>date('Y'),
    ),
);
