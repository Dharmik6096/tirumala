<?php
$title = 'Payment Transaction Reject '.ucfirst($type);
$this->title = Yii::t('app', Yii::$app->label->title('list', $title));
?>

<div class="panel panel-default panel-grid">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        <div class="clearfix"></div>
        <?=
        $this->render('_reject_reinitiate_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'type' => $type
        ])
        ?>
    </div>
</div>