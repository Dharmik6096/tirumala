<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Insurance Detail'));
$this->params['menu'][] = Yii::$app->controls->add('Member Detail');
$this->params['menu'][] = Yii::$app->controls->custombutton('Import Data', 'create-insurance-detail', '', 'btn btn-danger btn-block', '<i class="fa fa-download"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton(Yii::t('app', 'DCS'). ' Wise Import Data', 'dcs-wise-import', '', 'btn btn-danger btn-block', '<i class="fa fa-download"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Member Publish/Finalize', 'publish-finalize', '', 'btn btn-danger btn-block', '<i class="fa fa-upload"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Extend Edit Date', ['/insurance/tbl-insurance-detail-summary/extend-date'], '', 'btn btn-danger btn-block', '<i class="fa fa-calendar"></i>');
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

