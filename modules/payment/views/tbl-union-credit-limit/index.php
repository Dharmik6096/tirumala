<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Union Credit Limit'));
//if (Yii::$app->general->checkAccess('/dcsoperation//tbl-member/update')) {
    $this->params['menu'][] = Yii::$app->controls->add('Union Credit Limit');
//    $this->params['menu'][] = Yii::$app->controls->import('member', $this);
//    $this->params['menu'][] = Yii::$app->controls->import('member_limited', $this,'Import Limited Data');
//}
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
