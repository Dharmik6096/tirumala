<?php

use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'member'));
if (Yii::$app->general->checkAccess('/dcsoperation//tbl-member/update')) {
    $this->params['menu'][] = Yii::$app->controls->add('member');
    $config = (count(explode(',', Yii::$app->session->get('Unions'))) == 1 && !empty(Yii::$app->session->get('unionConfig')[Yii::$app->session->get('Unions')]['member_with_class'])) ? Yii::$app->session->get('unionConfig')[Yii::$app->session->get('Unions')]['member_with_class'] : 0;
    if ($config == 1) {
        $this->params['menu'][] = Yii::$app->controls->import('member-bulk-rateclass', $this);
    } else {
        $this->params['menu'][] = Yii::$app->controls->import('member-bulk', $this);
    }
//    $this->params['menu'][] = Yii::$app->controls->import('member_limited', $this,'Import Limited Data');
    $this->params['menu'][] = GhostHtml::a('<i class="fa fa fa-times"></i>' . Yii::t('app', 'Member Deactivation'), ['/dcsoperation/tbl-member-deactive/index'], ['class' => 'btn btn-danger btn-block']);
}
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body hide-grid-export">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>