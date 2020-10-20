<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collections'));
$this->params['menu'][] = Yii::$app->controls->add('Milk Collection');
$this->params['menu'][] = Yii::$app->controls->custombutton('Update Milk Collection', 'update-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete Milk Collection', 'delete-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
$this->params['menu'][] = Yii::$app->controls->import('milk-collection-bulk', $this);
$this->params['menu'][] = Yii::$app->controls->custombutton('Online Farmer', 'online-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-map-marker"></i>');
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
