<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Payment Cycle'));
$this->params['menu'][] = Yii::$app->controls->add('Payment Cycle');
$this->params['menu'][] = Yii::$app->controls->custombutton('Bulk Data Lock/Unlock', 'bulk-data-lock-unlock', '', 'btn btn-danger btn-block', '<i class="fa fa-lock"></i>');
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
