<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblProject */
/* @var $form yii\widgets\ActiveForm */
$fDate = !empty($ActiveModel->from_date) ? $ActiveModel->from_date : date('d-m-Y', strtotime('-6 day', strtotime(date('Y-m-d'))));
$fDate = date('Y-m-d', strtotime('+1 day', strtotime($ActiveModel->from_date)));
$curDate = date('Y-m-d');
$curDate = date('Y-m-d', strtotime('-5 day', strtotime($curDate)));
if (strtotime($fDate) > strtotime($curDate)) {
    $minDate = date('d-m-Y', strtotime($fDate));
} else {
    $minDate = $curDate;
}
?>
<div class="modal modal-default fade" id="DCSActiveModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Activate DCS') ?></h4>
            </div>

            <div class='row pad-10'>
                <div class="col-sm-12">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'activate-dcs-form',
                                ],
                                'action' => Url::to(['/organisation/tbl-dcs-deactive/activate-dcs'])
                    ]);
                    ?>

                    <div class="col-sm-6">
                        <?= Yii::$app->controls->date($model, $form, 'to_date', '', FALSE, $minDate); ?> 
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
                    </div>
                    <?= Html::activeHiddenInput($model, 'dcs_deactive_code'); ?>

                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12 top-bottom-15 padding-50">
                            <?php
                            AjaxSubmitButton::begin([
                                'label' => Yii::t('app', 'Save'),
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'url' => Url::to(['activate-dcs']),
                                    'beforeSend' => new JsExpression("function(data){
//                                                $('#loadercontent').show();
//                                                $('#pageloader').show();
                                                }"),
                                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
//                                                                $("#loadercontent").hide();
//                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                }else{
//                                                                    $("#pageloader").hide();
//                                                                    $(".help-block").text("");
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

                            <?= Html::resetButton('Reset', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
</div>

