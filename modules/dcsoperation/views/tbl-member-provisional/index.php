<?php

use yii\helpers\Url;

if (Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'workflow_require', 'PORTAL') == 1) {
    if ($pending_approval) {
        $this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Member Pending Approval'));
    } else {
        $this->title = Yii::t('app', Yii::$app->label->title('list', 'provisional member'));
        $this->params['menu'][] = Yii::$app->controls->add('provisional member');
    }
} else {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'provisional member'));
    $this->params['menu'][] = Yii::$app->controls->add('provisional member');
    $this->params['menu'][] = Yii::$app->controls->import('member-provisional', $this);
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