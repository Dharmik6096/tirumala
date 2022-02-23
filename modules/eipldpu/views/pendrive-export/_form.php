<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['/eipldpu/pendrive-export/load-society']);
$rate_url = Url::to(['/eipldpu/pendrive-export/view-rate']);
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="row">   
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>    
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbleiplmasterfilelog-union_code', 'plant_code', true); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbleiplmasterfilelog-plant_code', 'mcc_plant_code', true); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbleiplmasterfilelog-mcc_plant_code', 'bmc_code', true); ?>
    </div>
    <?= Yii::$app->dropdown->dropdownStatic('process_type', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', $model->getAttributeLabel('file_type'), FALSE, 'file_type') ?> 
    <div class="col-sm-2 singledcs">
        <?= Yii::$app->dropdown->depend_dropdown('bmc-dcs', $model, $form, 'tbleiplmasterfilelog-bmc_code', '', $model->getAttributeLabel('dcs_code') . ' *', 'dcs_code', FALSE, 1, explode(',', Yii::$app->session->get('Dcs')), false); ?>
    </div>
    <div id='dputype'>
        <?= Yii::$app->dropdown->dropdownStatic('EIPL_dpu_type', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', $model->getAttributeLabel('dpu_type'), FALSE, 'dpu_type') ?> 
    </div>
    <div class='ratedigit'>
        <?= Yii::$app->dropdown->dropdownStatic('rate_digit', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', $model->getAttributeLabel('rate_digit') . ' *', FALSE, 'rate_digit') ?> 
    </div>
    <div class="col-sm-2 singledcs">
        <div class="col-sm-8"> 
            <?= Yii::$app->dropdown->DpuRateChart($model, $form, 'tbleiplmasterfilelog-dcs_code', 'rate_id', $model->getAttributeLabel('rate_id') . ' *'); ?>
        </div>
        <div class="col-sm-1 mt20"> 
            <?= Html::a(Yii::t('app', 'View'), '#', ['class' => 'view-rate btn btn-danger']); ?>
        </div>

    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_encrypted', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="clearfix"></div>

    <div class="multidcs col-sm-12 padding-left-0 padding-right-0 applicableCodeArea">
        <h4 class="theme-box-heading padding_left_0 padding_right_0"><?= Yii::t('app', 'DCS') ?> List</h4>
        <div class="app-check-list mx_h_400">
            <div class="form-group">
                <div class="checkbox app-check-all app-check-list-padding">
                    <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => 'dcs_code_multi', 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
                    <label class="route-text">
                        <?= Html::checkbox('checkall', false, ['id' => 'checkAll', 'class' => 'route-checkbox']) ?>
                        <label for="checkAll">Check All <?= Yii::t('app', 'DCS') ?></label>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="dcs-wrap" class=" app-check-list-padding">
                <?=
                $this->render('/../../applicability/views/default/_checkbox_list', [
                    'model' => $model, 'form' => $form, 'field_name' => 'dcs_code_multi',
                    'list' => [], 'selected' => [], 'selectedData' => [], 'checkboxClass' => 'col-sm-4',
                    'checkboxClass' => ' flt-checkboxa dcsCheckboxes'
                ])
                ?>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::t('app', 'Download'), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>



<?php
$script = "
     $('.singledcs').hide();
     $('.multidcs').hide();
     $('.ratedigit').hide();
     $('.view-rate').addClass('disabled');

     $('#tbleiplmasterfilelog-dpu_type').find('option[value=0]').remove();

    $('#tbleiplmasterfilelog-bmc_code,#tbleiplmasterfilelog-dcs_code,#tbleiplmasterfilelog-file_type,#tbleiplmasterfilelog-dpu_type').on('change',function(){
        var bmc_code  = $('#tbleiplmasterfilelog-bmc_code').val();
        var file_type = $('#tbleiplmasterfilelog-file_type').val();
        var dpu_type  = $('#tbleiplmasterfilelog-dpu_type').val(); 
        var dcs_code  = $('#tbleiplmasterfilelog-dcs_code').val();

    if(bmc_code !='' && bmc_code !=null && file_type !='' && file_type !=null)
    {
       if(file_type=='MEMBER')
       {
        $('.singledcs').hide();
        $('.multidcs').show();
        $('.ratedigit').hide();

       
        if(dpu_type !='' && dpu_type !=null)
        {
            $('#dcs_code_multi-list').empty();
            addSociety(bmc_code,dpu_type,file_type);
        } 
       }                 
       if(file_type=='RATE')
       {
        $('.singledcs').show();
        $('.multidcs').hide();
        $('.ratedigit').hide();

        if(dcs_code !='' && dcs_code !=null)
        {
                    addSociety(dcs_code,dpu_type,file_type);
        }
       }
    }else{
     $('.singledcs').hide();
     $('.multidcs').hide();
     $('.ratedigit').hide();
    }

    });
  function addSociety(bmc_code,dpu_type,file_type)
    {
        $.ajax({
                        type: 'post',
                        url: '{$url}',
                        data: {'bmc_code':bmc_code,'dpu_type':dpu_type,'file_type':file_type},
                        beforeSend : function(data){
                                            $('#loadercontent').show();
                                            $('#pageloader').show();
                                },
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                if(file_type=='MEMBER')
                                {
                                    $.each(obj1.data, function(index, value) {
                                            $('#dcs_code_multi-list').append('<div class=\"col-sm-3 dcs-checklist checklist\" id=\"nd-'+index+'\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"route-checkbox\" name=\"TblEiplMasterFileLog[dcs_code_multi][]\" value=\"'+index+'\" id=\"'+index+'\"><label class=\"route-text\" for=\"'+index+'\">'+value+'</label></div></div>');
                                        });                                    
                                }else{
                                    $('#tbleiplmasterfilelog-dpu_type').val(obj1.data).trigger('change.select2');
                                    if(obj1.data==32){
                                         $('.ratedigit').show();
                                    }
                                  }
                            }
                                            $('#loadercontent').hide();
                                            $('#pageloader').hide();
                        },
                        error:function(data){
                                            $('#loadercontent').hide();
                                            $('#pageloader').hide();
                                }
            });  
    }
    $('#checkAll').click(function (event) {
        event.stopPropagation();
        var chk=$(this);
        var checked=[];
        $('.route-checkbox').not('.flt-checkbox').not(':disabled').prop('checked', this.checked);
        if($(this).is(':checked'))
        {
            $('.route-checkbox').not('.flt-checkbox').not('#checkAll').not(':disabled').each(function(){
                checked.push($(this).val());
            });
        }
    });
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
 $(document).on('click', '.dcs-checklist',function(event){
      event.stopPropagation();
      $('#checkAll').prop('checked', false);
   });
    
 $('#tbleiplmasterfilelog-rate_id').on('change',function(){
        var rate_id  = $('#tbleiplmasterfilelog-rate_id').val();
        if(rate_id !='' && rate_id !=null)
        {
         $('.view-rate').removeClass('disabled');
        } else {
        $('.view-rate').addClass('disabled');
        }
    }); 
 $(document).on('click', '.view-rate',function(event){
             var rate_id  = $('#tbleiplmasterfilelog-rate_id').val();
             $.ajax({
                        type: 'post',
                        url: '{$rate_url}',
                        data: {'rate_id':rate_id},  
                        success: function(data) {
                            var obj = $.parseJSON(data);
                                window.open(obj.data, '_blank');
                         }                     
            });  
   });
       
";
$this->registerJs($script, View::POS_END, 'eipl-master-export');

