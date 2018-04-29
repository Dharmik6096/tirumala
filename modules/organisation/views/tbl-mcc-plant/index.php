<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'MCC'));
if (Yii::$app->general->checkAccess('/organisation/tbl-mcc-plant/update')) {
    $this->params['menu'][] = Yii::$app->controls->add('MCC');
    $this->params['menu'][] = Yii::$app->controls->import('mcc-plant', $this);
}
?>
<div class="tbl-branch-index">
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