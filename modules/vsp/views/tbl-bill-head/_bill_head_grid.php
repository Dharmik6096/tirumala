<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
?>
<div class=""></div>
<?php
$form = ActiveForm::begin([
            'id' => 'dcs-bill-head',
        ]);
?>
<div class=" no-effect table_form" >
    <div class="col-sm-12">
        <div class="table-responsive table-rate-chart">
            <table class="table table-bordered table-striped table-input table-hover" id="table">
                <?php
                if (!empty($head) && !empty($dcs)) {
                    $cnt = 0;
                    echo "<thead class='sticky_head'>";
                    foreach ($dcs as $key => $attr) {
                        if ($key == 0) {
                            echo " <tr>";
                            echo "<th rowspan='2'><input type = 'checkbox' class = 'selectAllRowsCheckbox'></th>";
                            echo "<th rowspan='2' class='width100px'>" . Yii::t('app', 'DCS') . "</th>";
                            foreach ($head as $s) {
                                echo "<th class='width1 00px'>" . $s->bill_head_name . "</th>";
                            }
//                            echo "<th class='width100px'>" . Yii::t('app', 'Action') . "</th>";
                            echo "</tr>";
                        }
                    }
                    foreach ($head as $h) {
                        echo "<th class='width100px'><input type = 'checkbox' class = 'selectAllHead'  id='{$h->bill_head_code}'></th>";
                    }
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($dcs as $key => $attr) {
                        $name = strtolower($model->customer_type) == 'dcs' ? $attr->dcs_name . '-' . $attr->ref_code : $attr->customer_name;
                        $code = strtolower($model->customer_type) == 'dcs' ? $attr->dcs_code : $attr->customer_code;
                        ?>
                        <tr class="bill_head_row_<?= $key ?>" data_key="<?= $key ?>">
                            <td><input type="checkbox" class="selectAllCheckboxRow" id="selectAllCheckbox-<?= $key ?>"></td>

                            <td><?= $name; ?></td>
                            <?php
                            $fromDate = $model->from_date;
                            $applicability = $model->getApplicabiliytData($model, $code, $fromDate);
                            $dispBtn = false;
                            $headCount = count($head);
                            $headAppCount = count($head);
                            foreach ($head as $s) {
                                if (in_array($s->bill_head_code, $applicability)) {
                                    echo "<td>" . 'Yes' . "</td>";
                                } else {
                                    $headAppCount--;
                                    $dispBtn = true;
                                    $class = $headAppCount == 0 ? ' highlightParentRow ' : '';
                                    ?>
                                    <td class="<?= $class ?>">
                                        <div class="">
                                            <?= Html::hiddenInput('dcs', $code, ['class' => 'bill_head_row_' . $key, 'id' => 'dcs']); ?>
                                            <?= Html::hiddenInput('type', $model->customer_type, ['class' => 'bill_head_row_' . $key, 'id' => 'type']); ?>
                                            <?= Html::hiddenInput('from_date', $model->from_date, ['class' => 'bill_head_row_' . $key, 'id' => 'from_date']); ?>
                                            <?= Html::hiddenInput('to_date', $model->to_date, ['class' => 'bill_head_row_' . $key, 'id' => 'to_date']); ?>
                                            <?= Html::hiddenInput('union', $model->union_code, ['class' => 'bill_head_row_' . $key, 'id' => 'union']); ?>
                                            <?= Html::hiddenInput('bmc', $model->bmc_code, ['class' => 'bill_head_row_' . $key, 'id' => 'bmc']); ?>
                                            <?= Html::hiddenInput('head_for', $model->bill_head_for, ['class' => 'bill_head_row_' . $key, 'id' => 'head_for']); ?>
                                            <input type="checkbox" class="billHeadCheckbox billHeadCheckbox-<?= $key ?> billhead-<?= $s->bill_head_code ?>" id="billheadcheck-<?= $s->bill_head_code . '_' . $code ?>" name="billhead_<?= $code ?>" value="<?= $s->bill_head_code ?>" head_key="<?= $s->bill_head_code ?>">
                                            <label for="billheadcheck-<?= $s->bill_head_code . '_' . $code ?>"></label>
                                        </div>
                                    </td>
                                    <?php
                                }
                            }
//                            if ($dispBtn) {
//                                echo "<td>" . '<i class="fa fa-edit" onClick="saveDataBillHead(' . $key . ')"></i>' . "</td>";
//                            } else {
//                                echo "<td></td>";
//                            }
                            ?>
                        </tr>
                        <?php
                    } echo "</tbody>";
                }
                ?>
            </table>
            <div class="panel-footer sticky_head">
                <?php
                if (!empty($head) && !empty($dcs)) {
                    echo Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'id' => 'saveApplicability']);
                    echo Yii::$app->controls->custombutton('Cancel', 'cs-wise-bill-head');
                }
                ?>
            </div>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

