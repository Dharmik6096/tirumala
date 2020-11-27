<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Asset SAP Code'));
$this->params['menu'][] = Yii::$app->controls->add('Asset SAP Code');
$this->params['menu'][] = Yii::$app->controls->add('SAP Code Movement', 'movement');
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
