<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'BMC Collection'));
$this->params['menu'][] = Yii::$app->controls->add('BMC Collection');
$this->params['menu'][] = Yii::$app->controls->custombutton('Update BMC Collection', 'update-bmc-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete BMC Collection', 'delete-bmc-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');

$mappedBmc = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'pouring_bmc_collection', 'PORTAL');

$allowRouteSelection = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'allow_route_selection', 'PORTAL');

$allowCanSelection = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'allow_can_selection', 'PORTAL');

$key = 'bmc-collection-bulk';
//100
if ($mappedBmc == 1 && $allowRouteSelection != 1 && $allowCanSelection != 1) {
    $key = 'bmc-mapped-collection-bulk';
}
//010
if ($mappedBmc != 1 && $allowRouteSelection == 1 && $allowCanSelection != 1) {
    $key = 'bmc-collection-bulk-route';
}
//001
if ($mappedBmc != 1 && $allowRouteSelection != 1 && $allowCanSelection == 1) {
    $key = 'bmc-collection-bulk-can';
}
//110
if ($mappedBmc == 1 && $allowRouteSelection == 1 && $allowCanSelection != 1) {
    $key = 'bmc-collection-bulk-bmc-route';
}
//101
if ($mappedBmc == 1 && $allowRouteSelection != 1 && $allowCanSelection == 1) {
    $key = 'bmc-collection-bulk-bmc-can';
}
//011
if ($mappedBmc != 1 && $allowRouteSelection == 1 && $allowCanSelection == 1) {
    $key = 'bmc-collection-bulk-route-can';
}
//111
if ($mappedBmc == 1 && $allowRouteSelection == 1 && $allowCanSelection == 1) {
    $key = 'bmc-collection-bulk-bmc-route-can';
}
$this->params['menu'][] = Yii::$app->controls->import($key, $this);
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
