<?php

namespace backend\controllers;

use Yii;

use backend\components\BaseController;
use backend\models\SiteFormModel;
use backend\models\SearchFormModel;
use backend\models\CompleFormModel;
use backend\components\Message;

use common\models\ApolloCheck;
use common\models\Deviate;


/**
 * Description of SystemController
 * @author wangjiacheng
 */
class SiteController extends BaseController {

    /**
     * 单页面显示
     */
    public function actions() {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => null,
                'backColor' => 0xffffff, //背景颜色
                'maxLength' => 4, //最大显示个数
                'minLength' => 4, //最少显示个数
                'padding' => 0, //间距
                'height' => 50, //高度
                'width' => 110, //宽度  
                'foreColor' => 0x000000, //字体颜色
                'offset' => 10, //设置字符偏移量 有效果
                'testLimit' => 1, //重试次数
                'fontFile' => '@yii/captcha/Jura.ttf'
            ],
        ];
    }

    public function actionIndex() {
       
//        $data = \common\models\Master::getCurrentRules();
        
//        Yii::error('切克闹1');
//        Yii::info('切克闹2');
//        Yii::warning('切克闹3');
//        Yii::trace('切克闹4');
        
//        dump($_SESSION);
        return $this->render('index');
    }
    
    public function actionDemo() {
        $model = new SiteFormModel();
        if (Yii::$app->request->isPost) {
//            dump($_POST);
        }
        return $this->render('demo', [
            'model' => $model
        ]);
    }
    
    public function actionSearch() {
        $model = new SearchFormModel();
        
        return $this->render('search', [
            'model' => $model
        ]);
    }
    
    public function actionComple() {
        $model = new CompleFormModel();
        
        return $this->render('comple', [
            'model' => $model
        ]);
    }
}
