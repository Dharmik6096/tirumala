<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$this->title = 'Member Approval - Animal Details';
?>
<?=
$this->render('approval_tabs', [
    'currentStep' => $currentStep,
    'processModel' => $processModel
]);
?>
<div class="panel panel-default panel-main">
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'animal-detail'
        ]);
        ?>
        <?php echo $form->errorSummary([$model, $member_animal_model]); ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-animal-detail')) { ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom">
                <div class="col-md-12 padding_10_0">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Animal Details') ?></h4>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', false, 'animal_type_code'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'no_of_buffalo')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'no_of_cow_cross')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'no_of_cow_ind')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'total_animals')->textInput(['readonly' => 'disable']) ?>
                    </div>
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
        <?php } ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-commitment-detail')) { ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom">
                <div class="col-md-12 padding_10_0">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Commitment Details') ?></h4>
                    </div>
                    <div class="col-sm-2 number-validate">
                        <?= $form->field($model, 'total_land')->textInput() ?>
                    </div>
                    <div class="col-sm-2 number-validate">
                        <?= $form->field($model, 'annual_milk_pour')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdownStatic('member_class', $model, $form, '', $model->getAttributeLabel('member_class'), false, 'member_class', false); ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row">           
            <div class="col-sm-12 margin-top-10">
                <?php if ($isLastStep) { ?>
                    <div class="col-md-12 padding_10_0 theme-box mt10">
                        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix margin-bottom-10">
                            <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Add Approval Detail') ?></h4>
                        </div>
                        <div class="form-grid">
                            <div class="col-sm-12">
                                <?php echo $form->errorSummary([$processModel, $model]); ?>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <?= Yii::$app->dropdown->dropdownStatic('provisional_approval_status', $processModel, $form, '', $processModel->getAttributeLabel('status'), false, 'status', FALSE, FALSE, FALSE); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?= $form->field($processModel, 'remarks')->textarea(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <div class="form-group">
                        <?php
                        echo Html::hiddenInput('reroute_remarks', '', ['id' => 'reroute_remarks']);
                        echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
                        if ($isLastStep) {
                            echo Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut reroute btn-login me-2', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#ProvisionalModal',]);
                        }
                        $btnLabel = $isLastStep ? 'save' : 'Save & Next';
                        echo Yii::$app->controls->save($btnLabel, $processModel);
                        $prevStep = Yii::$app->controller->getPreviousStepUrl($currentStep, $processModel->process_approval_code);
                        if ($prevStep) {
                            ?>
                            <a href="<?= Url::to(['/dcsoperation/tbl-member-provisional/' . $prevStep[0], 'id' => $prevStep['id']]) ?>" class="btn btn-default btn-login">Previous</a>
                        <?php } ?>
                    </div>  
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?=
$this->render('@app/modules/document/views/tbl-attachment/_reroute', [
    'model' => $model,
])
?>

<?php
$script = "
    $('#tblmemberprovisional-no_of_buffalo, #tblmemberprovisional-no_of_cow_cross, #tblmemberprovisional-no_of_cow_ind').on('change',function(){
            var no_of_buffalo = document.getElementById('tblmemberprovisional-no_of_buffalo').value;
            var no_of_cow_cross = document.getElementById('tblmemberprovisional-no_of_cow_cross').value;
            var no_of_cow_ind = document.getElementById('tblmemberprovisional-no_of_cow_ind').value;
            if(no_of_buffalo == '') {no_of_buffalo = 0}
            if(no_of_cow_cross == '') {no_of_cow_cross = 0}
            if(no_of_cow_ind == '') {no_of_cow_ind = 0}
            var result = parseInt(no_of_buffalo) + parseInt(no_of_cow_cross) + parseInt(no_of_cow_ind);
            if (!isNaN(result)) {
                document.getElementById('tblmemberprovisional-total_animals').value = result;
            }
            $('#tblmemberprovisional-total_animals').prop('readonly', true);
    });
   
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
";
$this->registerJs($script, View::POS_END, 'approval_animal_detail');
?>
