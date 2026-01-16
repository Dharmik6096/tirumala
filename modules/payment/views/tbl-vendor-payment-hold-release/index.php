<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Vendor Payment Hold Release'));
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process Vendor Payment Hold Release'), ['/payment/tbl-vendor-payment-hold-release/create'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money"></i> ' . Yii::t('app', 'Disburse Vendor Payment Hold Release'), ['/payment/tbl-vendor-payment-hold-release/payment-disburse'], true);
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>
