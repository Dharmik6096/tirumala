<?php
$this->title = Yii::$app->label->title('create', 'Alert Rule Mapping');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'department' => $department,
            'type' => 'create'
        ])
        ?>
    </div>
</div>
