<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'GRN'));

$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$withoutDispatch = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'without_dispatch_grn', 'PORTAL');

if ($batchNoWiseInventory == 1) {
    if ($withoutDispatch == 1) {
        $this->params['menu'][] = Yii::$app->controls->add('grn');
    } else {
        $this->params['menu'][] = Yii::$app->controls->add('grn', 'create-other');
    }
//    $this->params['menu'][] = Yii::$app->controls->import('grn-other', $this);
} else {
    $this->params['menu'][] = Yii::$app->controls->add('grn');
    $this->params['menu'][] = Yii::$app->controls->import('grn', $this);
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
                'batchNoWiseInventory' => $batchNoWiseInventory,
            ])
            ?>
        </div>
    </div>
</div>
