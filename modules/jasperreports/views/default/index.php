<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;

//$this->title = Yii::$app->label->title('view', 'Reports');
$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : '');
$inclass = !empty($result) ? '' : 'in';
$model->p_from_date = empty($model->p_from_date) ? date('d-m-Y') : $model->p_from_date;
$model->p_to_date = empty($model->p_to_date) ? date('d-m-Y') : $model->p_to_date;
$model->p_collection_date = empty($model->p_collection_date) ? date('d-m-Y') : $model->p_collection_date;
?>
<div class="panel panel-default panel-grid panel-main">

    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="report-area">
            <div class="table-responsive mt10 panel-collapse collapse <?= $inclass ?>" id="panel1">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'id' => 'report-form',
                                'field-class' => 'form-group col-sm-3'
                            ], 'validateOnBlur' => FALSE,
                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>    
                <?php //Yii::$app->dropdown->federation($model, $form, 'federation_code', false); ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                </div>     
                <?php
                $param = isset($data['param']) ? explode(',', $data['param']) : [];
                foreach ($param as $key => $value) {
                    $value_array = explode(':', $value);
                    $value = $value_array[0];
                    if (in_array($value, array('dcs_code', 'p_dcs_code', 'pm_dcs_code'))) {
                        ?>
                        <?php
                        if (isset($value_array[1]) && $value_array[1] == 'p_route_code') {
                            $allowmulti = false;
                            if (isset($value_array[2]) && $value_array[2] == 'multiselect') {
                                $allowmulti = true;
                            }
                            $id = 'reportsmodel-' . $value;
                            ?>
                            <div class="col-sm-2">
                                <?php
                                echo Yii::$app->dropdown->route_dcs($model, $form, 'reportsmodel-p_route_code', 'p_dcs_code', 'Society', false, $allowmulti, $id);
                                ?>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-2">
                                <?php
                                echo Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'reportsmodel-union_code', '', 'Society', $value);
                                ?>
                            </div>    
                            <?php
                        }
                    }
                    if (in_array($value, array('p_route_code'))) {
                        ?>

                        <div class="col-sm-2">
                            <?php
                            echo Yii::$app->dropdown->union_routes($model, $form, 'reportsmodel-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Route', $value);
                            ?>
                        </div>    
                        <?php
                    }
                    
                    if (in_array($value, array('p_district_code'))) {
                        ?>
                        <div class="col-sm-3">
                            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State'); ?>
                        </div>
                        <div class="col-sm-2">
                            <?= Yii::$app->dropdown->uniondistrict($model, $form, 'reportsmodel-union_code,reportsmodel-state_code', 'p_district_code', 'District', FALSE); ?>
                        </div>    
                        <?php
                    }
                    
                    if (in_array($value, array('p_sub_district_code'))) {
                        ?>
                        <div class="col-sm-2">
                            <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'reportsmodel-p_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Sub District', 'p_sub_district_code'); ?>
                        </div>    
                        <?php
                    }
                    
                    if (in_array($value, array('p_block_name'))) {
                        ?>
                        <div class="col-sm-2">
                            <?php Yii::$app->dropdown->depend_dropdown('block_code', $model, $form, 'reportsmodel-p_sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Block', 'p_block_name'); ?>
                        </div>    
                        <?php
                    }                    
                    
                    if (isset($value_array[1]) && $value_array[1] == 'string') {
                        ?>
                        <div class="col-sm-2">
                            <?php
                            echo Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-2 padding-left-5 padding-right-5', false);
                            ?>
                        </div>    
                        <?php
                        if (isset($value_array[2])) {
                            ?>
                            <div class="col-sm-2">
                                <?php
                                echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', $model->getAttributeLabel($value_array[2]), false, $value_array[2]);
                                ?>
                            </div>    
                            <?php
                        }
                    }
                    if (in_array($value, array('p_animal_type', 'p_milk_type', 'milk_type'))) {
                        ?>
                        <div class="col-sm-2">
                            <?php
                            echo Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel($value), false, $value, true);
                            ?>
                        </div>    
                        <?php
                    }
                    if (in_array($value, array('p_member_code', 'member_code'))) {
                        $depends = 'reportsmodel-' . $value_array[1];
                        ?>
                        <div class="col-sm-2">
                            <?php
                            echo Yii::$app->dropdown->depend_dropdown('member', $model, $form, $depends, 'form-group col-sm-3 padding-right-5 padding-left-5', 'Member', 'p_member_code');
                            ?>
                        </div>

                        <?php
                    }
                    ?>
                    <?php
                    if (in_array($value, array('with_and_without_milktype'))) {
                        ?>
                        <div class="col-sm-2">
                            <?php
                            echo Yii::$app->dropdown->dropdownStatic('with_and_without_milktype', $model, $form, 'form-group', $model->getAttributeLabel($value), false, $value, false);
                            ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('p_type'))) {
                        ?>
                        <div class="col-sm-2">
                            <?php
                            echo Yii::$app->dropdown->dropdownStatic('p_type', $model, $form, 'form-group', $model->getAttributeLabel($value), false, $value, false);
                            ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('p_dcs_payment'))) {
                        ?>

                        <div class="col-sm-2">
                            <?= Yii::$app->dropdown->unionpaymentcyclewithdate($model, $form, 'reportsmodel-union_code', 'p_dcs_payment', 'Payment Cycle'); ?>
                        </div>    
                        <?php
                    }

                    if (in_array($value, array('p_is_bank'))) {
                        ?>
                        <div class="col-sm-2">
                            <?php
                            echo Yii::$app->dropdown->dropdownStatic('bank_status', $model, $form, 'form-group', $model->getAttributeLabel($value), false, $value, false);
                            ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('p_member_type'))) {
                        ?>

                        <div class="col-sm-2">
                            <?= Yii::$app->dropdown->dropdown('member-type', $model, $form, '', 'Member Type',false,'p_member_type'); ?>
                        </div>   
                        <?php
                    }
                    if (in_array($value, array('p_pouring_qty'))) {?>
                        
                        <div class="col-sm-2">
                            <?= $form->field($model, 'p_pouring_qty')->textInput(['maxlength' => true]) ?>
                        </div>  
                    <?php }
                    if (in_array($value, array('p_no_of_pouring_day'))) {?>
                        
                        <div class="col-sm-3">
                            <?= $form->field($model, 'p_no_of_pouring_day')->textInput(['maxlength' => true]) ?>
                        </div>  
                    <?php }
                }
                if (isset($data['report_type'])) {
                    echo $form->field($model, 'report_type', [ 'options' => ['class' => 'form-group col-sm-3']])->dropDownList($data['report_type'], ['prompt' => Yii::t('app', 'Select Type')]);
                }

                echo Html::activeHiddenInput($model, 'p_union_code');
                echo Html::activeHiddenInput($model, 'p_union_name');
                echo Html::activeHiddenInput($model, 'p_dcs_name');
                echo Html::activeHiddenInput($model, 'p_route_name');
                $model->p_report_name = Html::encode($this->title);
                echo Html::activeHiddenInput($model, 'p_report_name');
                ?>

                <!--            <div class="clearfix"></div>-->
                <div class="col-sm-3 mt25">
                    <?php
                    if ($param) {
                        echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'html', 'id' => 'html']);
                    }
                    ?>
                </div>
            </div>
            <?php if ($result != '') { ?>

                <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <?= GhostHtml::submitButton('<i class="text-danger fa fa-file-pdf-o"></i>', ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'pdf', 'id' => 'pdf', 'title' => Yii::t('app', 'pdf')]); ?>
                    <?php if (!isset($data['pdf'])) { ?>
                        <?= GhostHtml::submitButton('<i class="text-primary fa fa-file-code-o"></i>', ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'csv', 'id' => 'csv', 'title' => Yii::t('app', 'csv')]); ?>
                        <?= GhostHtml::submitButton('<i class="text-success fa fa-file-excel-o"></i>', ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'xls', 'id' => 'xls', 'title' => Yii::t('app', 'xls')]); ?>
                    <?php } ?>
                    <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="text-success fa fa-search"></i></a>
                </div>
            <?php } ?>
        </div>
        <?php if ($result != '') { ?>
            <div class="report-grid">
                <?php echo $result; ?>
            </div>

        <?php }
        ?>
    </div>
