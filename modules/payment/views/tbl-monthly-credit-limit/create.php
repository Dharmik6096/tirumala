<?php
$this->title = Yii::$app->label->title('create', 'Monthly Credit Limit');
$type = !empty($type) ? $type : 'create';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
       <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>
    </div>
</div>
