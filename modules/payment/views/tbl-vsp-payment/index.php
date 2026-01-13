<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Vendor Payment'));
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process Vendor Payment'), ['/payment/tbl-vsp-payment/create'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process Stop Vendor Payment'), ['/payment/tbl-vsp-payment/create-stop-payment'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money"></i> ' . Yii::t('app', 'Disburse Vendor Payment'), ['/payment/tbl-vsp-payment/payment-disburse'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process Remunaration Payment'), ['/payment/tbl-remuneration-summary/create'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process Stop Remunaration Payment'), ['/payment/tbl-remuneration-summary/create-stop-payment'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money"></i> ' . Yii::t('app', 'Disburse Remunaration Payment'), ['/payment/tbl-remuneration-summary/payment-disburse'], true);

$url = ['/payment/tbl-vsp-payment/vendor-payment-import'];
$this->params['menu'][] = Yii::$app->controls->import('vendor-billing-bulk', $this, Yii::t('app', 'Vendor Billing Import Data'), [], '', $url);
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
