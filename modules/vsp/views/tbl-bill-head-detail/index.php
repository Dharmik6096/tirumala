<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Bill Head Detail'));
$this->params['menu'][] = Yii::$app->controls->add('Vendor Bill Head Transection');
$this->params['menu'][] = Yii::$app->controls->add('Member Bill Head Transection', 'create-member-bill-detail');
//$this->params['menu'][] = Yii::$app->controls->custombutton('Head Wise Transaction', '/vsp/tbl-bill-head-detail/create', true);
//$this->params['menu'][]=Yii::$app->controls->custombutton('Society Wise Transaction','/vsp/tbl-bill-head-detail/society-wise-transaction',true);
//$this->params['menu'][]=Yii::$app->controls->custombutton('Head Wise Bulk Entry','/vsp/tbl-bill-head-detail/society-bulk-insert',true);
$this->params['menu'][] = Yii::$app->controls->import('bill-head-detail', $this, Yii::t('app', 'Vendor Bill Head Import'));
$this->params['menu'][] = Yii::$app->controls->import('member-bill-head-detail', $this, Yii::t('app', 'Member Bill Head Import'), [], 'member_bill');
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