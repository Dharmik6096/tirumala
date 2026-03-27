<?php
$this->title = Yii::$app->label->title('edit', 'Provisional DCS');
$this->title .= ' > ' . $model->dcs_code_ex . ' > ' . $model->ref_code;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form_sap_error_data', [
            'model' => $model,
            'type' => 'edit',
        ])
        ?>
    </div>
</div>
