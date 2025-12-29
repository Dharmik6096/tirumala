<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Member Payment'));
$session_union = Yii::$app->session->get('Unions');
$allow_stop_payment_member = isset(Yii::$app->session->get('unionConfig')[$session_union]['allow_stop_payment_member']) ? Yii::$app->session->get('unionConfig')[$session_union]['allow_stop_payment_member'] : 0;
$milk_short_recovery_member = isset(Yii::$app->session->get('unionConfig')[$session_union]['milk_short_recovery_member']) ? Yii::$app->session->get('unionConfig')[$session_union]['milk_short_recovery_member'] : 0;

$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process Farmer Payment'), ['/payment/tbl-member-payment/create-payment'], true);
if ($allow_stop_payment_member == '1') {
    $this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process Stop Payment'), ['/payment/tbl-member-payment/create-stop-payment'], true);
}
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money"></i> ' . Yii::t('app', 'Disburse Farmer Payment'), ['/payment/tbl-member-payment/member-payment-disburse'], true);
$url = ['/payment/tbl-member-payment/member-payment-import'];
$this->params['menu'][] = Yii::$app->controls->import('member-billing-bulk', $this, Yii::t('app', 'Member Billing Import Data'), [], '', $url);
if ($milk_short_recovery_member == '1') {
    $this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-upload"></i> ' . Yii::t('app', 'Export Farmer Shortage Recovery'), ['/misreports/reports/member-payment-shortage-recovery'], true, '', '', '_blank');
    $this->params['menu'][] = Yii::$app->controls->import('member-payment-shortage-recovery-bulk', $this, Yii::t('app', 'Import Farmer Shortage Recovery'));
}
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

