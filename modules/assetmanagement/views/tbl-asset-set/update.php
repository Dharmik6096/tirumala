<?php
$this->title = Yii::$app->label->title('edit', 'Asset SAP Code');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <?=
    $this->render('_form', [
        'model' => $model,
        'type' => 'edit',
    ])
    ?>
</div>