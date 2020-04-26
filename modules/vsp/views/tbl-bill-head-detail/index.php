<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Bill Head Detail'));
$this->params['menu'][] = Yii::$app->controls->add('Head Wise Transaction');
$this->params['menu'][] = Yii::$app->controls->add('Member Head Wise Transaction', 'create-member-bill-detail');
//$this->params['menu'][] = Yii::$app->controls->custombutton('Head Wise Transaction', '/vsp/tbl-bill-head-detail/create', true);
//$this->params['menu'][]=Yii::$app->controls->custombutton('Society Wise Transaction','/vsp/tbl-bill-head-detail/society-wise-transaction',true);
//$this->params['menu'][]=Yii::$app->controls->custombutton('Head Wise Bulk Entry','/vsp/tbl-bill-head-detail/society-bulk-insert',true);
$this->params['menu'][] = Yii::$app->controls->import('bill-head-detail', $this);
//$this->params['menu'][] = Yii::$app->controls->import('member-bill-head-detail', $this);
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