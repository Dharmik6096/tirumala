<!--header starts-->
<?php

use yii\helpers\Url;
?>

<div class="navbar navbar-fixed-top menu-wrap">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= Url::to(['/site/dashboard']) ?>">PCDF</a>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse">
            <?php require_once('tpl_navigation.php'); ?>
        </div>
    </div>
</div>