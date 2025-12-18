<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Medicine Stock'));
$this->params['menu'][] = Yii::$app->controls->import('medicine-stock', $this);
$this->params['menu'][] = Yii::$app->controls->custombutton('Medicine Stock Transfer', 'create', '', 'btn btn-danger btn-block', '<i class="fa fa-exchange"></i>');

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