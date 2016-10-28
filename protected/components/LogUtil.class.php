<?php
// 定数ファイル
Yii::import('application.const.*');
class LogUtil
{
    public static function setLog($log_id, $log_level, $req_id, $cls, $fnc, $line, $message)
    {
        $log = array();
        $log[] = $req_id;
        $log[] = $log_id;
        $log[] = $cls;
        $log[] = $fnc;
        $log[] = $line;
        $log[] = $message;

        Yii::log(implode("\t", $log), $log_level);
    }

}
