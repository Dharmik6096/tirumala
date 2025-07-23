<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$url = Url::to(['/applicability/default/load-society']);
$furl = Url::to(['/applicability/default/load-filter-data']);
$bmcUrl = Url::to(['/applicability/default/load-bmc-data']);
$routeUrl = Url::to(['/applicability/default/load-route-data']);
$loadBmcUrl = Url::to(['/applicability/default/load-bmc']);
$model_name = str_replace('\\', '_', $model_name);
$cname = explode('_', $model_name);
$cname = end($cname);
$nameforid = strtolower($cname);
$filter_json = json_encode($filters);
$model->union_code = $union_code;
Url::remember();
$selectedMccCode = !empty($selectedMccCode) ? $selectedMccCode : [];
$selectedMccCode = json_encode($selectedMccCode);
$selectedBmcCode = !empty($selectedBmcCode) ? $selectedBmcCode : [];
$selectedBmcCode = json_encode($selectedBmcCode);
$selectedAppCode = !empty($model->$main_field_name) ? $model->$main_field_name : [];
$selectedAppCode = json_encode($selectedAppCode);
$selectedRouteCode = !empty($selectedRouteCode) ? $selectedRouteCode : [];
$selectedRouteCode = json_encode($selectedRouteCode);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'encodeErrorSummary' => false,
        ]);
