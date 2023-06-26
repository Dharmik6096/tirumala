<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

$summary_model=$type=='create'?[$model,$contactDetails]:$model;
$readonly=$type=='create'?FALSE:TRUE;
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$disabled = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
<div class="col-md-12 padding_10_0 theme-box ">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
        <h4 class="theme-box-heading">Cluster Details</h4>
    </div>
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union',$readonly); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('plant', $model, $form, 'tblcluster-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Plant', '',$disabled); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    
    <div class="col-sm-2">
        <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'address')->textarea(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'local_address')->textarea(['maxlength' => true]) ?>
    </div>
</div>
    <div class="clearfix"></div>
    <?php if($type=='create') { ?>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Contact Details</h4>
        </div>
      <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>

    <?php } ?>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
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