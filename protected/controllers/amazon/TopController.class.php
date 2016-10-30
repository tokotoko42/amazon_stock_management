<?php
/*
 * Top Page Controller
 *
 * @package Amazon Stock Management
 * @subpackage
 * @author Kamijo Tetsuya
 * @version $Revision$
 * $Id$
 */
class TopController extends PCController
{
    private $log_id = "TOP";
    private $logutil;

    /**
     * Top Page
     */
    public function actionIndex()
    {
        // 共通処理
        $this->setPageTitle('Amazon Stock Management');
        $this->logutil = new LogUtil;
        $this->logutil->init();
        
        $msg = "トップページへアクセスしました";
        $this->logutil->setLog($this->log_id, "info", __CLASS__, __FUNCTION__, __LINE__, $msg);
    }
}
