<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Route Mapping'));
if (Yii::$app->general->checkAccess('/organisation/tbl-route-mapping/create')) {
    $this->params['menu'][]=Yii::$app->controls->add('Route Mapping');
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
            ])
            ?>
        </div>
    </div>
</div>