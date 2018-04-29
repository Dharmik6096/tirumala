<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Vehicle'));
if (Yii::$app->general->checkAccess('/organisation/tbl-vehicle-master/update')) {
    $this->params['menu'][] = Yii::$app->controls->add('Vehicle');
}
?>
<div class="tbl-vehicle-master-index">
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