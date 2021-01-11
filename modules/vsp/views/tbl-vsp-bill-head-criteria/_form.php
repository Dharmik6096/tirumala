<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use kartik\grid\GridView;
?>
<div id="maincontent">
    <?=
    $this->render('_criteria', ['model' => $model, 'type' => $type, 'txModel' => $txModel])
    ?>
</div>

<div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
    <div class="QltyParamDivGrid">
        <?=
        $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
        ?>
    </div>
</div>

<?php
$script = "
    var action = '" . $type . "';
    gridChange();
        if(action == 'edit'){
            reloadGrid();
            $('.DisableAferAdd').addClass('disabledDiv');                                                                  
            $('.QltyParamDiv').show();
            $('#tblvspbillheadcriteriaslabs-from_val').focus();
        }
    $(document).on('change', '#tblvspbillheadcriteria-criteria_name', function() {  
        gridChange();
    });
    $(document).on('change', '#tblvspbillheadcriteria-bill_head_code', function() {  
       gridChange();
    });
    $(document).on('change', '#tblvspbillheadcriteria-general_formula_code', function() { 
         gridChange();
            var formulaCode = $('#tblvspbillheadcriteria-general_formula_code').val();
            if(formulaCode !=''){
                $('#tblvspbillheadcriteriaslabs-general_formula_code').val(formulaCode);
                $('#tblvspbillheadcriteriaslabs-general_formula_code').trigger('change');
                $('#tblvspbillheadcriteriaslabs-general_formula_code').trigger('select2:select');
                $('#tblvspbillheadcriteriaslabs-general_formula_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    $('#tblvspbillheadcriteriaslabs-general_formula_code').val(formulaCode);
                    $('#tblvspbillheadcriteriaslabs-general_formula_code').trigger('change');
                    $('#tblvspbillheadcriteriaslabs-general_formula_code').trigger('select2:select');
                });
            }
    });
    
    function gridChange(){
       $('.add-criteria').prop('disabled',true);
       $('#bill-head-criteria-from .reset_field input').val('');
        if(action != 'edit'){
            $('.QltyParamDiv').hide();
        }
        var name = $('#tblvspbillheadcriteria-criteria_name').val();
        var head = $('#tblvspbillheadcriteria-bill_head_code').val();
        var formula = $('#tblvspbillheadcriteria-general_formula_code').val();
        if(setData(name) && setData(head) && setData(formula)){
            if(action != 'edit'){
                 $('.add-criteria').removeAttr('disabled');
            }
        } 
        
    }
    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }

    $(document).on('click','.add-criteria',function(e){
        reloadGrid();
        $('.QltyParamDiv').show();
    });
    function reloadGrid(){
         var code = $('#tblvspbillheadcriteria-vsp_criteria_code').val();
         if(code !=''){
         getFromVal();
            var url = '" . Url::to(['/vsp/tbl-vsp-bill-head-criteria/list-grid']) . "'+ '?' + $('#bill-head-criteria-from').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
//                        $('#loadercontent').show();
//                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet .QltyParamDivGrid').html(data);
                        $('#tblvspbillheadcriteria-from_val').focus();
//                        $('#loadercontent').hide();
//                        $('#pageloader').hide();
                    },
                });
        }        
    }
  
    var formula_value='';
        var formula='';
        function removeError(){
            $('#div_formula').removeClass('has-error');
            $('#error_message').remove();
        }
        function addError(message){
            $('#div_formula').addClass('has-error');
            $('#replace_value').after('<p id=\"error_message\" class=\"help-block help-block-error\">'+message+'</p>');
        }

         $(document).on('click','.add-formula',function(e){
            $('#tblvspbillheadcriteriaslabs-formula_with_val').val('');

            var formulacode = $('#tblvspbillheadcriteria-general_formula_code').val();
             if(formulacode !=''){
             var str=$('#tblvspbillheadcriteria-general_formula_code').find(\"option:selected\").text();
             formula=str;
             $.ajax({
            type: 'post',
            url: '" . Url::to(['/vsp/tbl-vsp-bill-head-criteria/keyword']) . "',                    
            success: function(data) {
                var type = $.parseJSON(data).join('|')+'|-|\\\+|\\\*|/|\\\[|\\\(|\\\)';//              
                var re = new RegExp(type,\"g\");
                formula_value=formula.replace(re,'').replace(/\]/g,',').slice(0,-1);
                $('#subtitle').empty().append(\"<span>Add Coma(,) Seperated Value for [\"+formula_value+\"]</span>\");
                $('#replace_value').val('');
                $('#apply-formula').modal('toggle');
            },
        });
            }
         });
         
        $('#formula-submit').on('click',function(e){
            if($('#replace_value').val()==''){
                removeError();
                addError('Value can not be blank');
            }else{
                var tmp1=formula_value.split(',');
                var tmp2=$('#replace_value').val().split(','); 
                if(tmp1.length==tmp2.length){    
                     $.each(tmp1, function (key, val) {
                        formula=formula.replace('['+val+']',tmp2[key]);
                     });
                    removeError(); 
                    $('#tblvspbillheadcriteriaslabs-formula_with_val').val(formula);
//                    $('#general_formula').val(formula);
                    $('#apply-formula').modal('toggle');
                }else{
                    removeError();
                    addError('Value is not in proper Format');
                }
            }
         });
         


    $(document).on('click','.delete-slab',function(e){
    var from= $(this).attr('data-from');
    var to= $(this).attr('data-to');
    var id= $(this).attr('data-id');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to delete Slab From \"'+from+'\" - \"'+to+'\"?</span></div></div>',
        buttons: {
            'cancel': {
                            label: 'Cancel',
                            className: 'btn btn-danger'
              },
            'confirm': {
                            label: 'Ok',
                            className: 'btn btn-primary'
             }
        },
        callback: function(result) {
            if (result) {
              $('#loader').show();
                 $.ajax({
                        type: 'get',
                        url: '" . Url::to(['delete-slab']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                              reloadGrid();
//                                $.pjax.reload({container: '#member-grid'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
            }
        }
    });
    });
    $(document).ready(function() {
        getFromVal();
    });
    function getFromVal(){
        $('#tblvspbillheadcriteriaslabs-from_val').val('0');
         var code = $('#tblvspbillheadcriteria-vsp_criteria_code').val();
            if(code !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['get-from-value']) . "',
                    data: {'id':code},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                         $('#tblvspbillheadcriteriaslabs-from_val').val(obj.toFixed(2));
                        if (obj.status == 'success')
                        {
                           
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };
   
    
";
$this->registerJs($script, View::POS_END, 'panel-before-hide-form');
?>