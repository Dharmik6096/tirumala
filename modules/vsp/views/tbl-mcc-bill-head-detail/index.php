<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Bill Head Detail'));
$this->params['menu'][] = Yii::$app->controls->add('MCC Bill Head Transaction');
$this->params['menu'][] = Yii::$app->controls->import('mcc-bill-head-detail', $this, Yii::t('app', 'Bill Head Import'));
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