</div>            
<?php ActiveForm::end(); ?>
<?php
$script = "
   $(document).ready(function() {
        if('" . Yii::$app->session->get('Federations') . "' != ''){
         var id='" . strtolower((new ReflectionClass($model))->getShortName() . '-federation_code') . "';   
         $('#'+id+' option:selected').val('" . Yii::$app->session->get('Federations') . "');
         $('#'+id).parent('div').hide(); 
         }
         
    });
";
Yii::$app->view->registerJs($script, View::POS_END, strtolower((new ReflectionClass($model))->getShortName() . '-' . 'search'));
?>

<?php
$script = "
       $('#reportsmodel-p_union_name').val($('select#reportsmodel-union_code option:selected').text());
        $('#reportsmodel-p_union_code').val($('select#reportsmodel-union_code option:selected').val());
    $('#reportsmodel-union_code').change(function() {
        $('#reportsmodel-p_union_name').val($('select#reportsmodel-union_code option:selected').text());
        $('#reportsmodel-p_union_code').val($('select#reportsmodel-union_code option:selected').val());
    });
    $('#reportsmodel-p_route_code').change(function() {
        $('#reportsmodel-p_route_name').val($('select#reportsmodel-p_route_code option:selected').text());
    });
    $('#reportsmodel-p_dcs_code').change(function() {
       $('#reportsmodel-p_dcs_name').val($('select#reportsmodel-p_dcs_code option:selected').text()); 
   });
     $('#reportsmodel-with_and_without_milktype').change(function() {
            if($('#reportsmodel-with_and_without_milktype').val()=='With Milk Type' || $('#reportsmodel-with_and_without_milktype').val()==''){
                $('#reportsmodel-p_milk_type').removeAttr('disabled', 'false');
                if($('#reportsmodel-with_and_without_milktype').val()=='') {
                    $('#reportsmodel-p_milk_type').val('');
                } else {
                    $('#reportsmodel-p_milk_type').val(0);
                }
                $('#reportsmodel-p_milk_type option:contains(\'NA\')').remove();  
            }
            else
            {
                $('#reportsmodel-p_milk_type').attr('disabled', 'true');
                //$('#reportsmodel-p_milk_type').html('<option value=\'0\'>NA</option>');
                $('#reportsmodel-p_milk_type option:first').after($('<option/>', { 'value': '','selected':'selected', text: '" . Yii::t('app', 'NA') . "'}));      
            }
    });
    $('#reportsmodel-p_from_date').change(function() {
        var dt = $(this).val();
        $('#reportsmodel-p_to_date').val('');
        $('#reportsmodel-p_to_date').parent().kvDatepicker('setStartDate',dt);
    });
   $(document).ready(function() {
       $('#reportsmodel-from_shift option:contains(\'All\')').remove();    
       $('#reportsmodel-to_shift option:contains(\'All\')').remove();
       $('#reportsmodel-shift option:contains(\'All\')').remove();
       if($('#reportsmodel-with_and_without_milktype').val()=='Without Milk Type'){
            $('#reportsmodel-p_milk_type').attr('disabled', 'true');
            $('#reportsmodel-p_milk_type option:first').after($('<option/>', { 'value': '','selected':'selected', text: '" . Yii::t('app', 'NA') . "'}));      
       }
    });
