<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\SystemConfiguration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-configuration-form">
    <h5 class="panel-subtitle"></h5>

    <?php $form = ActiveForm::begin(['validateOnBlur' => false,
                'validateOnEnter' => TRUE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,]); ?>
    
   
    <div class="table-responsive" style="width: 700px;margin: 0 auto;">
        
         <?php echo $form->errorSummary($model); ?>
        <table class="table table-bordered table-striped tbl-portal-conf">
            <thead>
            <th colspan="3">
            <h4 class="text-center">National Portal Config for geographical masters</h4>
            </th>
            </thead>
            <tr>
                <td></td>
                <td>From Value</td>
                <td>To Value</td>
            </tr>
           <?php
           switch ($flag) {
                    case 'state' :
                        $label = 'Custom Range for State Code';
                        break;
                    case 'district' :
                        $label = 'Custom Range for District Code';
                        break;
                    case 'sub-district' :
                        $label = 'Custom Range for Sub - District Code';
                        break;
                }
           ?>
                <tr>
                    <td>
                        <?= $label ?>
                    </td>
                    <td>
                        <?php echo Html::activeHiddenInput($model, 'id', ['value' => $model->id]); ?>
                        <?= $form->field($model, 'from_value', [ 'options' => ['class' => 'form-group col-sm-12',]])->textInput(['value' => $model->from_value])->label(false) ?></td>
                    <td>
                        <?= $form->field($model, 'to_value', [ 'options' => ['class' => 'form-group col-sm-12',]])->textInput(['value' => $model->to_value])->label(false) ?></td>
                </tr>

            <tr>
                <td colspan="3">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success apply-shortcut pull-right', 'shortcut_key' => 'ctrl+alt+s', 'button' => 'save']) ?>
                </td>
            </tr>
        </table>
    </div>
</div>


<?php ActiveForm::end(); ?>
