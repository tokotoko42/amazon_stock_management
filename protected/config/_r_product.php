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
            'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6',
            'csv_file_path' => 'csv',
            'csv_file_name' => 'stock_YYYYMMDDHHMMII.csv',
        ),
    )
);
