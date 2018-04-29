<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Society'));
if (Yii::$app->general->checkAccess('/organisation/tbl-dcs/update')) {
    $this->params['menu'][] = Yii::$app->controls->add('Society');
    $this->params['menu'][] = Yii::$app->controls->import('dcs', $this);
}
?>
<div class="tbl-dcs-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>

        <div class="panel-body">
            <?=
            $this->render('_form_grid', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>
