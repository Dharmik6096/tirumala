<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Dispatch'));
$this->params['menu'][] = Yii::$app->controls->add('Milk Dispatch');
$this->params['menu'][] = Yii::$app->controls->custombutton('Update Milk Dispatch', 'update-milk-dispatch', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil-alt"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete Milk Dispatch', 'delete-milk-dispatch', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
$this->params['menu'][] = Yii::$app->controls->import('milk-dispatch', $this);
?>
<div class="tbl-banks-index">
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

