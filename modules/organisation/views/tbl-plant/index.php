<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Plants'));
if (Yii::$app->general->checkAccess('/organisation/tbl-plant/update')) {
    $this->params['menu'][] = Yii::$app->controls->add('Plant');
    $this->params['menu'][] = Yii::$app->controls->import('plant', $this);
}
?>
<div class="tbl-plant-index">
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