<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'no_pointer';
$url = $type == ['create', 'id' => $saveModel->device_id];
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
   
        <?php $f_cnt = 0; ?>
        <?php
       
        foreach ($model as $models) {
            $key = $models->config_code;
            $fieldType = $models->getType($key);
            if (!empty($fieldType)) {
                $labels = Yii::$app->general->getforeignkey($models->configCode, 'config_name');
                ?>
                <div class="" style="display: none">   
                    <?php echo $form->field($models, '[' . $key . ']config_result_code')->textInput()->label(false); ?>
                    <?php echo $form->field($models, '[' . $key . ']config_code')->textInput()->label(false); ?>
                    <?php echo $form->field($models, '[' . $key . ']union_code')->textInput()->label(false); ?>
                </div>
                <?php
                if ($fieldType[0]->config_detail_key == 'text') {
                    $f_cnt++
                    ?>
                    <div class="col-sm-2">
                        <?= $form->field($models, '[' . $key . ']config_detail_key')->textInput()->label($labels); ?>
                    </div>
                <?php } elseif ($fieldType[0]->config_detail_key == 'time') {
                    ?>
                    <div class="col-sm-2">
                        <?=
                        $form->field($models, '[' . $key . ']config_detail_key')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                            'mask' => '99:99',])->label($labels);
                        ?> 
                    </div>

                <?php } elseif ($fieldType[0]->config_detail_key == 'date') { ?>
                    <div class="col-sm-2">
                        <?= Yii::$app->controls->date($models, $form, '[' . $key . ']config_detail_key', '', '', false, false, true, FALSE, '', $labels); ?>
                    </div>
                    <?php
                } else {
                    $dd_data = ArrayHelper::map($fieldType, 'config_detail_key', 'config_detail');
                    $f_cnt++;
                    ?>
                    <div class="col-sm-2">
                        <?php echo $form->field($models, '[' . $key . ']config_detail_key', ['options' => ['class' => 'form-group padding-right-5']])->dropDownList($dd_data, ['prompt' => Yii::t('app', 'Select')])->label(Yii::t('app', $labels)); ?>
                        <?php //Yii::$app->dropdown->configDrop($models, $form, 'tblunionconfigresult-' . $key . '-config_code', '[' . $key . ']config_result_key', $label, FALSE, FALSE, $models->config_result_key);    ?>
                    </div>
                    <?php
                }
                if ($f_cnt == 6) {
                    echo '<div class="clearfix"></div>';
                    $f_cnt = 0;
                }
            }
        }
        ?>
</div>
<div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Save'),
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to($url),
                'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $(\'#loadercontent\').hide();
                                                                $(\'#pageloader\').hide();
                                                                if (data.status == "success"){ 
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                }else{
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
            ],
            'options' => ['class' => 'btn btn-default btn-raised',
                'type' => 'submit'],
        ]);
        AjaxSubmitButton::end();
        ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($models); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

