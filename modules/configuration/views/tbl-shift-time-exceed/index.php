<?php
if ($pending_approval) {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Shift Time Exceed Provision Approval'));
} else {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Shift Time Exceed Provision'));
    $this->params['menu'][] = Yii::$app->controls->add('Shift Time Exceed Provision');
    $this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-check"></i> ' . Yii::t('app', 'Shift Time Exceed Provision Approval'), ['/configuration/tbl-shift-time-exceed/pending-approval'], true);
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
            'pending_approval' => $pending_approval,
        ])
        ?>
    </div>
</div>
