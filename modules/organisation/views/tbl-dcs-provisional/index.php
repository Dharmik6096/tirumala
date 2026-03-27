<?php

use webvimark\modules\UserManagement\components\GhostHtml;

if ($pending_approval) {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Society Pending Approval'));
} else {
    if (Yii::$app->general->checkAccess('/organisation/tbl-dcs/update')) {
        $this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Society'));
        $this->params['menu'][] = Yii::$app->controls->add('Provisional Society');
    }
    $this->params['menu'][] = Yii::$app->controls->custombutton('Update SAP Error Data', 'sap-error-data-list', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil"></i>');
}
?>
<div class="tbl-dcs-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body hide-grid-export">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'pending_approval' => $pending_approval
            ])
            ?>
        </div>
    </div>
</div>
