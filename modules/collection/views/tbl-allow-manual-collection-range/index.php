<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Allow Manual Collection'));
$this->params['menu'][] = Yii::$app->controls->add('Allow Manual Collection Range');
$approval_config = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'workflow_for_manual_collection', 'PORTAL');
if (in_array($approval_config, [1, 2])) {
    $this->params['menu'][] = Yii::$app->controls->add('Allow Manual Collection Approval', 'manual-collection-approval');
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
