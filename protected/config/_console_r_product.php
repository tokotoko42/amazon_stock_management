<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/console.php'),
    array(
        'params' => array(
            'csv_file_path' => 'csv',
            'csv_file_name' => 'stock_YYYYMMDDHHMMII.csv',
            'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6',
        ),
    )
);

