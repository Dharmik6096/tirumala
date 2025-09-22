<?php
$this->title = Yii::$app->label->title('create', 'Member / Rate Re-Push Logs');
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
            <div class="clearfix"></div>
            <?php
            echo $this->render('_form', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
            ?>
        </div>
    </div>
</div>