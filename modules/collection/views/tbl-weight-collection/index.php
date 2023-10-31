<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'BMC Weight Data'));
//$this->params['menu'][] = Yii::$app->controls->add('BMC Weight Data');
$this->params['menu'][] = Yii::$app->controls->custombutton('Bulk Delete Collection', 'delete-bulk-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
$this->params['menu'][] = Yii::$app->controls->import('bmc-weight-collection', $this);
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

