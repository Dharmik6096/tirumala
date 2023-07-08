<?php
$this->title = Yii::$app->label->title('edit', 'Complain');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body hide-grid-settings">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit',
            'complainAttachment' => $complainAttachment
        ])
        ?>

        <?=
        $this->render('_attachment_grid', [
            'dataProvider' => $dataProvider,
            'complainAttachment' => $complainAttachment
        ])
        ?>
    </div>
</div>

