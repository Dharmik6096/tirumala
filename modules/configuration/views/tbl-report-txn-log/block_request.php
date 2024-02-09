<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

Url::remember();
$this->title = $title;
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>

    <div class="panel-body">
        <div class="large-search hidden-print">
            <?php echo $this->render('_search', ['searchModel' => $searchModel]); ?>
        </div>
        <div class="clearfix"></div>
        <div class="large-search hidden-print">
            <?php
            echo $this->render('block_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
            ?>
        </div>

    </div>
</div>
