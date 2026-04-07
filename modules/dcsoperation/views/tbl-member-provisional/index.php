<?php

use yii\helpers\Url;

$memberCreationPendingForSapApproval = Yii::$app->general->getUnionConfigResult(Yii::$app->session->get('Unions'), 'member_creation_pending_for_sap_approval');
if (Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'workflow_require', 'PORTAL') == 1) {
    if ($pending_approval) {
        $this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Member Pending Approval'));
        if ($memberCreationPendingForSapApproval == 1) {
            $this->params['menu'][] = Yii::$app->controls->add('Bulk Provisional Member Pending Approval', 'bulk-pending-approval');
        }
    } else {
        $this->title = Yii::t('app', Yii::$app->label->title('list', 'provisional member'));
        $this->params['menu'][] = Yii::$app->controls->add('provisional member');
        $this->params['menu'][] = Yii::$app->controls->custombutton('provisional member bank receipt export', ['/misreports/reports/export-provisional-member-bank-receipt'], '', 'btn btn-danger btn-block', '<i class="fa fa-upload"></i>');
        // $this->params['menu'][] = Yii::$app->controls->custombutton('provisional member bank receipt export', 'export-provisional-member-bank-receipt', '', 'btn btn-danger btn-block', '<i class="fa fa-upload"></i>');
        $this->params['menu'][] = Yii::$app->controls->custombutton('provisional member bank receipt import', 'import-provisional-member-bank-receipt', '', 'btn btn-danger btn-block', '<i class="fa fa-download"></i>');
        $this->params['menu'][] = Yii::$app->controls->import('provisional-member-sap-import', $this, Yii::t('app', 'Provisional Member SAP Import'));
        $this->params['menu'][] = Yii::$app->controls->custombutton('FTP Upload', ['/dcsoperation/tbl-member-provisional/upload-member-data-to-sap-ftp'], '', 'btn btn-danger btn-block', '<i class="fa fa-upload"></i>');
    }
} else {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'provisional member'));
    $this->params['menu'][] = Yii::$app->controls->add('provisional member');
    $this->params['menu'][] = Yii::$app->controls->import('member-provisional', $this);
}
$this->params['menu'][] = Yii::$app->controls->custombutton('Update SAP Error Data', 'sap-error-data-list', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil"></i>');
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
            'pending_approval' => $pending_approval,
        ])
        ?>
    </div>
</div>