<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblRouteMapping */
/* @var $form yii\widgets\ActiveForm */
//$url= ($model->isNewRecord) ? '' : Url::to(['../../organisation/tbl-unions/view','id'=>$model->union_code]);
//$checkChild = ($model->getCheckDcsExist()) ? ' disabled' : '';
//$disable = ($model->isNewRecord) ? false : true;
$summary_model = $type == 'create' ? [$model, $contactDetails] : $model;

$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$readonly = $type == 'create' ? FALSE : TRUE;
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
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>

<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Route Mappings Details</h4>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <?php
        $keyPattern = Yii::$app->general->getKeyPattern('tbl_route_mapping');
        if (!empty($keyPattern)) {
            ?>
            <?php if (!$readonly && $keyPattern['ex_code_auto'] == 0) { ?>
                <div class="col-sm-2">  
                    <?= $form->field($model, 'route_code_ex')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
            <?php if (!$readonly && $keyPattern['ref_code_type'] == 2) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'ref_code')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="clearfix"></div>
        <div class="col-sm-2">
            <?= $form->field($model, 'route_name')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'local_name')->textInput() ?>
        </div>

        <div class="col-sm-2">
            <?= $form->field($model, 'route_type')->dropDownList(['Can' => 'Can', 'Tanker' => 'Tanker'], ['prompt' => 'Select Route Type']); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->routedestinationtype($model, $form, 'tblroutemapping-route_type,tblroutemapping-union_code', 'to_dest', 'To', FALSE, 'to'); ?>
            <?= $form->field($model, 'to_type')->hiddenInput()->label(false) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Vehicle Capacity(Ltr)', false, 'capacity'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('vehicle_type_code', $model, $form, 'form-group col-sm-3', 'Vehicle'); ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-2">
            <?= $form->field($model, 'route_length_kms')->textInput() ?>
        </div>


        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'valid_from'); ?>
        </div>
        <div class="col-sm-2">
            <?=
            $form->field($model, 'morning_start_time')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99:99',])->label('Morning Start Time (24 Hrs)');
            ?>
        </div>
        <div class="col-sm-2">
            <?=
            $form->field($model, 'morning_end_time')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99:99',])->label('Morning End Time (24 Hrs)');
            ?>
        </div>
        <div class="col-sm-2">
            <?=
            $form->field($model, 'evening_start_time')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99:99',])->label('Evening Start Time (24 Hrs)');
            ?>
        </div>
        <div class="col-sm-2">
            <?=
            $form->field($model, 'evening_end_time')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99:99',])->label('Evening End Time (24 Hrs)');
            ?>
        </div>
    </div>



    <div class="clearfix"></div>
    <?php if ($type == 'create') { ?>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Contact Details</h4>
        </div>
        <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>


        <!--    <div class="col-sm-12">
                <p class="form-subtitle">Bank   Details</p>
                <hr class="hr10">
            </div>-->
        <?php //Yii::$app->dropdown->dropdown('bank', $model, $form, 'form-group col-sm-2','Bank');    ?>
        <?php
        // =
//        $this->render('../../../details/views/tbl-bank-details/_form', [
//            'model' => $bankDetails,
//            'form' => $form,
//            'dist_field'=>'tblroutemapping-union_code'
//        ])
        ?>
    <?php } ?>

    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
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
<?php
$script = "
    $('#tblroutemapping-from_dest').on('change',function(){
            var str = $('#tblroutemapping-from_dest option:selected').text();
            str=str.split('-');
            $('#tblroutemapping-from_type').val(str[1].toLowerCase());
    });
    $('#tblroutemapping-to_dest').on('change',function(){
            var str = $('#tblroutemapping-to_dest option:selected').text();
            str=str.split('-');
            $('#tblroutemapping-to_type').val(str[1].toLowerCase());
    });
";
$this->registerJs($script, View::POS_END, 'union-select');

$script = "var delay=2000;";
$this->registerJs($script, View::POS_HEAD, 'time-loader');
