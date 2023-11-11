<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\web\View;

$this->title = Yii::t('app', 'Create Trip');

?>
<div class="modal modal-default fade create-trip-modal" id="createTripModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content panel">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalHeader">Generate Trip For Vehicle: <span id="vehicleName"><?= $data['vehicleName'] ?></span>, Date: <span id="dispatchDate"><?= $data['dispatchDate'] ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class='panel-body row pad-10'>
                <div class="col-md-12">
                    <?php
                    $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form-group popup-form',
                            'id' => 'vehicle-trip-form',
                        ],
                        'action' => Url::to(['/tankermovement/tbl-bmc-milk-dispatch/generate-auto-trip'])
                    ]);
                    echo $form->errorSummary($bmcDispatchInspectionModel);
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-2 hide_help_block">
                                <?= $form->field($bmcDispatchInspectionModel, 'bmc_code')->hiddenInput(['value' => $data['bmcValue']]); ?>
                                <input type="text" class="form-control" id="bmc_code" value="<?= $data['bmcName'] ?>" readonly>
                            </div>
                            <div class="col-sm-2 hide_help_block">
                                <?= $form->field($bmcDispatchInspectionModel, 'vehicle_code')->hiddenInput(['value' => $data['vehicleValue']]); ?>
                                <input type="text" class="form-control" id="vehicle_code" value="<?= $data['vehicleName'] ?>" readonly>
                            </div>
                            <div class="col-sm-2 hide_help_block">
                                <?= $form->field($bmcDispatchInspectionModel, 'inspection_date')->hiddenInput(['value' => $data['dispatchDate']]); ?>
                                <input type="text" class="form-control" id="inspection_date" value="<?= $data['dispatchDate'] ?>" readonly>
                            </div>
                            <div class="col-sm-2 shift">
                                <?= Yii::$app->dropdown->dropdown('shift_applicability', $bmcDispatchInspectionModel, $form, '', true, false, 'shift_code'); ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($bmcDispatchInspectionModel, 'remarks')->textInput() ?>
                            </div>
                            <div class="clearfix"></div>
                            <?php
                            $index = 1;
                            $cnt = 1;
                            foreach ($config_list as $c) {
                            ?>
                                <?= Html::activeHiddenInput($config, '[' . $index . ']config_code', ['value' => $c->config_code]); ?>
                                <div class="col-sm-2">
                                    <?= $c->prepareControl($form, $config, $index); ?>
                                </div>
                                <?php if ($cnt == 6) { ?>
                                    <div class="clearfix"></div>
                                <?php
                                    $cnt = 0;
                                }
                                ?>
                            <?php
                                $cnt++;
                                $index++;
                            }
                            ?>
                            <div class="hide_help_block">
                                <?= $form->field($bmcDispatchInspectionModel, 'union_code')->hiddenInput(['value' => $data['unionValue']])->label(false); ?>
                                <?= $form->field($bmcDispatchInspectionModel, 'plant_code')->hiddenInput(['value' => $data['plantValue']])->label(false); ?>
                                <?= $form->field($bmcDispatchInspectionModel, 'mcc_plant_code')->hiddenInput(['value' => $data['mccValue']])->label(false); ?>
                            </div>
                        </div>
                        <div class="modal-footer mt10 col-sm-12">
                            <div class="col-md-12 top-bottom-15 padding-50">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                <?php
                                AjaxSubmitButton::begin([
                                    'label' => Yii::t('app', 'Generate Trip'),
                                    'id' => 'recoveryBtn',
                                    'ajaxOptions' => [
                                        'type' => 'POST',
                                        'url' => Url::to(['/tankermovement/tbl-bmc-milk-dispatch/generate-auto-trip']),
                                        'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#createTripModal").modal("hide");
                                                                $("#tblbmcmilkdispatch-vehicle_code").trigger("change");
                                                                if (data.status == "success"){ 
                                                                    $("#createTripModal").modal("hide");
                                                                    $("#tblbmcmilkdispatch-vehicle_code").trigger("change"); 
                                                                    $("#tblbmcmilkdispatch-vehicle_code").trigger("select2:select");
                                                                    bootbox.alert("<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>"+data.msg+" </span></div></div>", function(){                                                                  
                                                                    });                                                                    
                                                                 }else{
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        if(key=="msg" && key != null){   
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                        }
                                                                    });
                                                                    $(".error-summary").show();                                                                   
                                                                }
                                                 }'),
                                    ],
                                    'options' => [
                                        'class' => 'btn btn-default btn-raised',
                                        'type' => 'submit'
                                    ],
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
</div>

<?php
$script = "
    $(document).ready(function(){
        $('.shift select option[value=\'3\']').remove();
    });
";
$this->registerJs($script, View::POS_END, 'auto-trip-popup');
?>