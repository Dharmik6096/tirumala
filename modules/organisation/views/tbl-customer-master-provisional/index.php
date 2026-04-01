<?php

if ($pending_approval) {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Customer Pending Approval'));
} else {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Customer'));
    $this->params['menu'][] = Yii::$app->controls->add('Provisional Customer');
    $this->params['menu'][] = Yii::$app->controls->custombutton('Update SAP Error Data', 'sap-error-data-list', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil"></i>');
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
            'pending_approval' => $pending_approval
        ])
        ?>
    </div>
</div>
