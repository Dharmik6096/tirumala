<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

Url::remember();
$this->title = $title;
?>
<div class="tbl-member-payment-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <div class="grid-search large-search">
                <?php echo $this->render('_disburse_search', ['model' => $searchModel]); ?>
            </div>
            <div class="clearfix"></div>
            <?php
            echo $this->render('_disburse_form_grid_export', ['model' => $searchModel, 'dataProvider' => $dataProvider]);
            ?>
        </div>
    </div>
</div>