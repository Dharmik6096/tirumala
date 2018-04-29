<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblFonts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-fonts-form">

    <?php    $form = ActiveForm::begin([
                           'id' => 'upload-form',
                           'options' => [ 'enctype' => 'multipart/form-data'],]);
                 ?>

    <?= Html::fileInput('upload_file') ?>

    <div class="form-group">
        <?= Html::submitButton('Import', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>