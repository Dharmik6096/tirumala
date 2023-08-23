<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use kartik\grid\GridView;
?>
<div id="maincontent">
    <?=
    $this->render('_main_form', ['model' => $model, 'type' => 'create', 'txModel' => $txModel])
    ?>
</div>



