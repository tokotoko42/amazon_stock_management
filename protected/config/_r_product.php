<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'components'=>array(
            'log' => array(
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'info, debug',
                        'logFile'=>'debug.log',
                    ),
                )
            ),
        ),
        'params' => array(
        ),
    )
);
