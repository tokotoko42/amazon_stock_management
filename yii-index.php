<?php

error_reporting(E_ALL|E_STRICT);
// detect environment
$yii = 'protected/yii/framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/_r_product.php';
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 0);

require_once($yii);
Yii::createWebApplication($config)->run();
