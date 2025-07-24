<?php
$this->title = Yii::$app->label->title('create', 'PLANT Milk Dispatch');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form_plant', [
            'model' => $model,
            'txn_model' => $txn_model,
            'type' => $txnEdit ? 'edit' : 'create',
            'tripGenerateBtn' => $tripGenerateBtn,
            'txnEdit' => $txnEdit,
        ])
        ?>
    </div>
</div>