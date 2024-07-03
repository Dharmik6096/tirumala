<?php
$title = 'Payment Transaction Pending '.ucfirst($type);
$this->title = Yii::t('app', Yii::$app->label->title('list', $title));
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
            'type' => $type
        ])
        ?>
    </div>
</div>