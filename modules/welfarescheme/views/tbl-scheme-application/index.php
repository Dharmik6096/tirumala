<?php
if ($pending_approval) {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Scheme Application Pending Approval'));
} else {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Scheme Application'));
    $this->params['menu'][] = Yii::$app->controls->add('Scheme Application');
}
?>
<div class="tbl-banks-index">
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
</div>
