<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$disable = (!$model->isNewRecord) ? ' disabled' : '';
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?php echo $form->errorSummary($model); ?>

<?php
echo Html::hiddenInput('warning', 0, ['id' => 'warning']);
?>
<div class="row">
    <div class="col-sm-3 <?= $disable ?>">
        <?php Yii::$app->dropdown->state($model, $form, 'state', 'State'); ?>
    </div>
    <div class="col-sm-3 <?= $disable ?>">
        <?= Yii::$app->dropdown->district($model, $form, 'tblhamlets-state', 'district', 'District'); ?>
    </div>
    <div class="col-sm-3 <?= $disable ?>">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblhamlets-district', '', 'Sub District', 'sub_district'); ?>
    </div>
    <div class="col-sm-3 <?= $disable ?>">
        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblhamlets-sub_district', '', 'Village'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'hamlet_code')->textInput(['maxlength' => true, 'readOnly' => true, 'class' => 'form-control ']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'hamlet_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
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
    $('#tblhamlets-village_code').on('blur',function(){
            var id = $('#tblhamlets-village_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/geo/tbl-hamlets/get-max-hamlet-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $('#tblhamlets-hamlet_code').val(obj1.code);
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>