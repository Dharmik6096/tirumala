<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Payment Transaction Approval'));
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money-bill"></i> ' . Yii::t('app', 'My Pending Approval'), ['/payment/tbl-payment-transaction-approval/pending-approval'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money-bill"></i> ' . Yii::t('app', 'My Pending Reinitiate'), ['/payment/tbl-payment-transaction-approval/pending-reinitiate'], true);
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