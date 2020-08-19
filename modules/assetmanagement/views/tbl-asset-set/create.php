<?php
$this->title = Yii::$app->label->title('create', 'Asset SAP Code');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <?=
    $this->render('_form', [
        'model' => $model,
        'type' => 'create',
    ])
    ?>
</div>