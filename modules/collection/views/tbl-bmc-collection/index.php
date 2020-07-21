<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'BMC Collection'));
$this->params['menu'][] = Yii::$app->controls->add('BMC Collection');
$this->params['menu'][] = Yii::$app->controls->custombutton('Update BMC Collection', 'update-bmc-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete BMC Collection', 'delete-bmc-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
$this->params['menu'][] = Yii::$app->controls->import('bmc-collection', $this);
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
