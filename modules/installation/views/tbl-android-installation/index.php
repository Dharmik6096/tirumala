<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'AMCS Installation'));
$this->params['menu'][] = Yii::$app->controls->add('AMCS Installation');
$this->params['menu'][] = Yii::$app->controls->custombutton('Add App Lock', 'tbl-app-lock-password/index', '', 'btn btn-danger btn-block', '<i class="fa fa-plus"></i>');
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
