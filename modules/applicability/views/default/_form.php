<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$url = Url::to(['/applicability/default/load-society']);
$furl = Url::to(['/applicability/default/load-filter-data']);
$model_name = str_replace('\\', '_', $model_name);
$cname = explode('_', $model_name);
$cname = end($cname);
$nameforid = strtolower($cname);
$filter_json = json_encode($filters);
$model->union_code = $union_code;
Url::remember();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
$divPrefix = '<div class="col-sm-3 shift">';
$divPostfix = '</div>';
$modelName = 'TblDcsPurchaseRateApplicabitity';
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?php if ($is_union) { ?>
        <div class="col-sm-3" id="union">
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
                    $divPrefix = '<div class="col-sm-3 ' . $f['class'] . '">';
                }
                echo $divPrefix . Yii::$app->dropdown->dropdown($f['flag'], $model, $form, '', 'Shift', false, $key) . $divPostfix;
            }
        }
    }
    ?>
    <div class="clearfix"></div>
    <?php
    $class = 'col-sm-9';
    $checkboxClass = 'col-sm-4';
    if (in_array('dcs', $options)) {
        ?>
        <div class="col-sm-12 mt10">
            <h5 class="panel-subtitle">Apply to</h5>
            <?= Html::radioList('dcs-filter', 'society', $filters, ['separator' => " ", 'id' => 'dcs-filter', 'class' => 'app-radio-list radio-list', 'itemOptions' => ['class' => 'dcs-filter']]); ?>
        </div>
        <div class="col-sm-3">
            <div class="app-header-list">
                <h4 class="mt10 mb15" id="header"><?= $title ?></h4>
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
                foreach ($filters as $key => $data) {
                    if ($key != 'society')
                        echo '<div id="' . $key . '-list" class="row flt" style="display:none"></div>';
                }
                ?>
            </div>
        </div>
        <?php
    } else if (in_array('tanker_rate', $options)) {
        $modelName = \yii\helpers\StringHelper::basename(get_class($model));
        $class = 'col-sm-12';
        $checkboxClass = 'col-sm-3';
        ?>
        <div class="col-sm-12 mt10">
            <h5 class="panel-subtitle">Apply to</h5>
            <?= Html::radioList('applicable_for', 'MCC', $filters, ['separator' => " ", 'id' => 'dcs-filter', 'class' => 'app-radio-list radio-list', 'itemOptions' => ['class' => 'applicable_for']]); ?>
        </div>
    <?php } ?>
    <div class="<?= $class ?>">
        <div class="app-check-list">
            <h4><?= $title ?> List</h4>
            <div class="form-group">
                <div class="checkbox app-check-all">
                    <label class="route-text">
                        <?= Html::checkbox('checkall', false, ['id' => 'checkAll', 'class' => 'route-checkbox']) ?>
                        <label for="checkAll">Check All <?= $title ?></label>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="dcs-wrap">
                <?=
                $this->render('_checkbox_list', [
                    'model' => $model, 'form' => $form, 'field_name' => $main_field_name,
                    'list' => $dcs_list, 'selected' => $selected, 'checkboxClass' => $checkboxClass,
                ])
                ?>
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
$script = "
    $('#w0').submit(function() {
            //$('#loadercontent').show();
            //$('#pageloader').show();
      });
    $('#checkAll').click(function (event) {
        //console.log('here');
        event.stopPropagation();
        var chk=$(this);
        var checked=[];
        $('.route-checkbox').not('.flt-checkbox').prop('checked', this.checked);
        if($(this).is(':checked'))
        {
            $('.route-checkbox').not('.flt-checkbox').not('#checkAll').each(function(){
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
        $('#checkAll').prop('checked', false);
        $('#header').text(fl);
        $('#'+fl+'-list').show();
        $('#dcs_code-list').empty();
    });
    $(document).ready(function(){
        if($('#{$nameforid}-union_code').val() !== '')
        {
            addFilterData($('#{$nameforid}-union_code').val());
//            addSociety('society',0,'');
        }
//        $('#dcs-filter input[type=\'radio\']:first').attr('checked', true);
        $('#dcs-filter input[type=\'radio\']:first').trigger('click');
//        addFilterData($('#{$nameforid}-union_code').val(), $('#dcs-filter input[type=\'radio\']:first').val(), 'applicable_code');
    });
    $('#{$nameforid}-union_code').on('change',function(){
        addFilterData($(this).val());
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
        $('.getTitleForThis').removeClass('getTitleForThis');
        $(this).closest('label').addClass('getTitleForThis');
        $('.app-check-list h4').text($('.getTitleForThis').text() + ' List');
        $('.app-check-all label label').text('Check All '+$('.getTitleForThis').text());
        addFilterData($('#{$nameforid}-union_code').val(), $(this).val(), 'applicable_code');
    });
    function addFilterData(ucode, filter_type = '', applicable_for = '')
    {
        var appendId='nd';
        var flts=JSON.stringify({$filter_json});
        var fld='{$field_name}';
        var fldcode='{$field_code}';
        var mname='{$model_name}';
         var wef_date=$('#{$nameforid}-wef_date').val();  

        $.ajax({
                        type: 'post',
                        url: '{$furl}',
                        data: {'ucode':ucode,'filters':flts,'filter_type':filter_type,'field':fld,'fcode':fldcode, 'mname' : mname,'wef_date':wef_date},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.each(obj1.data, function(index, value) {
                                        $('#'+index+'-list').empty();
                                        appendId = index;
                                        $.each(value, function(ind, vl) {
                                            if(applicable_for == ''){
                                               $('#'+index+'-list').append('<div class=\"col-sm-12 dcs-checklist checklist\" id=\"nd-'+ind+'\"><div class=\"checkbox\"><input type=\"checkbox\" data-flt=\"'+index+'\" class=\"route-checkbox flt-checkbox\" name=\"'+index+'[]\" value=\"'+ind+'\" id=\"'+appendId+'-'+ind+'\"><label class=\"route-text\" for=\"'+appendId+'-'+ind+'\">'+vl+'</label></div></div>');
                                            } else {
                                                var modelName = '" . $modelName . "';
                                                $('#'+index+'-list').append('<div class=\"col-sm-3 dcs-checklist checklist\" id=\"nd-'+ind+'\"><div class=\"checkbox\"><label class=\"route-text\"><input type=\"checkbox\" class=\"route-checkbox\" name=\"'+modelName+'[applicable_code][]\" value=\"'+ind+'\" id=\"'+ind+'\"><label for=\"'+ind+'\">'+vl+'</label></label></div></div>');
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
                                            $('#dcs_code-list').append('<div class=\"col-sm-4 dcs-checklist checklist\" id=\"nd-'+index+'\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"route-checkbox\" name=\"{$cname}[dcs_code][]\" value=\"'+index+'\" id=\"'+index+'\"><label class=\"route-text\" for=\"'+index+'\">'+value+'</label></div></div>');
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

";
$this->registerJs($script, View::POS_END, 'village-code');
