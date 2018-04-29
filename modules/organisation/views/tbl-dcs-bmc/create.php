<?php
$this->title = Yii::$app->label->title('create', 'BMC');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'contactDetails'=>$contactDetails
        ])
        ?>
    </div>
</div>