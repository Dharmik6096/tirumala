
<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Party Payment'));
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-life-ring"></i> ' . Yii::t('app', 'Process Party Payment'), ['/payment/tbl-party-payment/create'], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money"></i> ' . Yii::t('app', 'Disburse Party Payment'), ['/payment/tbl-party-payment/payment-disburse'], true);
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

