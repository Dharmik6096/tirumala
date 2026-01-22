<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use kartik\detail\DetailView;

$readonly = $type == 'create' ? FALSE : TRUE;
$btn = $type == 'create' ? 'create' : 'update';
?>
<div id='member_details'>

    <div class="clearfix"></div>
    <div class="form-grid hide-grid-settings remove_cols">
        <?php
        $attributes = [
                [
                'columns' => [
                        [
                        'attribute' => 'provisional_member_code',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'member_code',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'ref_code',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'ex_member_code',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'pro_ex_member_code',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'member_name',
                        'value' => $model->member_name . ' ' . $model->father_name . ' ' . $model->surname,
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
        ];

        echo DetailView::widget([
            'model' => $model,
            'attributes' => $attributes,
            'mode' => 'view',
            'bordered' => true,
            'striped' => false,
            'responsive' => true,
            'hAlign' => 'left',
            'vAlign' => 'top',
            'deleteOptions' => [// your ajax delete parameters
                'params' => ['id' => 1000, 'kvdelete' => true],
            ],
            'container' => ['id' => 'kv-demo'],
        ]);
        ?>
    </div>
    <div class="form-grid hide-grid-settings">
        <?=
        $this->render('@app/modules/dcsoperation/views/tbl-member-provisional-family-details/create', [
            'msearchModel' => $msearchModel,
            'mdataProvider' => $mdataProvider,
            'memberFamilyDetail' => $memberFamilyDetail,
            'memberFamilySearchModel' => $memberFamilySearchModel,
            'memberFamilyDataProvider' => $memberFamilyDataProvider,
            'model' => $model,
        ])
        ?>
    </div>
    <div class="clearfix"></div>
    <?php $form = ActiveForm::begin(['id' => 'add-animal-detail']); ?>
    <?php echo $form->errorSummary([$model, $member_animal_model, $memberShareDetail]); ?>
    <div class="row hr10">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <a class="pull-right" data-bs-toggle="collapse" href="#animalDetailsCollapse">
                            <i id="collapseIcon" class="fa fa-chevron-up"></i>
                        </a>
                        <?= Yii::t('app', 'Animal Details') ?>
                    </div>
                </div>

                <hr class="hr10">
                <div id="animalDetailsCollapse" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php if (!empty($member_animal_model_data)) { ?>
                            <div class="col-sm-12">
                                <table  class="table table-bordered table-striped table-main table-language br_grey bl_grey">
                                    <thead>
                                        <tr>
                                            <th><?= $member_animal_model->getAttributeLabel('animal_type_code') ?></th>
                                            <th><?= $member_animal_model->getAttributeLabel('heifers_count') ?></th>
                                            <th><?= $member_animal_model->getAttributeLabel('milch_animal_count') ?></th>
                                            <th><?= $member_animal_model->getAttributeLabel('dry_animal_count') ?></th>
                                            <th><?= $member_animal_model->getAttributeLabel('total_animal') ?></th>
                                            <th><?= $member_animal_model->getAttributeLabel('daily_milk_production') ?></th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($animals as $animal) {
                                            $member_animal_model->total_animal = $member_animal_model_data[$animal->animal_type_code]->total_animal;
                                            $member_animal_model->heifers_count = $member_animal_model_data[$animal->animal_type_code]->heifers_count;
                                            $member_animal_model->milch_animal_count = $member_animal_model_data[$animal->animal_type_code]->milch_animal_count;
                                            $member_animal_model->dry_animal_count = $member_animal_model_data[$animal->animal_type_code]->dry_animal_count;
                                            $member_animal_model->daily_milk_production = $member_animal_model_data[$animal->animal_type_code]->daily_milk_production;
                                            ?>
                                            <tr>
                                                <td class="hide_help_block" ><?= $animal->animal_type_name ?><?= Html::activeHiddenInput($member_animal_model, '[' . $animal->animal_type_code . ']animal_type_code', ['value' => $animal->animal_type_code]) ?></td>
                                                <td class="hide_help_block"><?= $form->field($member_animal_model, '[' . $animal->animal_type_code . ']heifers_count')->textInput(['class' => 'form-control heifers_count number-validate', 'id' => 'heifers_' . $animal->animal_type_code])->label(false) ?></td>
                                                <td class="hide_help_block"><?= $form->field($member_animal_model, '[' . $animal->animal_type_code . ']milch_animal_count')->textInput(['class' => 'form-control milch_animal_count number-validate', 'id' => 'milch_' . $animal->animal_type_code])->label(false) ?></td>
                                                <td class="hide_help_block"><?= $form->field($member_animal_model, '[' . $animal->animal_type_code . ']dry_animal_count')->textInput(['class' => 'form-control dry_animal_count number-validate', 'id' => 'dry_' . $animal->animal_type_code])->label(false) ?></td>
                                                <td class="hide_help_block"><?= $form->field($member_animal_model, '[' . $animal->animal_type_code . ']total_animal')->textInput(['class' => 'form-control total_animal number-validate', 'id' => 'total_' . $animal->animal_type_code, 'readonly' => true])->label(false) ?></td>
                                                <td class="hide_help_block"><?= $form->field($member_animal_model, '[' . $animal->animal_type_code . ']daily_milk_production')->textInput(['class' => 'form-control daily_milk_production number-validate'])->label(false) ?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $member_animal_model->getAttributeLabel('Total') ?></td>
                                            <td><?= $form->field($member_animal_model, 'no_of_heifers_count')->textInput(['readonly' => true, 'class' => 'form-control number-validate'])->label(false) ?></td>
                                            <td><?= $form->field($member_animal_model, 'no_of_milch_animal_count')->textInput(['readonly' => true, 'class' => 'form-control number-validate'])->label(false) ?></td>
                                            <td><?= $form->field($member_animal_model, 'no_of_dry_animal_count')->textInput(['readonly' => true, 'class' => 'form-control number-validate'])->label(false) ?></td>
                                            <td><?= $form->field($member_animal_model, 'no_of_total_animal')->textInput(['readonly' => true, 'class' => 'form-control number-validate'])->label(false) ?></td>
                                            <td><?= $form->field($model, 'daily_milk_total')->textInput(['readonly' => true, 'class' => 'form-control number-validate'])->label(false) ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= $member_animal_model->getAttributeLabel('Total Milk Production (LPD)') ?></td>
                                            <td><?= $form->field($model, 'home_consumption_milk')->textInput(['class' => 'form-control number-validate']) ?></td>
                                            <td><?= $form->field($model, 'market_surplus_milk')->textInput(['readonly' => true, 'class' => 'form-control number-validate']) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    //share detail
    ?>
    <div class="row hr10">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <a class="pull-right" data-bs-toggle="collapse" href="#shareDetailsCollapse">
                            <i id="shareCollapseIcon" class="fa fa-chevron-up"></i>
                        </a>
                        <?= Yii::t('app', 'Share Details') ?>
                    </div>
                </div>

                <hr class="hr10">
                <div id="shareDetailsCollapse" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="col-sm-2">
                                <?= Yii::$app->dropdown->dropdownStatic('mode_of_payment', $memberShareDetail, $form, 'form-group', $memberShareDetail->getAttributeLabel('mode_of_payment'), false, 'mode_of_payment', false); ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($memberShareDetail, 'ref_no')->textInput() ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($memberShareDetail, 'bank_name')->textInput() ?>
                            </div>
                            <div class="col-sm-2">
                                <?= Yii::$app->controls->date($memberShareDetail, $form, 'deposit_date', '', true); ?>
                            </div>
                            <div class="col-sm-2 number-validate">
                                <?= $form->field($memberShareDetail, 'amount_deposit')->textInput() ?>
                            </div>
                            <div class="col-sm-2 default_hide">
                                <?= $form->field($memberShareDetail, 'no_of_share_req')->textInput(['readonly' => true]) ?>
                            </div>
                            <div class="col-sm-2 number-validate">
                                <?= $form->field($memberShareDetail, 'no_of_share_apply')->textInput() ?>
                            </div>
                            <div class="col-sm-2 number-validate">
                                <?= $form->field($memberShareDetail, 'admission_fee')->textInput(['readonly' => TRUE]) ?>
                            </div>
                            <div class="col-sm-2 number-validate disp_none">
                                <?= $form->field($memberShareDetail, 'per_share_rate')->textInput() ?>
                            </div>
                            <div class="col-sm-2 number-validate">
                                <?= $form->field($memberShareDetail, 'payable_share_amount')->textInput(['readonly' => TRUE]) ?>
                            </div>
                            <div class="col-sm-2 number-validate">
                                <?= $form->field($memberShareDetail, 'amount_payable')->textInput(['readonly' => TRUE]) ?>
                            </div>
                            <div class="col-sm-2 number-validate">
                                <?= $form->field($memberShareDetail, 'total_amount')->textInput(['readonly' => TRUE]) ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group mt20">
            <?= Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); ?>
            <?= Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut', 'data-toggle' => 'modal', 'data-target' => '#ProvisionalModal',]) ?>
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', Yii::t('app', 'NEXT')),
                'useWithActiveForm' => 'add-animal-detail',
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['member-detail', 'id' => $member_animal_model->provisional_member_code]),
                    'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                    'success' => new JsExpression('function(data){
                                                                $(\'#loadercontent\').hide();
                                                                $(\'#pageloader\').hide();
                                                                if (data.status == "error"){ 
                                                                    $("p.help-block").text("");
                                                                    var cnt=0;
                                                                    var errorMessage = "";
                                                                    $.each(data.errors, function(key, value) {
                                                                        errorMessage += value + "<br>";
                                                                        $(".field-" + key + " .help-block").text(value);
                                                                        $("#" + key).closest(".form-group").addClass("has-error");
                                                                    });
                                                                    bootbox.alert({
                                                                        title: "Validation Error",
                                                                        message: errorMessage
                                                                    });
                                                                }else{
                                                                  var successMessage = "' . Yii::t('app', 'Member Animal Details Successfully Created') . '";
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");   
                                                                    $("#member_details").html(data);
                                                                    $(\'#loadercontent\').hide();
                                                                    $(\'#pageloader\').hide();
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-success\'><i class=\'fa fa-check\'></i></div><span>"+successMessage+"</span></div></div>");
                                                                }
                                                 }'),
                ],
                'options' => ['class' => 'btn btn-default btn-raised btn-login saveBtn',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?=
$this->render('@app/modules/document/views/tbl-attachment/_reroute', [
    'model' => $model,
])
?>
<?php ActiveForm::end(); ?>

<?php
$script = "
    setTotalCount();
    setHeifersCount();
    setMilchCount();
    setDryCount();
    setDailyMilk();
    setRemainMilk();
    $('.daily_milk_production').change(function(){
        setDailyMilk();
    });
    
    function setDailyMilk(){
        var amount = parseFloat(0.00);
        $('.daily_milk_production').each(function(i, obj) {
            if(obj.value != '' && !isNaN(obj.value)){
                amount = parseFloat(amount) + parseFloat(obj.value);
            }
        });
        $('#tblmemberprovisional-daily_milk_total').val(amount);
        setRemainMilk();
    }
    
    $('#tblmemberprovisional-home_consumption_milk').change(function(){
        setRemainMilk();
    });
    
    function setRemainMilk(){
        var daily_milk_use = $('#tblmemberprovisional-home_consumption_milk').val();
        var remaining_daily_milk = $('#tblmemberprovisional-daily_milk_total').val();
        if(daily_milk_use != '' && !isNaN(daily_milk_use)){
            remaining_daily_milk = parseFloat(remaining_daily_milk) - parseFloat(daily_milk_use);
        }
        $('#tblmemberprovisional-market_surplus_milk').val(remaining_daily_milk);
    }
    
    $('.dry_animal_count').change(function(){
        var id = $(this).attr('id');
        calculateTotalAnimal(id);
        setDryCount();
    });

    $('.heifers_count').change(function(){
        var id = $(this).attr('id');
        calculateTotalAnimal(id);
        setHeifersCount();
    });

    $('.milch_animal_count').change(function(){
        var id = $(this).attr('id');
        calculateTotalAnimal(id);
        setMilchCount();
    });

    function calculateTotalAnimal(id){
        id = id.split('_');
        id = id[1];
        var heifer = $('#heifers_' + id).val();
        var dry_no = $('#dry_' + id).val();
        var milch_animal_count = $('#milch_' + id).val();
        var total_animal = 0;
        if(dry_no != '' && !isNaN(dry_no)){
            total_animal = parseFloat(total_animal) + parseFloat(dry_no);
        }
        if(heifer != '' && !isNaN(heifer)){
            total_animal = parseFloat(total_animal) + parseFloat(heifer);
        }
        if(milch_animal_count != '' && !isNaN(milch_animal_count)){
            total_animal = parseFloat(total_animal) + parseFloat(milch_animal_count);
        }
        $('#total_' + id).val(total_animal);
        $('.total_animal').trigger('change');
    }

    $('.total_animal').change(function(){
        setTotalAnimal();
        var id = $(this).attr('id');
        setTotalCount();
    });
    
    function setTotalAnimal(){
        var animalCount = parseFloat(0.00);
        $('.total_animal').each(function() {
            if(!isNaN($(this).val())) {
                animalCount = parseFloat(animalCount) + parseFloat($(this).val());
            }
        });
        $('#tblmemberprovisional-total_animal').val(animalCount);
    }
    
    function setHeifersCount(){
        var heifers = $('.heifers_count').val();
        var amount = parseFloat(0.00);
        $('.heifers_count').each(function(i, obj) {
            if(obj.value != '' && !isNaN(obj.value)){
                amount = parseFloat(amount) + parseFloat(obj.value);
            }
        });
        $('#tblmemberprovisionalanimaldetails-no_of_heifers_count').val(amount);
    }
    
    function setMilchCount(){
        var milch = $('.milch_animal_count').val();
        var amount = parseFloat(0.00);
        $('.milch_animal_count').each(function(i, obj) {
            if(obj.value != '' && !isNaN(obj.value)){
                amount = parseFloat(amount) + parseFloat(obj.value);
            }
        });
        $('#tblmemberprovisionalanimaldetails-no_of_milch_animal_count').val(amount);
    }
    
    function setDryCount(){
        var dry = $('.dry_animal_count').val();
        var amount = parseFloat(0.00);
        $('.dry_animal_count').each(function(i, obj) {
            if(obj.value != '' && !isNaN(obj.value)){
                amount = parseFloat(amount) + parseFloat(obj.value);
            }
        });
        $('#tblmemberprovisionalanimaldetails-no_of_dry_animal_count').val(amount);
    }
    
    function setTotalCount(){
        var total_animal = $('.total_animal').val();
        var amount = parseFloat(0.00);
        $('.total_animal').each(function(i, obj) {
            if(obj.value != '' && !isNaN(obj.value)){
                amount = parseFloat(amount) + parseFloat(obj.value);
            }
        });
        $('#tblmemberprovisionalanimaldetails-no_of_total_animal').val(amount);
    }
    
    //collapse icon up & down   
    $(document).ready(function() {
  
        $('#collapseIcon').addClass('fa fa-chevron-up');

        $('#animalDetailsCollapse').on('show.bs.collapse', function() {
            $('#collapseIcon').removeClass('fa fa-chevron-up');
            $('#collapseIcon').addClass('fa fa-chevron-down');
        });

        $('#animalDetailsCollapse').on('hide.bs.collapse', function() {
            $('#collapseIcon').removeClass('fa fa-chevron-down');
            $('#collapseIcon').addClass('fa fa-chevron-up');
        });
        
         $('#shareCollapseIcon').addClass('fa fa-chevron-up');

        $('#shareDetailsCollapse').on('show.bs.collapse', function() {
            $('#shareCollapseIcon').removeClass('fa fa-chevron-up');
            $('#shareCollapseIcon').addClass('fa fa-chevron-down');
        });

        $('#shareDetailsCollapse').on('hide.bs.collapse', function() {
            $('#shareCollapseIcon').removeClass('fa fa-chevron-down');
            $('#shareCollapseIcon').addClass('fa fa-chevron-up');
        });
    });
    
    $('#tblmemberprovisionalsharedetails-no_of_share_apply').on('change', function() {
        var noOfShareApply = parseFloat($(this).val());
        var perShareRate = parseFloat($('#tblmemberprovisionalsharedetails-per_share_rate').val());
        var admissionFee = parseFloat($('#tblmemberprovisionalsharedetails-admission_fee').val());
        var payableShareAmount = noOfShareApply * perShareRate;
        $('#tblmemberprovisionalsharedetails-payable_share_amount').val(payableShareAmount.toFixed(2));
        var amountPayable = payableShareAmount + admissionFee;
        $('#tblmemberprovisionalsharedetails-amount_payable').val(amountPayable.toFixed(2));
        $('#tblmemberprovisionalsharedetails-total_amount').val(amountPayable.toFixed(2));

});   


";
$this->registerJs($script, View::POS_END, 'member-animal-details-script');

