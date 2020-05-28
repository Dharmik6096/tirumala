<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Rate Recalculation'));
$this->params['menu'][] = Yii::$app->controls->add('Rate Recalculation');
$this->params['menu'][] = Yii::$app->controls->add('Custom Rate Recalculation', ['create-recalc']);
$this->params['menu'][] = Yii::$app->controls->add('Dispatch Rate Recalculation', ['dcs-dispatch']);
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

