<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
?>
<div class="panel panel-main view-subtitle">
    <?php
    $form = ActiveForm::begin(['options' => [
                    'field-class' => 'form-group col-sm-3'
                ], 'validateOnBlur' => FALSE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
                'fieldConfig' => [
    ]]);
    ?>
    <div class="panel-body">
        <div class="row">
            <?php $f_cnt = 0; ?>
            <?php
            foreach ($model as $models) {
                $key = $models->config_code;
                $fieldType = $models->getType($key);
                if (!empty($fieldType)) {
                    $labels = Yii::$app->general->getforeignkey($models->configCode, 'config_name');
                    ?>
                    <div class="" style="display:none">   
                        <?php echo $form->field($models, '[' . $key . ']config_txn_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']config_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']union_code')->hiddenInput()->label(false); ?>
                    </div>
                    <?php
                    if ($fieldType[0]->config_result_key == 'text') {
                        $f_cnt++
                        ?>
                        <div class="col-sm-2">
                            <?= $form->field($models, '[' . $key . ']config_result_key')->textInput()->label($labels); ?>
                        </div>
                        <?php
                    } else if ($fieldType[0]->config_result_key == 'numeric') {
                        $f_cnt++
                        ?>
                        <div class="col-sm-2">
                            <?= $form->field($models, '[' . $key . ']config_result_key')->textInput(['class' => 'form-control number-validate'])->label($labels); ?>
                        </div>
                        <?php
                    } else {
                        $dd_data = ArrayHelper::map($fieldType, 'config_result_key', 'config_result');
                        $f_cnt++;
                        ?>
                        <div class="col-sm-2">
                            <?php echo $form->field($models, '[' . $key . ']config_result_key', ['options' => ['class' => 'form-group padding-right-5']])->dropDownList($dd_data, ['prompt' => Yii::t('app', 'Select')])->label(Yii::t('app', $labels)); ?>
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
                        'url' => Url::to(['get-master-data']),
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
                    'options' => ['class' => 'btn-login btn btn-default btn-raised',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
                <?= Yii::$app->controls->reset(); ?>
                <?= Yii::$app->controls->cancel($models); ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