$divPrefix = '<div class="col-sm-2 shift">';
$divPostfix = '</div>';
$modelName = 'TblDcsPurchaseRateApplicabitity';
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?php if ($is_union) { ?>
        <div class="col-sm-2" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
        </div>
    <?php } else {
        ?>
        <?= Html::hiddenInput('union', $union_code, ['id' => $nameforid . '-union_code']); ?>
    <?php }
    ?>
    <?php
    foreach ($fields as $key => $f) {
        if (in_array('create', $f['view'])) {
            if (!empty($f['type']) && $f['type'] == 'date') {
                echo $divPrefix . Yii::$app->controls->date($model, $form, $key, '', false) . $divPostfix;
            } else if (!empty($f['type']) && $f['type'] == 'dropdown') {
                if (isset($f['class'])) {
                    $divPrefix = '<div class="col-sm-2">';
                }
                echo $divPrefix . Yii::$app->dropdown->dropdown($f['flag'], $model, $form, '', $model->getAttributeLabel($key), false, $key) . $divPostfix;
            }
        }
    }
    ?>
    <div class="clearfix"></div>
    <?php
    $class = 'col-sm-9 padding_10_0';
    $checkboxClass = 'col-sm-4';
    if (in_array('dcs', $options)) {
        ?>
        <!-- <div class="col-sm-12 mt10">
            <h5 class="panel-subtitle">Apply to</h5> -->
        <div class="col-md-12 padding_10_0 theme-box theme_border_left theme_border_right theme_border_bottom view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Apply To</h4>
            </div>
            <?= Html::radioList('dcs-filter', 'society', $filters, ['separator' => " ", 'id' => 'dcs-filter', 'class' => 'app-radio-list radio-list', 'itemOptions' => ['class' => 'dcs-filter']]); ?>
            <div class="col-sm-3">
                <h4 class="mb15 theme-box-heading" id="header"><?= $title ?></h4>
                <div class="margin_top_15_reverse mb15">
                    <?php
                    /* foreach ($filter_data as $key => $data) {
                      ?>
                      <?=
                      $this->render('_checkbox_list', [
                      'field_name' => $key,
                      'list' => $data, 'selected' => [],
                      ])
                      ?>
                      <?php } */
                    //   '.
                    //                 print Html::textInput('filter','',['class'=>'col-sm-12 margin_bottom_10','id'=> $key,'onkeyup'=>'checkBoxFilter(this)', 'placeholder'=>"Search"])
                    //                 .'
                    foreach ($filters as $key => $data) {
                        if ($key != 'society')
                            echo '<div id="' . $key . '-list" class="row flt app-check-list-bmc" style="display:none"></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    } else if (in_array('tanker_rate', $options)) {
        // change here for BMC MCC Filter
        $modelName = \yii\helpers\StringHelper::basename(get_class($model));
        $class = 'col-sm-12';
        $checkboxClass = 'col-sm-12';
        $appendClass = count($filters) == 1 ? ' disp_none ' : '';
        ?>
            <!-- <div class="col-sm-12 mt10 <?= $appendClass ?>">
                <h5 class="panel-subtitle">Apply to</h5> -->
        <div class="col-md-12 padding_10_0 theme-box theme_border_left theme_border_right theme_border_bottom mt10">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Apply To</h4>
            </div>
            <div class="<?= $appendClass ?>">
                <?= Html::radioList('applicable_for', 'MCC', $filters, ['separator' => " ", 'id' => 'dcs-filter', 'class' => 'app-radio-list radio-list', 'itemOptions' => ['class' => 'applicable_for']]); ?>
            </div>
        </div>
        <?php
    }
    $customerClass = "";
    ?>
    <div class="clearfix"></div>
    <?php
    $class = 'col-sm-12 padding_10_0';
    $checkboxClass = 'col-sm-4';
    if (in_array('dcs_mcc_user', $options)) {
        // change here for BMC MCC Filter
        $modelName = \yii\helpers\StringHelper::basename(get_class($model));
        $class = 'col-sm-12';
        $checkboxClass = 'col-sm-12';
        $appendClass = count($filters) == 1 ? 'disp_none' : '';
        ?>
        <div class="col-md-12 padding_10_0 theme-box theme_border_left theme_border_right theme_border_bottom mt10 <?= $appendClass ?>">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Apply To</h4>
            </div>
            <div class="<?= $appendClass ?>">
                <?= Html::radioList('applicable_for', 'MCC', $filters, ['separator' => " ", 'id' => 'dcs-filter', 'class' => 'app-radio-list radio-list', 'itemOptions' => ['class' => 'applicable_for']]); ?>
            </div>
        </div>
        <?php
    }
    $customerClass = "";
    ?>
    <?php
    if ($customer_type_wise_entry) {
        $customerClass = "customerTypeValidate";
        $hideClass = $hideCustomerType ? ' disp_none ' : '';
        ?>
        <div class="col-md-12 padding_10_0 theme-box theme_border_left theme_border_right theme_border_bottom mt10 customerTypeEntries <?= $customerClass ?> <?= $hideClass ?>">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Apply To</h4>
            </div>
        <!-- <div class="col-sm-12 mt10 customerTypeEntries <?= $customerClass ?> <?= $hideClass ?>">
            <h5 class="panel-subtitle">Apply to</h5> -->

            <div id="dcs-wrap">
                <?=
                $this->render('_checkbox_list', [
                    'model' => $model, 'form' => $form, 'field_name' => $customer_type_field_name,
                    'list' => $customer_type_list, 'selected' => $selected_customer_type, 'selectedData' => $selectedTypes, 'checkboxClass' => 'col-sm-2 apply_to_checkbox',
                ])
                ?>
            </div>
        </div>
    <?php } ?>
    <div class="<?= $class ?>">
        <div class="col-sm-4 padding-left-0 selectMccArea disp_none">
            <h4 class="theme-box-heading padding_left_0 padding_right_0"><?= Yii::t('app', 'MCC List') ?></h4>
            <div class="app-check-list-mcc ">
                <div class="form-group">
                    <div class="checkbox app-check-all-mcc app-check-list-padding">
                        <?php $field_name_for_filter = 'f_mcc_code' ?>
                        <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => $field_name_for_filter, 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
                        <label class="route-text">
                            <?= Html::checkbox('checkall', false, ['id' => 'checkAllMccList', 'class' => 'mcc-list-checkbox']) ?>
                            <label for="checkAllMccList"><?= Yii::t('app', 'Check ALL MCC') ?></label>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="mcc-wrap checkAllMcc" class=" app-check-list-padding <?= $customerClass ?>">
                    <?=
                    $this->render('_checkbox_list', [
                        'model' => $model, 'form' => $form, 'field_name' => $field_name_for_filter,
                        'list' => $mccList, 'selected' => [], 'selectedData' => [], 'checkboxClass' => 'col-sm-12',
                        'checkboxWidthClass' => 'col-sm-6', 'idPrefix' => 'mcc',
                        'checkboxClass' => ' flt-checkbox mccCheckboxes', 'setCheckboxClass' => 'mccCheck'
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 padding-left-0 selectBmcArea disp_none">
            <h4 class="theme-box-heading padding_left_0 padding_right_0"><?= Yii::t('app', 'BMC List') ?></h4>
            <div class="app-check-list-bmc ">
                <div class="form-group">
                    <div class="checkbox app-check-all-bmc app-check-list-padding">
                        <?php $field_name_for_filter = 'f_bmc_code' ?>
                        <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => $field_name_for_filter, 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
                        <label class="route-text">
                            <?= Html::checkbox('checkall', false, ['id' => 'checkAllBmcList', 'class' => 'bmc-list-checkbox']) ?>
                            <label for="checkAllBmcList"><?= Yii::t('app', 'Check ALL BMC') ?></label>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="bmc-wrap checkAllBmc" class=" app-check-list-padding <?= $customerClass ?>">
                    <?=
                    $this->render('_checkbox_list', [
                        'model' => $model, 'form' => $form, 'field_name' => $field_name_for_filter,
                        'list' => [], 'selected' => $selectedBmcCode, 'selectedData' => [], 'checkboxClass' => 'col-sm-12',
                        'checkboxWidthClass' => 'col-sm-6', 'idPrefix' => 'bmc',
                        'checkboxClass' => ' flt-checkbox bmcCheckboxes', 'setCheckboxClass' => 'bmcCheck'
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 padding-left-0 selectRouteArea disp_none">
            <h4 class="theme-box-heading padding_left_0 padding_right_0"><?= Yii::t('app', 'Route List') ?></h4>
            <div class="app-check-list-bmc ">
                <div class="form-group">
                    <div class="checkbox app-check-all-route app-check-list-padding">
                        <?php $field_name_for_filter = 'f_route_code' ?>
                        <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => $field_name_for_filter, 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
                        <label class="route-text">
                            <?= Html::checkbox('checkall', false, ['id' => 'checkAllRouteList', 'class' => 'route-list-checkbox']) ?>
                            <label for="checkAllRouteList"><?= Yii::t('app', 'Check ALL Route') ?></label>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="route-wrap checkAllRoute" class=" app-check-list-padding <?= $customerClass ?>">
                    <?=
                    $this->render('_checkbox_list', [
                        'model' => $model, 'form' => $form, 'field_name' => $field_name_for_filter,
                        'list' => [], 'selected' => $selectedRouteCode, 'selectedData' => [], 'checkboxClass' => 'col-sm-12',
                        'checkboxWidthClass' => 'col-sm-6', 'idPrefix' => 'route',
                        'checkboxClass' => ' flt-checkbox routeCheckboxes', 'setCheckboxClass' => 'routemcCheck'
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 padding-left-0 padding-right-0 applicableCodeArea">
            <h4 class="theme-box-heading padding_left_0 padding_right_0"><?= Yii::t('app', $title) ?> List</h4>
            <div class="app-check-list ">
                <div class="form-group">
                    <div class="checkbox app-check-all app-check-list-padding">
                        <?php $field_name_for_filter = $main_field_name ?>
                        <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => $field_name_for_filter, 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
                        <label class="route-text">
                            <?= Html::checkbox('checkall', false, ['id' => 'checkAll', 'class' => 'route-checkbox']) ?>
                            <label for="checkAll">Check All <?= $title ?></label>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="dcs-wrap" class=" app-check-list-padding <?= $customerClass ?>">
                    <?=
                    $this->render('_checkbox_list', [
                        'model' => $model, 'form' => $form, 'field_name' => $field_name_for_filter,
                        'list' => $dcs_list, 'selected' => $selected, 'selectedData' => $selectedCodes, 'checkboxClass' => $checkboxClass,
                        'checkboxClass' => ' flt-checkboxa dcsCheckboxes'
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$from_date = !empty($model->from_date) ? $model->from_date : '';
$to_date = !empty($model->to_date) ? $model->to_date : '';
$script = "
    $('.kv-panel-before').hide();
    var periodic_applicability = '{$periodic_applicability}';
    var check_applicability_with_field_name = '{$check_applicability_with_field_name}';
    var from_date = '{$from_date}';
    var to_date = '{$to_date}';
    var is_bulk_notification = '{$is_bulk_notification}';
    var load_data_on_apply_to_checkbox = '{$load_data_on_apply_to_checkbox}';
    function checkBoxFilter(val){
        var id = $(val).attr('id');
        var value = $(val).val();
        count = 0;
        $('#'+id+'-list div').each(function() {
            if ($(this).text().search(new RegExp(value, 'i')) < 0) {
                $(this).hide();
                $(this).find(':input').prop('disabled', true);
            } else {
                $(this).show();
                $(this).find(':input').prop('disabled', false);
                count++;
            }
        });
    }

    var selectedMcc = [];
    var selectedBmc = [];
    var selectedSociety = [];
    $('#w0').submit(function() {
            //$('#loadercontent').show();
            //$('#pageloader').show();
      });
    $('#checkAll').click(function (event) {
        //console.log('here');
        event.stopPropagation();
        var chk=$(this);
        var checked=[];
        $('.route-checkbox').not('.flt-checkbox').not(':disabled').prop('checked', this.checked);
        if($(this).is(':checked'))
        {
            $('.route-checkbox').not('.flt-checkbox').not('#checkAll').not(':disabled').each(function(){
                checked.push($(this).val());
            });
            var flag='check';
            var id= 0;
            addSociety(flag,checked,chk);
        }
    });
    
     $(document).on('click', '.route-checkbox',function(){
        if($(this).is(':checked') && !($(this).hasClass('flt-checkbox')))
        {
            var flag='check';
            var checked=[$(this).val()];
            addSociety(flag,checked,$(this));
        }
    });
    
    $(document).on('click', '.dcs-filter',function(event){
        $('.route-checkbox').prop('checked', false);
        event.stopPropagation();
        var fl=$(this).val();
        $('.flt').each(function(){
            $(this).hide();
        });
        if(fl==='society')
        {
            var chkbx='';
            var flag=fl;
            var id= 0;
            addSociety(flag,id,chkbx);
        }
       var fltl=$(this).closest('label').text();
       addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val());
        $('#checkAll').prop('checked', false);
        $('#header').text(fltl);
        setTimeout(function(){ $('#'+fl+'-list').show(); }, 500);
        $('#dcs_code-list').empty();
    });
    $(document).ready(function(){
        $('.customerTypeEntries input').removeClass('route-checkbox');
        if($('#{$nameforid}-union_code').val() !== '')
        {
            addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val());
//            addSociety('society',0,'');
        }
//        $('#dcs-filter input[type=\'radio\']:first').attr('checked', true);
        // use for select default first radio button
        $('#dcs-filter input[type=\'radio\']:first').trigger('click');
//        addFilterData($('#{$nameforid}-union_code').val(), $('#dcs-filter input[type=\'radio\']:first').val(), 'applicable_code');
        setMccBmcData();
    });
    $('#{$nameforid}-union_code').on('change',function(){
        addFilterData($(this).val(), $('input[type=\'radio\']:checked').val());
        $('#dcs_code-list').empty();
        if($('input[name=dcs-filter]:checked').val()==='society')
        {
            addSociety('society',0,'');
        }
    });
    
     $('#{$nameforid}-wef_date').on('change',function(){
        $('#dcs_code-list').empty();
        var appCode = '';
        if($('input[name=dcs-filter]:checked').val() == undefined) {
            appCode = 'applicable_code';
        }
        addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val(), appCode);
        if($('input[type=\'radio\']:checked').val()==='society')
        {
            addSociety('society',0,'');
        }
    });
    
    $(document).on('change', '.flt-checkbox', function(event) {
        event.stopPropagation();
        var chkbx=this;
        var flag=$(this).attr('data-flt');
        var id= $(this).val();
        addSociety(flag,id,chkbx);  
    })
    $(document).on('click', '.applicable_for',function(event){
        selectedSociety = [];
        $('.getTitleForThis').removeClass('getTitleForThis');
        $(this).closest('label').addClass('getTitleForThis');
        $('.app-check-list h4').text($('.getTitleForThis').text() + ' List');
        $('.app-check-all label label').text('Check All '+$('.getTitleForThis').text());
        addFilterData($('#{$nameforid}-union_code').val(), $(this).val(), 'applicable_code');
    });
    function addFilterData(ucode, filter_type = '', applicable_for = '', selectAppCode = [])
    {
        $('#checkAll').prop('checked', false);
        var checkFilter = filter_type.toLowerCase();
        var setClass = 'col-sm-2';
        if(checkFilter == 'bulkven' || checkFilter == 'vlccven' || checkFilter == 'dcs'  || checkFilter == 'farm') {
            $('.selectMccArea').show();
            $('.selectBmcArea').show();
            $('.selectRouteArea').show();
            $('.applicableCodeArea').removeClass('col-sm-12');
            $('.applicableCodeArea').addClass('col-sm-12');
            setClass = 'col-sm-2';
//            $('.applicableCodeArea .dcs-checklist').addClass('col-sm-2');
//            $('.applicableCodeArea .dcs-checklist').removeClass('col-sm-4');
        }else if (is_bulk_notification == 1) {
            $('.selectMccArea').show();
             setClass = 'col-sm-6';
             $('.applicableCodeArea').removeClass('col-sm-12');
             $('.applicableCodeArea').addClass('col-sm-6');
        }else {
            $('#f_bmc_code-list').empty();
            $('.mccCheckboxes').prop('checked',false);
            $('#checkAllMccList').prop('checked',false);
            $('#checkAllBmcList').prop('checked',false);
            setClass = 'col-sm-2';
            $('.selectMccArea').hide();
            $('.selectBmcArea').hide();
            $('.selectRouteArea').hide();
            $('.applicableCodeArea').addClass('col-sm-12');
            $('.applicableCodeArea').removeClass('col-sm-6');
//            $('.applicableCodeArea .dcs-checklist').removeClass('col-sm-2');
//            $('.applicableCodeArea .dcs-checklist').addClass('col-sm-4');
        }
        if(checkFilter == 'society') {
            setClass = 'col-sm-12';
        }
        if(filter_type)
        var appendId='nd';
        var flts=JSON.stringify({$filter_json});
        var fld='{$field_name}';
        var fldcode='{$field_code}';
        var mname='{$model_name}';
        var wef_date=$('#{$nameforid}-wef_date').val();
        if($('#{$nameforid}-from_date').length > 0){
            from_date=$('#{$nameforid}-from_date').val();    
        }
        if($('#{$nameforid}-to_date').length > 0){
            to_date=$('#{$nameforid}-to_date').val(); 
        }            
        var checkdate='{$check_wef_date}';
        var login_type='{$login_type}';    
            
        selectedBmc = [];
        $('.bmcCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedBmc.push($(this).val());
            }   
        });
        selectedMcc = [];
        $('.mccCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedMcc.push($(this).val());
            } 
        });
        selectedSociety = [];
        $('.dcsCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedSociety.push($(this).val());
            } 
        });
        selectedRoute = [];
        $('.routeCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedRoute.push($(this).val());
            } 
        });
        $.ajax({
            type: 'post',
            url: '{$furl}',
            data: {'login_type':login_type,'ucode':ucode,'filters':flts,'filter_type':filter_type,'field':fld,'fcode':fldcode, 'mname' : mname,'wef_date':wef_date,'checkdate':checkdate,'selected_mcc':JSON.stringify(selectedMcc),'selected_bmc':JSON.stringify(selectedBmc),'selected_route':JSON.stringify(selectedRoute),'from_date':from_date,'to_date':to_date,'periodic_applicability':periodic_applicability,'is_bulk_notification':is_bulk_notification,'check_applicability_with_field_name':check_applicability_with_field_name},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if (obj1.status == 'success')
                {
                    $.each(obj1.data, function(index, value) {
                        $('#'+index+'-list').empty();
                        appendId = index;
                        // $('#'+index+'-list').parent().append('<div class=\"checkbox app-check-all app-check-list-padding padding_bottom_25\"><input type=\"text\" id=\"'+index+'\" class=\"col-sm-12\" name=\"filter\" value=\"\" onkeyup=\"checkBoxFilter(this)\" placeholder=\"Search\"></div>' );
                        $.each(value, function(ind, vl) {
                            if(applicable_for == ''){
                               $('#'+index+'-list').append('<div class=\"col-sm-12 dcs-checklist checklist\" id=\"nd-'+ind+'\"><div class=\"checkbox\"><input type=\"checkbox\" data-flt=\"'+index+'\" class=\"route-checkbox flt-checkbox\" name=\"'+index+'[]\" value=\"'+ind+'\" id=\"'+appendId+'-'+ind+'\"><label class=\"route-text\" for=\"'+appendId+'-'+ind+'\">'+vl+'</label></div></div>');
                            } else {
                                var modelName = '" . $modelName . "';
                                $('#'+index+'-list').append('<div class=\"'+setClass+' dcs-checklist checklist\" id=\"nd-'+ind+'\"><div class=\"checkbox\"><label class=\"route-text\"><input type=\"checkbox\" class=\"route-checkbox dcsCheckboxes\" name=\"'+modelName+'[applicable_code][]\" value=\"'+ind+'\" id=\"'+ind+'\"><label for=\"'+ind+'\">'+vl+'</label></label></div></div>');
                                
                                $('.dcsCheckboxes').each(function () {
                                    var checkVal = $(this).val();
                                    if (selectedSociety.indexOf(checkVal) >= 0) {
                                        $(this).prop('checked', true);
                                    }
                                });
                                if(selectAppCode.length > 0) {  
                                    $('.dcsCheckboxes').each(function () {
                                        var checkVal = $(this).val();
                                        if (selectAppCode.indexOf(checkVal) >= 0) {
                                            $(this).prop('checked', true);
                                        }
                                    });
                                }
                            }
                        });
                    });
                }
            },
            error:function(data){
                    //alert('Your data has not been submitted..Please try again');
            }
        });  
    }
    function addSociety(flag,id,chkbx)
    {
        var ucode=$('#{$nameforid}-union_code').val();
        var fld='{$field_name}';
        var fldcode='{$field_code}';
        var mname='{$model_name}';
        var top_section='{$top_section}';
        var select_from_all='{$select_from_all}';
        var payment='{$payment}';
        var wef_date=$('#{$nameforid}-wef_date').val();       
        var shift_type='{$shift_type}';
        var ratechart='{$ratechart}';    
        $.ajax({
                        type: 'post',
                        url: '{$url}',
                        data: {'flag':flag,'id':id,'ucode':ucode,'field':fld,'fcode':fldcode,'mname':mname,'select_from_all':select_from_all,'payment':payment,'shift_type':shift_type,'wef_date':wef_date,'ratechart':ratechart},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                if(obj1.dcsalert != '' && $(chkbx).is(':checked') && flag=='check'){  
                                    bootbox.alert('<div class=\'mt15 mb15 display-table\'><div class=\'col-sm-12\'>'+obj1.dcsalert+'</div></div>');
                                    $.each(obj1.dcsarray, function(index, value) {   
                                              $('#'+value).prop('checked', false);  
                                        });              
    }
                                if((!($.isEmptyObject(chkbx)) && chkbx.checked) || (flag=='society')) {
                                    var flag_check = $('input[type=\'radio\']:checked').val();
                                    if(flag_check == flag){
                                        $.each(obj1.data, function(index, value) {
                                            $('#dcs_code-list').append('<div class=\"col-sm-3 dcs-checklist checklist\" id=\"nd-'+index+'\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"route-checkbox\" name=\"{$cname}[dcs_code][]\" value=\"'+index+'\" id=\"'+index+'\"><label class=\"route-text\" for=\"'+index+'\">'+value+'</label></div></div>');
                                        });
                                    }
                                }
                                else if(!($.isEmptyObject(chkbx)) && !(chkbx.checked)){
                                    $.each(obj1.data, function(index, value) {
                                        $('#nd-'+index).remove();
                                    });
                                }
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });      
    }
    $('#checkAllMccList').click(function (event) {
        $('#checkAll').prop('checked', false);
        $('.mccCheckboxes').not(':disabled').prop('checked', $(this).is(':checked'));
        setBmcList();
    });
    
    $('.mccCheckboxes').click(function (event) {
        setBmcList();
    });
    function setBmcList(selectBmc = [],selectAppCode = []){
        $('#checkAll').prop('checked', false);
        $('#checkAllBmcList').prop('checked',false);
        $('.routeCheckboxes').prop('checked',false);
        selectedBmc = [];
        $('.bmcCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedBmc.push($(this).val());
            }   
        });
        selectedMcc = [];
        $('.mccCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedMcc.push($(this).val());
            } 
        });
        var unionCode = $('#{$nameforid}-union_code').val();
        $('#f_bmc_code-list').empty();
