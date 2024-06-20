<?php
$this->title = Yii::$app->label->title('create', 'Import Provisional Member Bank Receipt');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_import_receipt_detail_from', [
            'model' => $model,
        ])
        ?>
    </div>
</div>