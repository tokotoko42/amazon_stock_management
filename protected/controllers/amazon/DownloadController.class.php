<?php
Yii::import('application.vendors.*');
require_once 'simple_html_dom.php';

/*
 * Csv Download page
 *
 * @package Amazon stock management
 * @subpackage - 
 * @author Kamijo Tetsuya
 * @version 0.0.1
 * $Id$ 
 */
class DownloadController extends PCController
{
    private $ch;
    private $url;
    private $log_id = "CSVDOWN";
    private $logutil;
    
    /**
     * Download operation
     */
    public function actionIndex()
    {
        $this->setPageTitle('Amazon Stock Management');
        $this->logutil = new LogUtil;
        $this->logutil->init();

        // Request amazon page
        $this->url = $this->getUrl();
        $html = $this->requestAmazon();
        
        // Get page count
        $page = $this->getPageCount($html);
        echo $page;
        $html->clear();
//        echo $page;

        /*
        for ($count = 2; $count <= $page; $count++) {
            $this->url = $this->getUrlNext($count);
            //echo $this->url . '<br>';
        }

        // Create CSV
        foreach($html->find("li") as $parts) {
            // Asinコード抽出
            foreach($parts->find("span") as $element1) {
                if (isset($element1->name)) {
                    $asin = $element1->name;
                }
            }

            // 商品タイトル抽出
            foreach($parts->find("a") as $element2) {
                if (isset($element2->title)) {
                    $item_name = $element2->title;
                }
                if (isset($element2->href)) {
                    $item_url = $element2->href;
                }
            }
            
            if (isset($parts->id) && isset($asin) && isset($item_name)) {
                echo $parts->id . ' : ' . $asin . ' : ' . $item_name . '<br>';
            }
        }
*/        
        // Get next page
        
        // Update CSV
        
        // Save csv file
    }
    
    private function getUrl() {
        return $_POST['url'];
    }

    private function getUrlNext($count) {
        return $this->url . '&page=' . $count;
    }
    
    private function getPageCount($html) {
        foreach($html->find('div') as $parts) {
            foreach($parts->find("span") as $element) {
                if ($element->class === "pagnDisabled") {
                    return $element;
                }
            }
        }
    }
    
    /**
     * リクエスト送信用コンポーネント
     * @param 
     * @return Response結果
     */
    private function exec() {
        $ret = curl_exec($this->ch);
        return $ret;
    }
    
    private function requestAmazon() {
        // ログインリクエストを送信
        $this->ch = curl_init();
        curl_setopt_array($this->ch, array(
            CURLOPT_URL => $this->url,
            CURLOPT_USERAGENT => Yii::app()->params['user_agent'],
            CURLOPT_RETURNTRANSFER => true,
        ));
     
        $msg = 'アマゾンにリクエストを送信しました。URL = ' . $this->url;
        $this->logutil->setLog($this->log_id, 'info', __CLASS__, __FUNCTION__, __LINE__, $msg);

        return str_get_html($this->exec());
    }
}
