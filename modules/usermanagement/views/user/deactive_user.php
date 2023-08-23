<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Deactive User');
?>
<div class="modal modal-default fade" id="UserModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content panel">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Deactive User') ?></h4>
            </div>
            <div class='panel-body row pad-10'>
                <div class="col-md-12">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'recovery-form',
                                ],
                                'action' => Url::to(['/user-management/user/deactive-user', 'id' => $model->id])
                    ]);
                    echo $form->errorSummary($model);
                    ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d'), false); ?>
                        </div>
                        <div class="modal-footer mt10 col-sm-12">
                            <div class="col-md-12 top-bottom-15 padding-50">
                                <?php
                                AjaxSubmitButton::begin([
                                    'label' => Yii::t('app', 'Save'),
                                    'id' => 'recoveryBtn',
                                    'ajaxOptions' => [
                                        'type' => 'POST',
                                        'url' => Url::to(['/user-management/user/deactive-user', 'id' => $model->id]),
                                        'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                if (data.status == "success"){ 
                                                                    bootbox.alert("<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>"+data.msg+" </span></div></div>", function(){
                                                                          location.reload(); 
                                                                    });
                                                                 }else{
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
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
