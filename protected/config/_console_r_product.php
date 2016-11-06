<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/console.php'),
    array(
        'params' => array(
            'csv_file_path' => '../csv',
            'csv_file_name' => 'stock_YYYYMMDDHHMMII.csv',
            'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6',
            'url' => 'https://www.amazon.co.jp/s/ref=sr_nr_n_0?fst=as%3Aoff&rh=n%3A3828871%2Cn%3A124048011%2Cn%3A3895791%2Cn%3A4083091%2Cn%3A4083101%2Ck%3A%E6%8E%83%E9%99%A4%E6%A9%9F%2Cp_n_availability%3A2227307051&keywords=%E6%8E%83%E9%99%A4%E6%A9%9F&ie=UTF8&qid=1477669858&rnid=3839151',
        ),
    )
);

