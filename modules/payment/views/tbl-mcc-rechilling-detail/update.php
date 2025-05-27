<?php
$this->title = Yii::$app->label->title('edit', 'MCC Rechilling Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit'
        ])
        ?>
    </div>
</div>