<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Test controller 
*/
class TestController extends Controller {  
    public function actionIndex(){
		sleep(10);
		echo "ok";
		file_put_contents("/tmp/log/index.txt", "indexindexindexindex");
	}  
    public function actionFinish(){
		sleep(10);
		echo "ok";
		file_put_contents("/tmp/log/finish.txt", "finishfinishfinishfinish");
	}

}  
