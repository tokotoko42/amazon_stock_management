<?php
// 定数ファイル
Yii::import('application.const.*');
class LogUtil
{
    public  $request_id = '';
    public function init()
    {
        // 定数ファイル
        Yii::import('application.const.*');
        // ログ出力用 リクエスト固有ID生成
        $this->request_id = md5(time(). rand(0, 10000));
    }
    
    public function setLog($log_id, $log_level, $cls, $fnc, $line, $message)
    {
        $log = array();
        $log[] = $this->request_id;
        $log[] = $log_id;
        $log[] = $cls;
        $log[] = $fnc;
        $log[] = $line;
        $log[] = $message;

        Yii::log(implode("\t", $log), $log_level);
    }

}
