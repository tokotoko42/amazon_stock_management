<?php
Yii::import('application.vendors.*');
mb_language("ja");
mb_internal_encoding('UTF-8'); 
require_once 'simple_html_dom.php';
ini_set('memory_limit', '1024M');

/*
 * Csv Download page
 *
 * @package Amazon stock management
 * @subpackage - 
 * @author Kamijo Tetsuya
 * @version 0.1.1
 * $Id$ 
 */
class DownloadController extends PCController {
    private $ch;
    private $url;
    private $log_id = "AMAZON-STOCK";
    private $logutil;
    private $fp;

    private $item;
    private $item_name;
    private $item_url;
    private $sold_out;
    private $asin;
    private $id;
    private $csv_file;
    
    private function initProcess() {
        $this->setPageTitle('Amazon Stock Management');
        $this->logutil = new LogUtil;
        $this->logutil->init();

        $this->csv_file = Yii::app()->params['csv_file_path'] . '/' . Yii::app()->params['csv_file_name'];
        
        // Rename
        $this->csv_file = preg_replace('/YYYYMMDDHHMMII/', date("YmdHis"), $this->csv_file);
        $this->fp = fopen($this->csv_file, "w");

        // ヘッダーを定義
        //fwrite($this->fp, '"ID","ASIN","ITEM_TYPE","ITEM_NAME","STOCK","ITEM_URL"' . "\n");
    }

    /**
     * Download operation
     */
    public function actionIndex() {
        // Validation check
        if (!$this->validationURL()) {            
            $this->template = 'error';
            return;
        }

        $this->initProcess();
        $msg = "CSVファイル作成処理を開始します";
        $this->logutil->setLog($this->log_id, "info", __CLASS__, __FUNCTION__, __LINE__, $msg);

        // Request amazon page
        $this->url = $this->getUrl();
        $html = $this->requestAmazon($this->url);

        // Get page count
        $page = $this->getPageCount($html);
        $msg = "ページ数は" . $page . "です";
        $this->logutil->setLog($this->log_id, "info", __CLASS__, __FUNCTION__, __LINE__, $msg);

        // Get response information
        $this->extractResponse($html);

        $msg = "初回ページのスクレイピングが完了しました";
        $this->logutil->setLog($this->log_id, "info", __CLASS__, __FUNCTION__, __LINE__, $msg);

        // Process clear
        $html->clear();
        curl_close($this->ch);

        // 次ページを解析する
        if ($page > 1) {
            for ($count = 2; $count <= $page; $count++) {
                // 次ページのURLを取得
                $url2 = $this->getUrlNext($count);

                // Request amazon page
                $html = $this->requestAmazon($url2);

                // Get response information
                $this->extractResponse($html);

                // Process clear
                $html->clear();
                curl_close($this->ch);
            }
        }

        $this->stash['csv_file'] = $this->csv_file;
        fclose($this->fp);
    }

    private function validationURL() {
        if (!preg_match("/^https/", $_POST['url'])) {
            return false;
        }
        return true;
    }
    
    private function writeRecord() {
        $record = '"' . $this->sold_out . '","' . $this->id . '","' . $this->asin . '","' . $this->item . '","' . html_entity_decode(urldecode($this->item_name)) . '","' . $this->item_url . '"' . "\n";
        fwrite($this->fp, $record);
    }

    
    private function getUrl() {
        return $_POST['url'];
    }

