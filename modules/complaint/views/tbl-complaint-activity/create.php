<?php
$this->title = Yii::$app->label->title('edit', 'Complaint Activity');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model, 
            'type' => 'create',
            'complaint_model' => $complaint_model,
        ])
        ?>
    </div>
</div>