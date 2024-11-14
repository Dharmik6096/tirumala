<?php
$title = 'Payment Transaction Pending '.ucfirst($type);
$this->title = Yii::t('app', Yii::$app->label->title('list', $title));
?>

<div class="panel panel-default panel-grid">
    <div class="panel-heading">
        <?= $this->title; ?>
        <div class="right_align_date">Total Payable Amount: <span class="total_payable_amount">0.00</span></div>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_pending_approval_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'type' => $type
        ])
        ?>
    </div>
</div>