<?php
$title = !empty($model->customer_code_ex) ? ' > ' . $model->customer_code_ex : '';
$title .= !empty($model->ref_code) ? ' > ' . $model->ref_code : '';
$this->title = Yii::$app->label->title('edit', 'Provisional Customer') . $title;
$createSapErrorData = !empty($createSapErrorData) ? $createSapErrorData : FALSE;
if ($createSapErrorData) {
    $this->title = Yii::t('app', 'Update & Create') . ' ' . Yii::t('app', 'Provisional Customer') . $title;
}
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit',
            'createSapErrorData' => $createSapErrorData
        ])
        ?>
    </div>
</div>