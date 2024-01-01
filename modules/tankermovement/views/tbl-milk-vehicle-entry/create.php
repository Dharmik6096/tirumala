<?php
$this->title = Yii::$app->label->title('create', 'Milk Receipt');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'txn_model' => $txn_model,
            'type' => 'create',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'bmc_user' => $bmc_user,
        ])
        ?>
    </div>
</div>




