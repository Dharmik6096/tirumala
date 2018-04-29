<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'rate formula'));
if (Yii::$app->general->checkAccess('/dcsoperation/formula-master/create')) {
    $this->params['menu'][] = Yii::$app->controls->add('rate formula');
    //$this->params['menu'][] = Yii::$app->controls->import('rate-formula', $this);
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
        ])
        ?>
    </div>
</div>
