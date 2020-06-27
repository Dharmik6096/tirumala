<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;

$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
$readonly = $type == 'create' ? false : true;
?>

<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Milk Reject Details</h4>
        </div>
    <div class="col-sm-2" id="union">
        <?php
        echo Yii::$app->dropdown->dropdownStatic('source_org_type', $model, $form, 'form-group', $model->getAttributeLabel('source_org_type'), false);
        ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkreject-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkreject-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkreject-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>

    <div class="" id="dcshide">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->customer_type($model, $form, 'tblmilkreject-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->customer_code($model, $form, 'tblmilkreject-bmc_code,tblmilkreject-customer_type', 'customer_code', $model->getAttributeLabel('customer_code'), FALSE); ?>
        </div>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection'); ?>
    </div>
    <div class="col-sm-2 shift rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('shift', $model, $form, 'shift_code', true, false, 'shift_code'); ?>
    </div>

    <div class="col-sm-2 rtpl_validate reset_field">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', $model->getAttributeLabel('milk_type_code')); ?>
    </div>
    <div class="col-sm-2 rtpl_validate reset_field">
        <?php
        echo Yii::$app->dropdown->dropdown('reject_reason', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('rejection_reason_code'), false, 'rejection_reason_code');
        ?>
    </div>
    <div class="col-sm-2" id="union">
        <?php
        echo Yii::$app->dropdown->dropdownStatic('return_type', $model, $form, 'form-group', $model->getAttributeLabel('return_type'), false, 'return_type', false);
        ?>
    </div>
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Milk Details</h4>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'action_taken')->textarea() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'remarks')->textarea() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'fat')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'snf')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'qty')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'no_of_can')->textInput() ?>
        </div>
    </div>

</div>   
<div class="clearfix"></div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<!--form ends-->


<?php
$script = "
    $(document).ready(function(){
    dispDcs();
    });
    $('#tblmilkreject-source_org_type').on('change',function(){
        dispDcs();
    });
    
    function dispDcs(){
        $('#dcshide').hide();
        var type = $('#tblmilkreject-source_org_type').val();
        if(type == 'bmc-society' || type == 'bmc'){
        $('#dcshide').show();
        }
    }
";
$this->registerJs($script, View::POS_END, 'milk-reject-form');
?>