<?php
/**
 * Controller は各コントローラに継承されるBase Controller
 */
class Controller extends CController
{
    public $layout = '//layouts/column1';
    public $menu = array();
    public $breadcrumbs = array();

    public $isRendered = false;
    public $forceRender = false;
    public $notRender = false;
    public $template;
    public $fillin = array();
    public $stash = array();
    public $is_debug = false;
    public $is_stage = false;
    public $is_develop = false;

    public $_page_code = '';
    public $_page_name = '';
    public $csrfTokenName = 'postkey';
    public $is_confirm = false;
    private $_csrfToken;

    const POST_PARAMETER_MAX = 70;
    const GET_PARAMETER_MAX = 30;

    public function __construct($id, $module=null)
    {
        $this->is_debug = true;
        return parent::__construct($id, $module);
    }

    /**
     * @param string $template
     * @param array $data
     * @param boolean $return
     * @return string
     */
    public function render($template=null, $data=null, $return=false)
    {
        $template = empty($template) ? $this->template : $template;
        $data = is_null($data) ? $this->stash : $data;
        $res = parent::render($template, $data, true);
        $this->isRendered = true;

        if (!empty($this->fillin)) {
            // run HTML_FillInForm
            require_once Yii::getPathOfAlias('application.vendors') . DIRECTORY_SEPARATOR . 'HTML_FillInForm.php';
            $ff = new HTML_FillInForm;
            $res = $ff->fill(array(
                'scalar' => $res,
                'fdat'   => $this->fillin,
            ));
        }

        if ($return) {
            return $res;
        } else {
            echo $res;
            return;
        }
    }

    /**
     * @param CAction $action
     * @return boolean
     */
    public function beforeAction($action)
    {
        $is_secure_access = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off' ? true : false;

        $cid = Yii::app()->controller->id;
        $aid = Yii::app()->controller->action->id;

        // access log
        $log = array();
        $log[] = "URL: ".Yii::app()->request->url;
        $log[] = "Headers: ".@json_encode(apache_request_headers());
        $log[] = "GET Parameters: ".@json_encode($_GET);
        if (Yii::app()->controller->id=='api' && Yii::app()->controller->action->id=='registImage') {
            $post = array();
            foreach ($_POST as $k=>$v) {
                if (strpos($k, 'img')===0) {
                    $post[$k] = 'length='.strlen($v);
                } else {
                    $post[$k] = $v;
                }
            }
            $log[] = "POST Parameters: ".@json_encode($post);
        } else {
            $log[] = "POST Parameters: ".@json_encode($_POST);
        }
        Yii::log(implode("\n", $log), 'secure.info', "controllers.".Yii::app()->controller->id.".".Yii::app()->controller->action->id.".request");

        $this->template = $action->getId();

        header('Cache-Control: no-cache');
        return true;
    }

    /**
     * @param CAction $action
     * @return boolean
     */
    public function afterAction($action)
    {
        if ((!$this->isRendered || $this->forceRender) && !$this->notRender) {
            return $this->render();
        } else {
            return true;
        }
    }

    public function dump($obj)
    {
        require_once('Var_Dump.php');
        Var_Dump::displayInit(array('display_mode' => 'HTML4_Table'));
        Var_Dump::display($obj);
        exit;
    }

    public function showBlankGif()
    {
        header("Content-type: image/gif");
        echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
        exit;
    }

    public function not_found()
    {
        throw new CHttpException(404,'The requested page does not exist.');
    }

    public function getCsrfToken()
    {
        if($this->_csrfToken===null)
        {
            $session = Yii::app()->session;
            $csrfToken=$session->itemAt($this->csrfTokenName);
            if($csrfToken===null)
            {
                $csrfToken = sha1(uniqid(mt_rand(),true));
                $session->add($this->csrfTokenName, $csrfToken);
            }
            $this->_csrfToken = $csrfToken;
        }
        return $this->_csrfToken;
    }

    public function validateCsrfToken($token = null)
    {
        if ($token === null) {
            $token = Yii::app()->request->getParam($this->csrfTokenName);
        }
        $is_valid = false;
        $session = Yii::app()->session;
        if ($session->contains($this->csrfTokenName) && $token)
        {
            if ($session->itemAt($this->csrfTokenName) === $token) {
                $is_valid = true;
            }
        }
        if (!$is_valid) {
            throw new CHttpException(400, 'エラーが発生しました');
        }
        return true;
    }
    private function checkParameter()
    {
        if (!empty($_POST)) {
            if (count($_POST) > self::POST_PARAMETER_MAX) {
                throw new CHttpException(400, '不正なパラメータです。');
            }
            foreach ($_POST as $_p) {
                if (is_array($_p)) {
                    throw new CHttpException(400, '不正なパラメータです。');
                }
            }
        }
        if (!empty($_GET)) {
            if (count($_GET) > self::GET_PARAMETER_MAX) {
                throw new CHttpException(400, '不正なパラメータです。');
            }
            foreach ($_GET as $_g) {
                if (is_array($_g)) {
                    throw new CHttpException(400, '不正なパラメータです。');
                }
            }
        }
    }
}
