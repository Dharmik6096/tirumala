<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
$title = Yii::$app->label->title($type, 'installation identity');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);
$ary = explode('-', $model->identity);
$style = (isset($ary[2]) && $ary[2]=='4')?'block':'none';
?>


                    <?php
$form = ActiveForm::begin(['options' => [
                'class' => 'save-form',
                'field-class' => 'col-sm-12'
            ],
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
            //'labelOptions' => [ 'class' => false],
        ]]);
?>
<div class="panel-body">
    <div class="panel-subheading">
        <h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
                    <?= $form->field($model, 'identity', [ 'options' => ['class' => 'form-group col-sm-3']])->dropDownList($model->getIdentityOrg(), ['prompt' => 'Select Identity']); ?>
                    
                    
                      <?= $form->field($model, 'parent_type', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['placeholder' => 'Parent Type', 'autocomplete' => 'off', 'readonly' => true]) ?>

                     <?php
                        echo $form->field($model, 'parent_code', [ 'options' => ['class' => 'form-group col-sm-3']])->widget(DepDrop::classname(), [
                            'data' => [$model->parent_code => $model->parent_code],
                            'name' => 'fonts',
                            'pluginOptions' => [
                                'depends' => ['installationidentity-identity'],
                                'placeholder' => 'Select Parent Code',
                                'url' => Url::to(['/installation/default/parent-list']),
                                'initialize' => true
                            ]
                        ]);
                        ?>
            
                    <?php
                        echo $form->field($model, 'organization_code', [ 'options' => ['class' => 'form-group col-sm-3']])->widget(DepDrop::classname(), [
                            'data' => [$model->organization_code => $model->organization_code],
                            'name' => 'fonts',
                            'pluginOptions' => [
                                'depends' => ['installationidentity-parent_code'],
                                'placeholder' => 'Select Organization Code',
                                'url' => Url::to(['/installation/default/parent-list']),
                                'initialize' => true,
                            ]
                        ]);
                        ?>
            
            <div id="dcs-dd" style="display: <?= $style ?>">
                    <?php
                        echo $form->field($model, 'dcs_code', [ 'options' => ['class' => 'form-group col-sm-3']])->widget(DepDrop::classname(), [
                            'data' => [$model->dcs_code => $model->dcs_code],
                            'name' => 'fonts',
                            'pluginOptions' => [
                                'depends' => ['installationidentity-organization_code'],
                                'placeholder' => 'Select DCS Code',
                                'url' => Url::to(['/installation/default/dcs-list']),
                                'initialize' => true,
                            ]
                        ]);
                    ?>
            </div>        
<!--                            <div class="clearfix"></div>-->
                    <?= $form->field($model, 'organization_type', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['placeholder' => 'Organization Type', 'autocomplete' => 'off', 'readonly' => true]) ?>
                   
                    
                    
</div>
    </div>
</div>
<div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?= Yii::$app->controls->save($button, $model); ?>
    <?= Yii::$app->controls->reset(); ?>
    <?= Yii::$app->controls->cancel($model); ?>
</div>
                    <?php ActiveForm::end(); ?>
                
<?php
$script = "
     $('#installationidentity-identity').on('change',function(){
        var org = this.value;
        var ary = org.split('-');
        $('#installationidentity-parent_type').val(ary[0]);
        $('#installationidentity-organization_type').val(ary[1]);
        if(ary[2]=='4'){
            $('#dcs-dd').css('display','block');
        }else{
            $('#dcs-dd').css('display','none');
        }
    });
";
$this->registerJs($script, View::POS_END, 'delete-manager');
?>