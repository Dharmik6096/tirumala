<?php
$this->title = Yii::$app->label->title('create', 'Complain');
$type = !empty($type) ? $type : 'create'; //update_complaint
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => $type,
            'complainAttachment' => $complainAttachment,
        ])
        ?>
    </div>
</div>
