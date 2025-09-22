<?php

use yii\helpers\Url;

Url::remember();
$this->title = 'Transporter Payment Disburse';
?>
<div class="tbl-member-payment-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <div class="grid-search large-search hidden-print">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
            <div class="clearfix"></div>
            <?php
                echo $this->render('disburse_payment_grid', ['model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
            ?>
        </div>
    </div>
</div>