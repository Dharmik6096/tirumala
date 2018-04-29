<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffSalarySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-staff-salary-search">

   <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5'
                ],
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

 

    <?= $form->field($model, 'staff_member_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->textInput(['placeholder'=>'Name'])->label(false) ?>
    
    <div class="form-group col-sm-3 padding-left-5">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
