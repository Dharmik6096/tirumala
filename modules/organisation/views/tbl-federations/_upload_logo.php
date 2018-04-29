<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblFederationsSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="tbl-federations-logo">
    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5 padding-left-0',
                    'enctype' => 'multipart/form-data',
                ],
                'action' => isset($actions) ? $actions : ['create'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2 padding-right-5">
        <?= $form->field($model, 'logo_path')->fileInput() ?>
    </div>
    <div class="col-sm-2 padding-left-0">
    <?= Html::submitButton('Upload', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>