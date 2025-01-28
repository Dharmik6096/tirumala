<?php
$this->title = Yii::$app->label->title('create', 'Insurance Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_create_insurance_detail', [
            'model' => $model,
            'type' => 'create',
        ])
        ?>
    </div>
</div>