";
$this->registerJs($script, View::POS_END, 'shift');
?>
<?php
$script = "$('#reportsmodel-p_member_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_member_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
            if('" . $model->p_member_code . "'=='0'){
        $('#reportsmodel-p_member_code').val(0);      
    }
});

if('" . $report . "'=='ConsolidatedMilkCollectionDate' || '" . $report . "'=='ConsolidatedMilkCollectionDateShift' || '" . $report . "'=='ConsolidatedMilkCollectionAllshiftData'){
$('#reportsmodel-p_dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    //$('#reportsmodel-p_dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
});
}

if('" . $report . "'=='ConsolidatedDcsMilkCollectionDate' || '" . $report . "'=='ConsolidatedDcsMilkCollectionDateShift' || '" . $report . "'=='ConsolidatedDcsMilkCollectionDateWithout' || '" . $report . "'=='ConsolidatedDcsMilkCollectionDateShiftWithout' || '" . $report . "'=='ConsolidatedUnionMilkCollectionDate' || '" . $report . "'=='ConsolidatedUnionMilkCollectionDateShift' || '" . $report . "'=='ConsolidatedUnionMilkCollectionDateWithout' || '" . $report . "'=='ConsolidatedUnionMilkCollectionDateShiftWithout' || '" . $report . "'=='DcsCollectionDispatchDifferenceReport' || '" . $report . "'=='DcsCollectionDispatchDifferenceReportWithMilkType'){
$('#reportsmodel-p_dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#reportsmodel-p_dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_dcs_code . "'=='0'){
        $('#reportsmodel-p_dcs_code').val(0);      
    }
    if($('#reportsmodel-union_code').val()=='0'){ 
         $('#reportsmodel-p_dcs_code').prop('disabled',false);
         $('#reportsmodel-p_dcs_code').val(0);
         $('#reportsmodel-p_route_code').prop('disabled',false);
         $('#reportsmodel-p_route_code').val(0);   
    }
    if($('#reportsmodel-p_route_code').val()=='0'){ 
         $('#reportsmodel-p_dcs_code').prop('disabled',false);
         $('#reportsmodel-p_dcs_code').val(0);
    }
});
$('#reportsmodel-p_route_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#reportsmodel-p_route_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_route_code . "'=='0'){
        $('#reportsmodel-p_route_code').val(0);      
    }
});
if('" . $report . "'=='DcsCollectionDispatchDifferenceReport' || '" . $report . "'=='DcsCollectionDispatchDifferenceReportWithMilkType'){ 
} else {
    $('#reportsmodel-union_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));  
          if('" . $model->p_union_code . "'=='0'){
        $('#reportsmodel-union_code').val(0);
         $('#reportsmodel-p_union_name').val('All');
        $('#reportsmodel-p_union_code').val(0);
   }
}
}
if('" . $report . "'=='UnionCollectionDispatchDifferenceReport' || '" . $report . "'=='UnionCollectionDispatchDifferenceReportWithOutMilkType'){ 
    
    $('#reportsmodel-p_route_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#reportsmodel-p_route_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_route_code . "'=='0'){
        $('#reportsmodel-p_route_code').val(0);      
    }
    
    if($('#reportsmodel-union_code').val()=='0'){ 
         $('#reportsmodel-p_route_code').prop('disabled',false);
         $('#reportsmodel-p_route_code').val(0);   
    }
    
    });
    $('#reportsmodel-union_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));  
        if('" . $model->p_union_code . "'=='0'){
        $('#reportsmodel-p_union_code').val(0);
        $('#reportsmodel-union_code').val(0);
    }
}
if('" . $report . "'=='MemberWisePaymentRegister' || '" . $report . "'=='MemberPaymentHeldup' || '" . $report . "'=='ConsolidatedMilkCollectionDate' || '" . $report . "'=='ConsolidatedMilkCollectionDateShift' || '" . $report . "'=='SocietyDetails'){
$('#reportsmodel-p_dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#reportsmodel-p_dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_dcs_code . "'=='0'){
        $('#reportsmodel-p_dcs_code').val(0);      
    }
});
$('#reportsmodel-p_member_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    if($('#reportsmodel-p_dcs_code').val()=='0'){ 
         $('#reportsmodel-p_member_code').prop('disabled',false);
         $('#reportsmodel-p_member_code').val(0);
    }
    
});

}
if('" . $report . "'=='UnionWiseMemberRegister' || '" . $report . "'=='SocietyWiseMemberRegister'){
$('#reportsmodel-p_dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#reportsmodel-p_dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_dcs_code . "'=='0'){
        $('#reportsmodel-p_dcs_code').val(0);      
    }    
});
}

if('" . $report . "'=='BlockWiseCollection'){ 
    $('#reportsmodel-p_block_name').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_block_name option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
            if('" . $model->p_block_name . "'=='0'){
            $('#reportsmodel-p_block_name').val(0);      
        }
        if($('#reportsmodel-p_sub_district_code').val() != ''){
            $('#reportsmodel-p_block_name').removeAttr('disabled', 'false');
        }
    });
    
}
";
$this->registerJs($script, View::POS_READY, 'dep-drop-member');
?>