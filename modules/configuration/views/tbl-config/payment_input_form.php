<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\jui\DatePicker;
use yii\helpers\Url;
use app\modules\configuration\models\TblConfigResult;
use kartik\depdrop\DepDrop;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
?>
<div class="panel panel-main">
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
    <?php
    if (!empty($model[0])) {
        if ($model[0]->org_type == 'PLANT') {
            $code = $model[0]->plant_code;
            $name = Yii::$app->general->getforeignkey($model[0]->plantCode, 'name');
            $refCode = Yii::$app->general->getforeignkey($model[0]->plantCode, 'ref_code');
        } else {
            $code = $model[0]->bmc_code;
            $name = Yii::$app->general->getforeignkey($model[0]->mainBmcCode, 'bmc_name');
            $refCode = Yii::$app->general->getforeignkey($model[0]->mainBmcCode, 'ref_code');
        }
    }
    $pro_name = (isset($masterData[0]->process_name)) ? $masterData[0]->process_name : '';
    ?>
    <div class="panel-heading"><?= $pro_name ?> > <?= $model[0]->org_type ?> : <?= $name . '(' . $code . ')' ?>, Ref. Code: <?= $refCode ?></div>

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
                        <?php echo $form->field($models, '[' . $key . ']config_mapping_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']config_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']union_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']plant_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']mcc_plant_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']bmc_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']org_code')->hiddenInput()->label(false); ?>
                        <?php echo $form->field($models, '[' . $key . ']org_type')->hiddenInput()->label(false); ?>
                    </div>
                    <?php
                    if ($fieldType[0]->config_result_key == 'text') {
                        $f_cnt++
                        ?>
                        <div class="col-sm-4">
                            <?= $form->field($models, '[' . $key . ']config_result')->textInput()->label($labels); ?>
                        </div>
                        <?php
                    } else {
                        $dd_data = ArrayHelper::map($fieldType, 'config_result_key', 'config_result');
                        $f_cnt++;
                        ?>
                        <div class="col-sm-4">
                            <?php echo $form->field($models, '[' . $key . ']config_result', ['options' => ['class' => 'form-group padding-right-5']])->dropDownList($dd_data, ['prompt' => Yii::t('app', 'Select')])->label(Yii::t('app', $labels)); ?>
                            <?php //Yii::$app->dropdown->configDrop($models, $form, 'tblunionconfigresult-' . $key . '-config_code', '[' . $key . ']config_result_key', $label, FALSE, FALSE, $models->config_result_key);  ?>
                        </div>
                        <?php
                    }
                    if ($f_cnt == 3) {
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
                        'url' => Url::to(['payment-config-data']),
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
    </div>
</div>
