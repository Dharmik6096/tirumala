<?php
$this->title = Yii::$app->label->title('create', 'Vehicle Cleaning Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'config_list' => $config_list,
            'config' => $config,
        ])
        ?>
    </div>
</div>
