<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Manual Collection Request'));
$this->params['menu'][] = Yii::$app->controls->add('Manual Collection Request');
$approval_config_real = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'manual_collection_request_approval_realtime', 'PORTAL');
$approval_config_back = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'manual_collection_request_approval_backdate', 'PORTAL');
if ($approval_config_real != 0 || $approval_config_back != 0) {
    $this->params['menu'][] = Yii::$app->controls->custombutton('My Pending Approval', 'manual-collection-approval', '', 'btn btn-danger btn-block', '<i class="fa fa-check"></i>');
}
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
