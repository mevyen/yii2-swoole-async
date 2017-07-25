<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\components\Captcha;
use yii\helpers\Url;


?>
<div class="main ">
		<!--登录-->
		<div class="login-dom login-max">
			<div class="login container " id="login">
				<input type="button" onclick="doAction()" class="btn text-center login-btn" value="异步处理测试">
			</div>
		</div>
		<div class="popupDom">
			<div class="popup text-default">
			</div>
		</div>
	</div>

<script type="text/javascript">
 
    function doAction(){
        $.post('/login/do/',{},function(data){
            popup_msg(data);
        });
    }
	function popup_msg(msg){
        $(".popup").html(msg);
        $(".popupDom").animate({
                "top": "0px"
        }, 400);
        setTimeout(function() {
            $(".popupDom").animate({
                    "top": "-40px"
            }, 400);
        }, 2000);
	}
        
</script>