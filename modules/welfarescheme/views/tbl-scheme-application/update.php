<?php
$this->title = Yii::$app->label->title('edit', 'Scheme Application');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <ul class="progressbar">
            <li><?= $this->title ?></li>
            <li class="inactive"> > Upload Scheme Documents</li>
        </ul>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit',
        ])
        ?>
    </div>
</div>
