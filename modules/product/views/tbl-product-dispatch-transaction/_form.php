<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>

    <div class="col-sm-3 reset_field">
        <?php Yii::$app->dropdown->depend_dropdown('product', $model, $form, 'tblproductdispatchtransaction-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('product_code'), '', true); ?>
    </div>
    <?= $form->field($model, 'dispatch_qty', ['options' => ['class' => 'form-group col-sm-3 number-validate']])->textInput() ?>
    <?= $form->field($model, 'rate', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'amount', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'discount_amount', ['options' => ['class' => 'form-group col-sm-3 number-validate']])->textInput([]) ?>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = "
    $('#tblproductdispatchtransaction-dispatch_qty').on('blur',function(){   
        calc();
    });
       
    function calc(){
    
        var qty = $('#tblproductdispatchtransaction-dispatch_qty').val();
        var rate = $('#tblproductdispatchtransaction-rate').val();        
        var amt = parseFloat(qty)*parseFloat(rate);
        
        if(!isNaN(amt)){
           amt=amt.toFixed(2);
            $('#tblproductdispatchtransaction-amount').val(amt);
        }
    }
";
$this->registerJs($script, View::POS_END, 'product-disp=txn-update-form');
?>