<?php
$script = "
//    function saveDataBillHead(setKey) {
//        let action_codes = [];
//        $('.bill_head_row_'+setKey+' .billHeadCheckbox').each(function () {
//            if ($(this).is(':checked')) {
//              action_codes.push($(this).val());
//            }
//        });
//        if(action_codes.length == 0){
//            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please Check At Least One Check Box.</span></div></div>');
//
//        }
//        let union = $('.bill_head_row_'+setKey+' #union').val();
//        let bmc = $('.bill_head_row_'+setKey+' #bmc').val();
//        let dcs = $('.bill_head_row_'+setKey+' #dcs').val();
//        let type = $('.bill_head_row_'+setKey+' #type').val();
//        let from_date = $('.bill_head_row_'+setKey+' #from_date').val();
//        let to_date = $('.bill_head_row_'+setKey+' #to_date').val();
//        let head_for = $('.bill_head_row_'+setKey+' #head_for').val();
//        
//        if(action_codes.length > 0){
//                $.ajax({
//                    type: 'post',
//                    url:'" . Url::to(['save-applicability']) . "',
//                    data: {'union_code':union, 'bill_head_codes':action_codes,'bmc_code':bmc,'applicable_code':dcs,'applicable_for':type,'wef_date':from_date,'from_date':from_date,'to_date':to_date,'bill_head_for':head_for},
//                    success: function(data) {                                        
//                        var obj = $.parseJSON(data);
//                        if (obj.status == 'success')
//                        {
//                            location.reload();
//    }
//                    },
//                    error:function(data){
//
//                    }
//                });
//            }
//    }
    customerType();
    $(document).on('change', '#tblbillhead-bmc_code', function(e) { 
        e.preventDefault();
        var bmc = $('#tblbillhead-bmc_code').val();
        if(setData(bmc)){
            customerType();
        }
    });
    $(document).on('change', '#tblbillhead-bill_head_for', function(e) { 
        e.preventDefault();
        customerType();
    });
    
    function customerType(){
        var billhead = $('#tblbillhead-bill_head_for').val();
        if(billhead =='MEMBER'){
//            $('#tblbillhead-customer_type').val('DCS');
//            $('.customertype').hide();
//            $('#tblbillhead-customer_type').trigger('select2:select');
//            $('#tblbillhead-customer_type').trigger('change');
            $('#tblbillhead-customer_type').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                $('#tblbillhead-customer_type').val('DCS');
                $('.customertype').hide();
                $('#tblbillhead-customer_type').trigger('select2:select');
                $('#tblbillhead-customer_type').trigger('change');
            });
        }else{
//            var length = $('#tblbillhead-customer_type option[value!=\'\']').length;
//            if(length == 0) {
//                $('#tblbillhead-customer_type').parent('div').parent().hide();
//            } else if(length == 1) {
//                $('#tblbillhead-customer_type').val('DCS');
//                $('#tblbillhead-customer_type').parent('div').parent().hide();
//                $('#tblbillhead-customer_type').trigger('select2:select');
//                $('#tblbillhead-customer_type').trigger('change');
//            } else {
//                $('#tblbillhead-customer_type').parent('div').parent().show();               
//            }
            $('#tblbillhead-customer_type').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                
                length = $('#tblbillhead-customer_type option[value!=\'\']').length;
                if(length == 0) {
                    $('#tblbillhead-customer_type').parent('div').parent().hide();
                } else if(length == 1) {
                    $('#tblbillhead-customer_type').val('DCS');
                    $('#tblbillhead-customer_type').parent('div').parent().hide();
                    $('#tblbillhead-customer_type').trigger('select2:select');
                    $('#tblbillhead-customer_type').trigger('change');
                } else {
                    $('#tblbillhead-customer_type').parent('div').parent().show();               
                }
            });
        }
    }
    
    function setData(field = ''){
        if(field != '' && field != null && field != undefined){
            return true;
        }else {
            return false;
        }
    }
    
    $(document).ready(function () {
        $('.highlightParentRow').closest('tr').addClass('highlightRow');
    });
    
    $(document).on('click', '.selectAllCheckboxRow', function(e) { 
        var dataKey= $(this).closest('tr').attr('data_key');
        if($(this).is(':checked')){
            $('.billHeadCheckbox-'+dataKey).prop('checked', true);
        } else {
            $('.billHeadCheckbox-'+dataKey).prop('checked', false);
        }
    });
    
    $(document).on('click', '.selectAllRowsCheckbox', function(e) { 
        if($(this).is(':checked')){
            $('.billHeadCheckbox').prop('checked', true);
        } else {
            $('.billHeadCheckbox').prop('checked', false);
        }
    });
    
    $(document).on('click', '.selectAllHead', function(e) { 
        var dataKey= $(this).attr('id');
        if($(this).is(':checked')){
            $('.billhead-'+dataKey).prop('checked', true);
        } else {
            $('.billhead-'+dataKey).prop('checked', false);
        }
    });
    
    $(document).on('click', '#saveApplicability', function(e) {
        saveDataBillHeadApplicability();
    });
    
    function saveDataBillHeadApplicability() {
        var action_codes = {};
        var trKeys = [];
        var isformSubmit=0;
           $('.billHeadCheckbox').each(function() {
            var set_key = $(this).closest('tr').attr('data_key');
            var dcs = $('.bill_head_row_'+set_key+' #dcs').val();
            if (!trKeys.includes(dcs)) {
               action_codes[dcs]=[];
               trKeys.push(dcs);
            }
                if ($(this).is(':checked')) {
                    isformSubmit=1;
                    action_codes[dcs].push($(this).val());
                }
          
        });

        let union = $('#union').val();
        let bmc = $('#bmc').val();
        let dcs = $('#dcs').val();
        let type = $('#type').val();
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let head_for = $('#head_for').val();

         saveDatas(action_codes,union,bmc,dcs,type,from_date,to_date,head_for,isformSubmit);

    }
    
    function saveDatas(action_codes,union,bmc,dcs,type,from_date,to_date,head_for,isformSubmit) {

       if (isformSubmit==0) {
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please Check At Least One Check Box.</span></div></div>');
            return;
        }

        if (isformSubmit==1) {
            $.ajax({
                type: 'post',
                url:'" . Url::to(['save-applicability']) . "',
                data: {'union_code':union, 'bill_head_codes':action_codes,'bmc_code':bmc,'applicable_for':type,'wef_date':from_date,'from_date':from_date,'to_date':to_date,'bill_head_for':head_for},
                success: function (data) {
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        location.reload();
                    }
                },
                error: function (data) {
                }
            });
        }
    }
    
  $(document).on('click', '.billHeadCheckbox', function() {
        var setKey = $(this).closest('tr').attr('data_key');
        var headKey = $(this).attr('head_key');
        var allChecked = true;
        var allColumnChecked = true;
        $('.selectAllRowsCheckbox').prop('checked', false);
        $('.billHeadCheckbox-'+setKey).each(function() {
            if (!$(this).is(':checked')) {
                allChecked = false;
            }
        });
        
        $('.billhead-'+headKey).each(function() {
            if (!$(this).is(':checked')) {
                allColumnChecked = false;
            }
        });
     
        if (allChecked) {
            $('#selectAllCheckbox-'+setKey).prop('checked', true);
        } else {
            $('#selectAllCheckbox-'+setKey).prop('checked', false);
        }
        if (allColumnChecked) {
            $('#'+headKey).prop('checked', true);
        } else {
            $('#'+headKey).prop('checked', false);
        }
        if(allChecked && allColumnChecked){
            $('.selectAllRowsCheckbox').prop('checked', true);
        }
      });  
      
    $(document).on('click', '.selectAllCheckboxRow', function() {
        var allMainChecked = true;
        $('.selectAllCheckboxRow').each(function() {
            if (!$(this).is(':checked')) {
                allMainChecked = false;
                return false; 
            }
        });
        
        if (allMainChecked) {
            $('.selectAllRowsCheckbox').prop('checked', true);
            $('.selectAllHead').prop('checked', true);
        } else {
            $('.selectAllRowsCheckbox').prop('checked', false);
            $('.selectAllHead').prop('checked', false);
        }
    });
    
    $(document).on('click', '.selectAllRowsCheckbox', function() {
        if ($(this).is(':checked')) {
            $('.selectAllCheckboxRow').prop('checked', true);
            $('.selectAllHead').prop('checked', true);
        } else {
            $('.selectAllCheckboxRow').prop('checked', false);
            $('.selectAllHead').prop('checked', false);
        }
    });
    
    $(document).on('click', '.selectAllHead', function() {
        var allChecked = true;
        $('.selectAllHead').each(function() {
            if (!$(this).is(':checked')) {
                allChecked = false;
                return false; 
            }
        });
        
        if (allChecked) {
            $('.selectAllCheckboxRow').prop('checked', true);
            $('.selectAllRowsCheckbox').prop('checked', true);
        } else {
            $('.selectAllCheckboxRow').prop('checked', false);
            $('.selectAllRowsCheckbox').prop('checked', false);
        }
    });
      ";
$this->registerJs($script, View::POS_END, 'bill-head-grid');
