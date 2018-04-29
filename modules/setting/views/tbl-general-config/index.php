<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'General Configuration'));
$count = $model->getNoOfData();
$data = $model->getData();
if ($count == 0) {
    $this->params['menu'][] = Yii::$app->controls->add('General Configuration');
} else {
    $this->params['menu'][] = Yii::$app->controls->update($data->config_code);
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