<?php

use backend\assets\LoginAsset;
use yii\helpers\Html;

LoginAsset::register($this);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<style>
        .ibar {display: none;}
        .gt_ajax_tip .ready{right: -56px;}
</style>

<body class="login-bg">
    <?php $this->beginBody() ?>
    
    <?= $content ?>
    
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>