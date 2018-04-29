<?php
$this->title = Yii::$app->label->title('edit', 'Product Sale');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
       <?= $this->render('_amount_form', [
        'model' => $model,
        'type'=>'edit'
    ]) ?>

    </div>
</div>