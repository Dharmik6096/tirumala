<?php
$isVcgMrgMeetingApprovalRequired = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'is_vcg_mrg_meeting_approval_required', 'PORTAL') == 1 ? TRUE : FALSE;
$this->title = Yii::t('app', Yii::$app->label->title('list', 'VCG MRG Members'));
if(!$isVcgMrgMeetingApprovalRequired){
    $this->params['menu'][] = Yii::$app->controls->custombutton('VCG/MRG Member Approval', 'approval', '', 'btn btn-danger btn-block', '<i class="fa fa-plus"></i>');
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

