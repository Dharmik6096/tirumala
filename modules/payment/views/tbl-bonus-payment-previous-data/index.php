<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Bonus Payment - Previous Data'));
$this->params['menu'][] = Yii::$app->controls->import('bonus-payment-previous-data', $this, Yii::t('app', 'Bonus Payment - Previous Data Import'));
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