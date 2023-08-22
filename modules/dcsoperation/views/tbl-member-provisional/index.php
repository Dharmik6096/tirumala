<?php

use yii\helpers\Url;

if ($pending_approval) {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Member Pending Approval'));
} else {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'provisional member'));
    $this->params['menu'][] = Yii::$app->controls->add('provisional member');
    $this->params['menu'][] = Yii::$app->controls->import('member-provisional', $this);
    $this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-check"></i> ' . Yii::t('app', 'Provisional Member Approval'), ['/dcsoperation/tbl-member-provisional/provisional-members-approval'], true);
    $this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-check"></i> ' . Yii::t('app', 'Provisional Member Approvals'), ['/dcsoperation/tbl-member-provisional/pending-approval'], true);
}
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