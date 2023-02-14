<?php
$this->title = Yii::$app->label->title('create', 'Inventory Transfer');
$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model, 'type' => 'create',
            'txModel' => $txModel,
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            'batchNoWiseInventory' => $batchNoWiseInventory,
        ])
        ?>
    </div>
</div>
