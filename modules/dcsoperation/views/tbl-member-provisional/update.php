<?php
$title = !empty($model->application_no) ? ' > ' . $model->application_no : '';
$this->title = Yii::$app->label->title('edit', 'Provisional Members') . $title;
$createSapErrorData = !empty($createSapErrorData) ? $createSapErrorData : FALSE;
if ($createSapErrorData) {
    $this->title = Yii::t('app', 'Update & Create') . ' ' . Yii::t('app', 'Provisional Members') . $title;
}
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        echo $this->render('_form', [
            'model' => $model,
            'type' => 'edit',
            'createSapErrorData' => $createSapErrorData
        ]);
        ?>
    </div>
</div>
