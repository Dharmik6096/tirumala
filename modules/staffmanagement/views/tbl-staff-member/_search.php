<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffMemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-staff-member-search">

   <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5'
                ],
                'action' => ['index'],
                'method' => 'get',
    ]); ?>

   
    
    <?= Yii::$app->dropdown->depend_dropdown('sub-center', $model, $form, 'tblstaffmembersearch-dcs_code'); ?>
    
    <?= $form->field($model, 'staff_member_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->textInput(['placeholder'=>'Code'])->label(false) ?>

    <div class="form-group col-sm-3 padding-left-5">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
