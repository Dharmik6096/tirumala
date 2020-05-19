<?php
$this->title = Yii::$app->label->title('create', 'Product Dispatch with Requisition');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modeltransaction' => $modeltransaction
        ])
        ?>
    </div>
</div>
