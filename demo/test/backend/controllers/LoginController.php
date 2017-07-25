<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;

class LoginController extends Controller {
    
    public $layout = 'login';
    
    public function init() {
        parent::init();
    }


    public function actionIndex() {
        
        return $this->render('login' );
    }
    /**
     * 异步调用
     * @return [type] [description]
     */
    public function actionDo() {
        $data = [
          "data"=>[["a" => "test/index"]],
          "finish"=>[["a" => "test/finish"]]
        ];
        $res = \Yii::$app->swooleasync->async(json_encode($data));
        if($res !== false){
            echo "异步处理成功发送";
        }else{
            echo "异步处理请求发送失败";
        }
    }

}
