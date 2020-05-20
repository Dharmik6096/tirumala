<?php
$this->title = Yii::$app->label->title('create', 'BMC Milk Dispatch');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'transaction'=>$transaction,
            'type' => 'create',
        ])
        ?>
    </div>
</div>




