<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Route Mapping'));
if (Yii::$app->general->checkAccess('/organisation/tbl-route-mapping/create')) {
    $this->params['menu'][] = Yii::$app->controls->add('Route Mapping');
    $this->params['menu'][] = Yii::$app->controls->custombutton('Delete Map Route', 'delete-map-route', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
    $this->params['menu'][] = Yii::$app->controls->import('route-master', $this);
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