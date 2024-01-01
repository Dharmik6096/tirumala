<?php
$this->title = Yii::$app->label->title('create', 'Payment Head Transaction');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
        ])
        ?>
    </div>
</div>