    private function getUrlNext($count) {
        return $this->url . '&page=' . $count;
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

    /**
     * ページ数を取得
     * @param Response結果
     * @return ページ数
     */
    private function getPageCount($html) {
        foreach($html->find('div') as $parts) {
            foreach($parts->find("span") as $element) {
                if ($element->class === "pagnDisabled") {
                    return preg_replace('/[^0-9]/', '', $element);
                }
            }
        }
    }

    /**
     * アマゾンへリクエストを送信する
     * @param URL
     * @return Response結果
     */
    private function requestAmazon($url) {
        // ログインリクエストを送信
        $this->ch = curl_init();
        curl_setopt_array($this->ch, array(
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => Yii::app()->params['user_agent'],
            CURLOPT_RETURNTRANSFER => true,
        ));
     
        $msg = 'アマゾンにリクエストを送信しました。URL = ' . $url;
        $this->logutil->setLog($this->log_id, 'info', __CLASS__, __FUNCTION__, __LINE__, $msg);

        return str_get_html($this->exec());
    }

    /**
     * レスポンスのスクレイピング
     * @param レスポンス結果
     * @return スクレイピング解析判定
     */
    private function extractResponse($html) {
        // １商品の大枠が<li>タグで構成されている
        foreach($html->find("li") as $parts) {
            // 在庫抽出
            $this->extractStock($parts);

            // 商品名および商品URL抽出
            if (!$this->extractItemInfo($parts)) {
                continue;
            }
            
            // ASINおよび商品型番抽出
            if (!$this->extractAsinAndItem($parts)) {
                continue;
            }

            // Create CSV file
            $this->writeRecord();
        }
    }

    /**
     * 在庫抽出
     * @param 商品用スクレイピング結果
     * @return 
     */
    private function extractStock($parts) {
        foreach($parts->find("span") as $element) {
            if ($element->class === "a-size-small a-color-secondary") {
                // 在庫切れ文字が含まれている場合、必ずUTF-8となる
                if (mb_detect_encoding($element) === "UTF-8") {
                    $pattern = "/在庫切れ/";
                    $pattern = mb_convert_encoding($pattern, "UTF-8", "auto");

                    //「在庫切れ」文字が含まれているか、判定
                    // 商品在庫切れ  ->  1
                    // 商品在庫あり  ->  0
                    if (preg_match($pattern, $element)) {
                        $this->sold_out = 1;
                    } else {
                        $this->sold_out = 0;
                    }
                }
            }
        }
    }

    /**
     * 商品名および商品URL抽出
     * @param 商品用スクレイピング結果
     * @return bool
     */
    private function extractItemInfo($parts) {
        foreach($parts->find("a") as $element) {
            if (isset($element->title)) {
                $this->item_name = $element->title;
            }
            if (isset($element->href)) {
                $this->item_url = $element->href;
            }
        }

        // 商品URLが含まれていない場合、スキップ
        if (!preg_match("/^https/", $this->item_url)) {
            return false;
        }
        return true;
    }

    /**
     * ASINおよび商品型番抽出
     * @param 商品用スクレイピング結果
     * @return bool
     */
    private function extractAsinAndItem($parts) {
        if (isset($parts->id) && isset($this->item_name)) {
            // idの抽出
            $this->id = $parts->id;

            // ASINの抽出
            $asin_tmp = explode("\"", $parts);
            $this->asin = $asin_tmp[3];

            // 商品の型番を抽出する
            $items = explode("-", $this->item_name);
            // ハイフン(-)が存在しない商品名は型番がないものとみなす
            if (count($items) > 1) {
                // 型番を抽出する
                $item_type1_tmp = explode(" ", $items[0]);
                $item_type1 = end($item_type1_tmp);
                $item_type2_tmp = explode(" ", $items[1]);
                $item_type2 = $item_type2_tmp[0];
                $item_tmp = $item_type1 . '-' . $item_type2;
                // 日本語を削除する
                $this->item = preg_replace("/&#\d+;/", "", $item_tmp);
            }

        // id, 商品名が取れていなかったらスキップ
        } else {
            return false;
        }
        return true;
    }

    /**
     * Download operation
     */
    public function actionDown() {
        $csvfile = $_GET["csv"];
        $file1 = "../work2/" . $csvfile;
        $file2 = "../work2/csv/header";
        $file3 = "../work2/csv/amazon_stock_list.csv";
        $fp2 = fopen($file2, "w");
        fwrite($fp2, '"ID","ASIN","ITEM_TYPE","ITEM_NAME","STOCK","ITEM_URL"' . "\n");
        fclose($fp2);

        # Sort csv file
        shell_exec("sort -t, -k1 -k2r -u $file1 -o $file1");

        $fp3 = fopen($file3, "wb");
        $this->join_f($fp3, $file2);
        $this->join_f($fp3, $file1);
        fclose($fp3);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file3));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file3));
//        ob_clean();
        flush();
        readfile ($file3);

    }

    public function join_f($fp_dest, $name)
    {
        $fp = fopen($name, "rb");
        flock($fp, LOCK_EX);
        while (false !== ($char = fgetc($fp))) fwrite($fp_dest, $char);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

}