//        console.log(selectedMcc);
//        return false;
        $.ajax({
            type: 'post',
            url: '{$bmcUrl}',
            data: {'selected_mcc':JSON.stringify(selectedMcc),'union_code':unionCode},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if (obj1.status == 'success') {
                    $.each(obj1.data, function(index, value) {
                        $('#f_bmc_code-list').append('<div class=\"col-sm-6 bmc-checklist checklist\" id=\"bmc-'+index+'\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"bmcCheck flt-checkboxa bmcCheckboxes\" name=\"{$cname}[f_bmc_code][]\" value=\"'+index+'\" id=\"bmc-input-'+index+'\"><label class=\"route-text\" for=\"bmc-input-'+index+'\">'+value+'</label></div></div>');
                    });
                    $('.bmcCheckboxes').each(function () {
                        var checkVal = $(this).val();
                        if (selectedBmc.indexOf(checkVal) >= 0) {
                            $(this).prop('checked', true);
                        }
                    });
                    if(selectBmc.length > 0) {
                        $('.bmcCheckboxes').each(function () {
                            var checkVal = $(this).val();
                            if (selectBmc.indexOf(checkVal) >= 0) {
                                $(this).prop('checked', true);
                            }
                        });
                    }
                    setRouteList();
//                    addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val(), 'applicable_code', selectAppCode);
                }
            },
            error:function(data) {
                    //alert('Your data has not been submitted..Please try again');
                }
        }); 
    }
    $('#checkAllBmcList').click(function (event) {
        $('.bmcCheckboxes').not(':disabled').prop('checked', $(this).is(':checked'));
        setRouteList();
//        addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val(), 'applicable_code');
    });
    
    $(document).on('click', '.bmcCheckboxes', function(){
            setRouteList();
//        addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val(), 'applicable_code');
    });
    
    $('#checkAllRouteList').click(function (event) {
        $('.routeCheckboxes').not(':disabled').prop('checked', $(this).is(':checked'));
        addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val(), 'applicable_code');
    });
    
    $(document).on('click', '.routeCheckboxes', function(){
        addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val(), 'applicable_code');
    });
    
    function setMccBmcData(){
        $('#checkAll').prop('checked', false);
        var selectMcc = '" . $selectedMccCode . "';
        var selectBmc = '" . $selectedBmcCode . "';
        var selectRoute = '" . $selectedRouteCode . "';
        selectMcc = JSON.parse(selectMcc);
        $('.mccCheckboxes').each(function () {
            var checkVal = $(this).val();
            if (selectMcc.indexOf(checkVal) >= 0) {
                $(this).prop('checked', true);
            }
        });
        var selectAppCode = '" . $selectedAppCode . "';
        selectAppCode = JSON.parse(selectAppCode);
        selectBmc = JSON.parse(selectBmc);
        setBmcList(selectBmc, selectAppCode);
        selectRoute = JSON.parse(selectRoute);
        setRouteList(selectRoute, selectAppCode);
    }
    
    function setRouteList(selectRoute = [],selectAppCode = []){
        $('#checkAllRouteList').prop('checked',false);
        $('.routeCheckboxes').prop('checked',false);
        $('#checkAll').prop('checked', false);
        selectedRoute = [];
        $('.routeCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedRoute.push($(this).val());
            }   
        });        
        selectedBmc = [];
        $('.bmcCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedBmc.push($(this).val());
            }   
        });
        selectedMcc = [];
        $('.mccCheckboxes').each(function () {
            if ($(this).is(':checked')) {
                selectedMcc.push($(this).val());
            } 
        });
        var unionCode = $('#{$nameforid}-union_code').val();
        $('#f_route_code-list').empty();
