<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

Url::remember();
$this->title = $title;
?>
<div class="tbl-vsp-payment-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <div class="large-search hidden-print">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
            <div class="clearfix"></div>
            <?php
            echo $this->render('@app/modules/payment/views/tbl-mcc-payment/disburse_payment_grid', ['model' => $searchModel, 'dataProvider' => $dataProvider]);
            ?>
        </div>
    </div>
</div>