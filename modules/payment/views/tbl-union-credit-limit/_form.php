<?php

use yii\bootstrap5\ActiveForm;

use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Union Credit Limit');
$button = Yii::$app->label->button($type);
//$milkType = $model->getMilkTypes();
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?php echo Yii::t('app', $title); ?></h4>
        </div>
        <div class="col-sm-2">
            <?php if($type == 'edit'){ $disable = true; }else{ $disable = false; } ?>
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union',$disable); ?>
        </div>
        
        <div class="col-sm-2" style='display: none'>
            <?php $model->old_credit_type = $model->credit_type; ?>
            <?= $form->field($model, 'old_credit_type')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('credit_type_data', $model, $form, 'form-group', $model->getAttributeLabel('credit_type'), false, 'credit_type', false); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'credit_value')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?php echo Yii::$app->controls->date($model, $form, 'wef_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, date('Y-m-d'), $disable); ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>



<?php
$script = "
    $(document).ready(function() {
        var credit_type = $(this).val();
        if(credit_type == 0 && credit_type != ''){
            $('.field-tblunioncreditlimit-credit_value label').text('Credit Value (In Rupees)');
        }else if(credit_type == 1 && credit_type != ''){
            $('.field-tblunioncreditlimit-credit_value label').text('Credit Value (In Percentage)');
        }else{
            $('.field-tblunioncreditlimit-credit_value label').text('Credit Value');
        }
    });
    $('#tblunioncreditlimit-credit_type').change(function(){
        var credit_type = $(this).val();
        if(credit_type == 0 && credit_type != ''){
            $('.field-tblunioncreditlimit-credit_value label').text('Credit Value (In Rupees)');
        }else if(credit_type == 1 && credit_type != ''){
            $('.field-tblunioncreditlimit-credit_value label').text('Credit Value (In Percentage)');
        }else{
            $('.field-tblunioncreditlimit-credit_value label').text('Credit Value');
        }
        
        var old_credit_type = $('#tblunioncreditlimit-old_credit_type').val();
        if(old_credit_type != credit_type && old_credit_type != '' && credit_type != ''){
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-info\'></i></div><span>If you change Credit type value has been initiated</span></div></div>');
        }
    });
    
";
$this->registerJs($script, View::POS_END, 'shift');
?>

