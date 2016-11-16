<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/console.php'),
    array(
        'params' => array(
            'csv_file_path' => '../csv',
            'csv_file_name' => 'stock_YYYYMMDDHHMMII.csv',
            'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6',
            'url' => 'https://www.amazon.co.jp/s/ref=sr_nr_p_n_availability_1?rh=n%3A2127209051%2Cn%3A2152000051%2Cn%3A2152002051%2Ck%3A%E3%83%97%E3%83%AA%E3%83%B3%E3%82%BF%E3%83%BC%2Cp_n_availability%3A2227307051&keywords=%E3%83%97%E3%83%AA%E3%83%B3%E3%82%BF%E3%83%BC&ie=UTF8&qid=1479153958',
        ),
    )
);

