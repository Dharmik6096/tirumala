<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Payment Transaction Pending Approval'));
?>

<div class="panel panel-default panel-grid">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_pending_approval_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>