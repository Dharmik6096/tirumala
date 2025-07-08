<?php
$this->title = Yii::$app->label->title('create', 'BMC Milk Dispatch');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'txn_model' => $txn_model,
            'type' => $txnEdit ? 'edit' : 'create',
            'tripGenerateBtn' => $tripGenerateBtn,
            'txnEdit' => $txnEdit,
        ])
        ?>
    </div>
</div>




