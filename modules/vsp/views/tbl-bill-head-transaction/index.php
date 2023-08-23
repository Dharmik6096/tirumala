<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Bill Head Transaction'));
$this->params['menu'][] = Yii::$app->controls->add('Vendor Bill Head Transaction');
$this->params['menu'][] = Yii::$app->controls->add('Member Bill Head Transaction', 'create-member-bill-detail');
$this->params['menu'][] = Yii::$app->controls->import('bill-head-transaction', $this, Yii::t('app', 'Vendor Bill Head Import'));
$this->params['menu'][] = Yii::$app->controls->import('member-bill-head-transaction', $this, Yii::t('app', 'Member Bill Head Import'), [], 'member_bill');
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