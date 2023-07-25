<?php
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Form Type',
]) . $model->form_type_code;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit',
        ])
        ?>
    </div>
</div>
