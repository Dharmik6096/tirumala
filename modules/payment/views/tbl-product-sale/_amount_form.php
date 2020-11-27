<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblProductSale */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => true,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= $form->field($model, 'amount')->textInput(['disabled'=>true]) ?>
    </div>
    <?php $model->available_credit = isset($model->memberCredit) ? $model->memberCredit->balance : '0';?>
    <div style='display:none'>
    <?= $form->field($model, 'available_credit')->hiddenInput(['disabled'=>false]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'other_amount')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'discount')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'paid_amount')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'amount_due')->textInput(['readOnly'=>true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'payment_cycle_code')->dropDownList($paymentCycle, ['prompt' => 'Select Payment Cycle'])->label('Payment Cycle'); ?>
    </div>
    <!--<div class="col-sm-4">
    <?php /* AutoComplete::widget([
      'model' => $model,
      'attribute' => 'dcs_code',
      'clientOptions' => [
      'source' => ['USA', 'RUS'],
      ],
      ]); */ ?>
    </div>
    <div class="col-sm-4">
    <?php /* AutoComplete::widget([
      'model' => $model,
      'attribute' => 'member_code',
      'clientOptions' => [
      'source' => ['01'=>'USA', '02'=>'RUS'],
      ],
      ]); */ ?>
    </div>-->
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('Save Payment', $model); ?>
            <?= Yii::$app->controls->reset(); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $(document).ready(function() {
        localStorage.removeItem('sale_data');
        localStorage.removeItem('sale_item');
        $('#tblproductsale-other_amount, #tblproductsale-paid_amount, #tblproductsale-discount').on('change',function(){
            calcDue();
        });
   });
   
    function calcDue()
    {
        var total=$('#tblproductsale-amount').val();
        var other=$('#tblproductsale-other_amount').val();
        var disc=$('#tblproductsale-discount').val();
        var paid=$('#tblproductsale-paid_amount').val();
        var due=( parseFloat(total)+ parseFloat(other))-( parseFloat(disc)+ parseFloat(paid));
        $('#tblproductsale-amount_due').val(due.toFixed(2));
    }
";
$this->registerJs($script, View::POS_END, 'union');
