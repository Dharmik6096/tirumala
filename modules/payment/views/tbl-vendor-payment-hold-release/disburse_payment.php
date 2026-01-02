<?php

use yii\helpers\Url;

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
            echo $this->render('disburse_payment_grid', ['model' => $searchModel, 'dataProvider' => $dataProvider]);
            ?>
        </div>
    </div>
</div>