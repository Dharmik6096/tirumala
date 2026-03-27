<?php
$this->title = Yii::$app->label->title('edit', 'Provisional Member');
$this->title .= ' > ' . $model->ex_member_code . ' > ' . $model->ref_code;
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
