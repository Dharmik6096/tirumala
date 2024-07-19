<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Indent Request'));
$this->params['menu'][] = Yii::$app->controls->custombutton('Indent Request', 'create-other', '', 'btn btn-danger btn-block', '<i class="fa fa-plus"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Indent Approval', 'indent-approval-other', '', 'btn btn-danger btn-block', '<i class="fa fa-check"></i>');
$this->params['menu'][] = Yii::$app->controls->import('indent-master-other', $this);
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid_other', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>
