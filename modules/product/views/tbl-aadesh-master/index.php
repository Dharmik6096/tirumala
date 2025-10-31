<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Aadesh Master'));
$this->params['menu'][] = Yii::$app->controls->add('Aadesh Master');
$this->params['menu'][] = Yii::$app->controls->custombutton('Bulk Delete Applicability', 'delete-bulk-applicability', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
$this->params['menu'][] = Yii::$app->controls->import('aadesh_master_bulk', $this);
$this->params['menu'][] = Yii::$app->controls->import('aadeshmasterapplicability-bulk', $this, Yii::t('app', 'Applicability Import'), [], 'aadesh_master_applicability');
$this->params['menu'][] = Yii::$app->controls->custombutton('Aadesh Patra', '//misreports/reports/aadesh-latter', '', 'btn btn-danger btn-block', '<i class="fa fa-file-pdf-o"></i>');
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
