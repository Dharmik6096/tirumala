<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Monthly Credit Limit'));
$this->params['menu'][] = Yii::$app->controls->add('Monthly Credit Limit');
$this->params['menu'][] = Yii::$app->controls->add('Monthly Credit Limit (Member)', ['create-member']);
$this->params['menu'][] = Yii::$app->controls->import('monthly-credit-limit', $this, Yii::t('app', 'Monthly Credit Limit Import'));
$this->params['menu'][] = Yii::$app->controls->import('monthly-credit-limit-member', $this, Yii::t('app', 'Monthly Credit Limit (Member) Import'), [], 'monthlycreditlimit_member');

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
