<?php
$this->title = Yii::$app->label->title('list', 'DCS Wise Billing Config');
$this->params['menu'][] = Yii::$app->controls->add('DCS Wise Billing Config');
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