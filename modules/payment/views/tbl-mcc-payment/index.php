<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'MCC Payment'));
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process MCC Payment'), ['/payment/tbl-mcc-remuneration-summary/create'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money"></i> ' . Yii::t('app', 'Disburse MCC Payment'), ['/payment/tbl-mcc-remuneration-summary/payment-disburse'], true);
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
