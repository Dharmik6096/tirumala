<?php
$this->title = Yii::$app->label->title('create', 'Society Wise Insurance Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_dcs_wise_import', [
            'model' => $model,
            'type' => 'create',
        ])
        ?>
    </div>
</div>