//        console.log(selectedMcc);
//        return false;
        $.ajax({
            type: 'post',
            url: '{$routeUrl}',
            data: {'selected_mcc':JSON.stringify(selectedMcc),'union_code':unionCode,'selected_bmc':JSON.stringify(selectedBmc)},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if (obj1.status == 'success') {
                    $.each(obj1.data, function(index, value) {
                        $('#f_route_code-list').append('<div class=\"col-sm-6 route-checklist checklist\" id=\"route-'+index+'\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"routeCheck flt-checkboxa routeCheckboxes\" name=\"{$cname}[f_bmc_code][]\" value=\"'+index+'\" id=\"route-input-'+index+'\"><label class=\"route-text\" for=\"route-input-'+index+'\">'+value+'</label></div></div>');
                    });
                    $('.routeCheckboxes').each(function () {
                        var checkVal = $(this).val();
                        if (selectedRoute.indexOf(checkVal) >= 0) {
                            $(this).prop('checked', true);
                        }
                    });
                    if(selectRoute.length > 0) {
                        $('.routeCheckboxes').each(function () {
                        var checkVal = $(this).val();
                        if (selectRoute.indexOf(checkVal) >= 0) {
                            $(this).prop('checked', true);
                        }
                    });
                    }
                    addFilterData($('#{$nameforid}-union_code').val(), $('input[type=\'radio\']:checked').val(), 'applicable_code', selectAppCode);
                }
            },
            error:function(data) {
                    //alert('Your data has not been submitted..Please try again');
                }
        }); 
    }
    if(load_data_on_apply_to_checkbox){
        loadData();
        $(document).on('change', 'input.apply_to_checkbox:checkbox', function() {
            loadData();
        })
        function loadData() {
            var checkedCheckboxes = $('input.apply_to_checkbox:checkbox:checked');
            var selectedValues = [];
            var unionCode = $('#{$nameforid}-union_code').val();
            $('#applicable_code-list').html('');
            if (checkedCheckboxes.length > 0) {
                checkedCheckboxes.each((index, checkboxElement) => {
                    selectedValues.push($(checkboxElement).val());
                });
                var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
                $.ajax({
                    type: 'post',
                    url: '{$loadBmcUrl}',
                    data: {'_csrf': csrfToken,'selected_apply_to':JSON.stringify(selectedValues),'union_code':unionCode, 'field_code': '{$field_code}', 'class_name':'{$cname}', 'field_name':'{$field_name}'},
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if (obj1.status == 'success') {
                            var html = '';
                            $.each(obj1.data, function(index, value) {
                                html = html+'<div class=\"col-sm-2 dcs-checklist checklist\" id=\"nd-'+index+'\"><div class=\"checkbox\"><label class=\"route-text\"><input type=\"checkbox\" class=\"route-checkbox flt-checkboxa dcsCheckboxes\" name=\"{$cname}[applicable_code][]\" value=\"'+index+'\" id=\"nd-input-'+index+'\"><label for=\"nd-input-'+index+'\">'+value+'</label></label></div></div>';
                            });
                            $('#applicable_code-list').html(html);
                        }
                    },
                    error:function(data) {
                    }
                }); 
            }
        }
    }

";
//if ($customer_type_wise_entry) {
//    $script .= "
//        $('.customerTypeValidate input').on('click', function(){
//            validateCustomers();
//        });
//        function validateCustomers(){
//            
//        }
//    ";
//}
$this->registerJs($script, View::POS_END, 'village-code');
