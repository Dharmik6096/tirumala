<?php

use yii\web\View;

$title = !empty($model->dcs_code_ex) ? ' > ' . $model->dcs_code_ex : '';
$title .= !empty($model->ref_code) ? ' > ' . $model->ref_code : '';
$this->title = Yii::$app->label->title('edit', 'Provisional Society') . $title;
$createSapErrorData = !empty($createSapErrorData) ? $createSapErrorData : FALSE;
if ($createSapErrorData) {
    $this->title = Yii::t('app', 'Update & Create') . ' ' . Yii::t('app', 'Provisional Society') . $title;
}
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model, 'type' => 'edit', 'showIsBMC' => $showIsBMC, 'createSapErrorData' => $createSapErrorData
        ])
        ?>
    </div>
</div>
<?php
$script = "
        $(document).ajaxStop(function() {
            $('.depend-control').each(function(){
                $(this).attr('disabled', 'disabled');
            })
        });";
Yii::$app->view->registerJs($script, View::POS_READY, 'disable-dep');
?>