<?php

namespace backend\components;

use Yii;
use backend\components\Message;
use backend\components\RbacManage;

use common\models\AuthMenu;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 基类控制器
 * @author wangjiacheng
 */
class BaseController extends \common\components\BaseController {
    
    const STATUS_SUCCESS = 0;           // 操作成功的状态
    const STATUS_ERROR = 100;             // 操作失败的状态
    
    const MSG_SUCCESS = '操作成功!';    // 成功提示
    const MSG_ERROR = '操作失败!';       // 失败提示

    /**
     * 每页显示数量
     */
    protected $_pageSize = 50;

    /**
     * 主布局文件
     * @var type 
     */
    public $layout = '';

    /**
     * 面包屑数据
     * @var type 
     */
    public $breadcrumbs = [];

    /**
     * 初始化
     */
    public function init() {
        $this->layout = $this->getLayoutView();
    }

    /**
     * 执行前操作
     * @param type $action
     * @return boolean
     */
    public function beforeAction($action) {
        if(parent::beforeAction($action)){ 
            if (Yii::$app->user->isGuest) {
                $this->redirect(['login/index']);
                return false;
            }
            if (!Yii::$app->rbacManage->checkAuth()) {
                if (empty(Yii::$app->getRequest()->referrer)) {
                    Yii::$app->user->logout();
                    Yii::$app->getResponse()->redirect(['login/index']);
                    return false;
                } else {
                    Message::set(Message::ALERTS_WARNING, '没有权限访问当前操作,操作: '. Yii::$app->controller->id. '/'. Yii::$app->controller->action->id);
                    $this->redirect(Yii::$app->getRequest()->referrer);
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * 执行后操作
     * @param type $action
     * @param type $result
     * @return type
     */
    public function afterAction($action, $result) {
        return parent::afterAction($action, $result);
    }

    /**
     * 关闭控制器布局器
     */
    protected function closeLayout() {
        $this->layout = false;
    }
    
    /**
     * 设置布局
     * @param type $layout  布局
     */
    protected function setLayout($layout) {
        $this->layout = $layout;
    }
    
    /**
     * 设置分页大小
     * @param type $pageSize  页大小
     */
    protected function setPageSize($pageSize) {
        $this->_pageSize = $pageSize;
    }

    /**
     * 获取主布局文件
     * @return string
     */
    protected function getLayoutView() {
        return 'main';
    }
    
    /**
     * 返回json数据
     * @param type $data        返回数据
     * @param type $msg         消息说明
     * @param type $status      状态值
     */
    protected function renderJson($data, $msg = '', $status = 0) {
        header('Content-type:application/json;charset=utf-8');
        return json_encode(['status' => intval($status), 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 渲染内容
     * @param type $view        视图名
     * @param type $params      参数
     * @return type
     */
    public function render($view, $params = []) {
        if (Yii::$app->getRequest()->isPjax) {
            return Yii::$app->message->showNotice(). parent::renderAjax($view, $params);
        } else {
            return parent::render($view, $params);
        }
    }
    
    /**
     * 取得分页大小
     * @return type
     */
    public function getPageSize() {
        return Yii::$app->request->isPjax && isset($_GET['per-page']) ? intval($_GET['per-page']): $this->_pageSize;
    }
}
