<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'disabled';
?>

<?php
$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        <?php echo $form->field($model, 'store_location_type')->hiddenInput(['value' => '', 'id' => 'store_location_type'])->label(false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('store_location_type'), $readonly); ?>
    </div>    
    <div class="col-sm-2">
        <?= $form->field($model, 'store_location_name')->textInput() ?>   
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'reference_code')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'sloc_code')->textInput() ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$eiplCode = Yii::$app->session->get('eiplCode');
$script = "
    var eiplCode = '$eiplCode';
    $(document).ready(function(){
        if(eiplCode == 'GYAN') {
            $('#tblstorelocation-store_location_type').val('4').trigger('change');
            $('#tblstorelocation-store_location_type').attr('disabled', true);
            $('#store_location_type').val('4');
        }
    });
    
";
Yii::$app->view->registerJs($script, View::POS_END, 'asset_form');
?>

