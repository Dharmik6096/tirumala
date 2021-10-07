<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Member Payment Process : Step 3');
$fromDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($aliasModel->paymentCycleCode, 'from_date'));
$toDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($aliasModel->paymentCycleCode, 'to_date'));

$bmc_info = Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_code') . ' > ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' > ' .
        $fromDate . ' to ' . $toDate;
$message = Yii::t('app', 'Payment data of  all society will be locked and considered as final for ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
$config = (isset(Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['recovery_from_other_member']) && Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['recovery_from_other_member'] == 1) ? TRUE : FALSE;

$urlForList = ['member-payment-adjust-list', 'union_code' => $model->union_code, 'payment_cycle_code' => $model->payment_cycle_code, 'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code];
?>
<?php
//$array = $dataProvider->getModels();
//$tot_amt = array_sum(array_map(function($array) {
//            return $array['final_amount'];
//        }, $array));
?>

<div id='member_payment_view'>
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-body">      
            <div class="panel-heading">
                <?= $this->title . ' (' . $bmc_info . ')' ?>   
                <div id="total-payment">
                    Total Payable :: <?= '0'; ?>
                </div>
            </div>    
        </div>
    </div>
</div>
<!--<div id='bill_head_view'></div>-->
<?php // ActiveForm::end(); ?>
<div id='bill_head_view'></div>
<div id='member_installment'></div>
<div id="recoverOtherMember"></div>

<?php
$script = '
    $(document).on("click", "#adjust", function(){
//    $("#adjust").click(function() {
    $(".process_lock_flag").val("Process");
     var negativeVal = "No";
    $(".final-amount").each(function() {
        var parent = $(this).parents("tr");
        var final = parseFloat(parent.find(".final-amount").text());
        var netPay = parseFloat(parent.find(".net-amount").val());
        if(final == "" ||  isNaN(final)){
            final=0;
        }
        if(final < 0){
            if((!isNaN(netPay) && netPay < 0)) {
                negativeVal = "Yes";
            }
        }
    });
    var message = "' . $message . '";
    var negativeCount = ' . $negativeValCount . ';
    if(negativeCount > 0 || negativeVal == "Yes") {
        var dispMessage = "' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispMessage+"</span>");
    } else {
        var totalRec=0;
        var totaladjRec=0;
        $(".adjust-recovery").each(function() {
            var parent = $(this).parents("tr");
            var adjustRec = parseFloat(parent.find(".adjust-recovery").val());
            var recovery = parseFloat(parent.find(".recovery").val());
                if(adjustRec == "" ||  isNaN(adjustRec)){
                    adjustRec=0;
                }
                if(recovery == "" ||  isNaN(recovery)){
                    recovery=0;
                }
            totalRec=totalRec+recovery;
            totaladjRec=totaladjRec+adjustRec;
        });
            if(totalRec != totaladjRec) {
                var dispmessage = "' . Yii::t('app', 'Sum of Adjust Recovery and Sum of Reovery Must be Same.') . '";
                bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispmessage+"</span>");
            } else {
                 $("#payment-adjust").submit();
            }
    }
});
$(document).on("click", "#adjust-lock", function(){
//$("#adjust-lock").click(function() {
    $(".process_lock_flag").val("Lock");
    
    var negativeVal = "No";
    $(".final-amount").each(function() {
        var parent = $(this).parents("tr");
        var final = parseFloat(parent.find(".final-amount").text());
        var netPay = parseFloat(parent.find(".net-amount").val());
        if(final == "" ||  isNaN(final)){
            final=0;
        }
        if(final < 0){
            if(netPay == "" || (!isNaN(netPay) && netPay < 0)) {
                negativeVal = "Yes";
            }
        }
    });
    var message = "' . $message . '";
    var negativeCount = ' . $negativeValCount . ';
    if(negativeCount > 0 || negativeVal == "Yes") {
        var dispMessage = "' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispMessage+"</span>");
    } else {
        bootbox.confirm({
            message: "<div class=\"bg-danger\"><i class=\"fa fa-question-circle\"></i></div><span>"+message+"</span>",
            buttons: {
                confirm: {
                    label: "' . Yii::t('app', 'Yes') . '",
                    className: "btn-primary"
                },
                cancel: {
                    label: "' . Yii::t('app', 'No') . '",
                    className: "btn-danger"
                }
            },
            callback: function (result) {
                if(result){
                    $("#payment-adjust").submit();
                }
            }
        });
    
    }
});
';
$script .= " 

    $(window).on('load', function () {
        setTimeout(function(){
//            $('#loadercontent').show();
//            $('#pageloader').show(); 
        }, 2500);
    });
    $(document).on('ready', function () {       
//    $(document).ready(function () {              
//        setTimeout(function(){
//            $('#loadercontent').show();
//            $('#pageloader').show(); 
            $.ajax({
                type: 'get',
                url: '" . Url::to($urlForList) . "',
                data: {},
                beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                },
                success: function(data) {            
                    $('#loadercontent').hide();
                    $('#pageloader').hide();  
                    $('#member_payment_view').html(data);
                    $('.hold-amount').each(function(){
                        var id = $(this).attr('id');
                        var parent = $(this).parents('tr');
                        var val = id.split('-');
                        var row_number = val[2];
                         calculte(row_number,parent);
                    }); 
    //                $('#BillHeadModal').modal('toggle');              
                    $('#loadercontent').hide();
                    $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });              
//        },1000);
    });
$(document).on('click','.view-head',function(e){
    var payment_cycle_code= $(this).attr('data-payment_cycle_code');
    var bmc_code= $(this).attr('data-bmc_code');
    var dcs_code= $(this).attr('data-dcs_code');
    var member_code= $(this).attr('data-member_code');
    ViewBillHead(payment_cycle_code, bmc_code, dcs_code, member_code);
});

function ViewBillHead(payment_cycle_code, bmc_code, dcs_code, member_code){
    if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-member-payment/member-bill-head']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code, 'member_code': member_code},
            beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
            },
            success: function(data) {
                $('#bill_head_view').html(data);
                $('#BillHeadModal').modal('toggle');              
                $('#loadercontent').hide();
                $('#pageloader').hide();                                                                  
            },
            error: function(data) {  
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    }
}

    function calculte(row_number,parent){
        var adjust = parseFloat($('#tblmemberpaymentalias-adjust_amount-'+row_number).val());
         var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat($('#tblmemberpaymentalias-hold_amount-'+row_number).val());
        var adjustRec = parseFloat($('#tblmemberpaymentalias-adjust_recovery-'+row_number).val());
        var rec = parseFloat($('#tblmemberpaymentalias-recovery-'+row_number).val());
         $('#tblmemberpaymentalias-final_amount-'+row_number).val('')
        if(adjust == '' ||  isNaN(adjust)){
            adjust=0;
        }
        if(hold == '' ||  isNaN(hold)){
            hold=0;
        }
        if(adjustRec == '' ||  isNaN(adjustRec)){
            adjustRec=0;
        }
        if(rec == '' ||  isNaN(rec)){
            rec=0;
        }
        var net = final + adjust - hold + adjustRec - rec; 
        if(net != '' &&  !isNaN(net)){
            $('#tblmemberpaymentalias-final_amount-'+row_number).val(net.toFixed(2));
            SumAmount();
        }
       
    }

    
    $(document).on('blur','.cal-amount',function(e){
//    $('.cal-amount').on('blur',function(){
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var adjust = parseFloat(parent.find('.adjust-amount').val());
        var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat(parent.find('.hold-amount').val());
         var val = id.split('-');
         var row_number = val[2];
        var adjustRec = parseFloat(parent.find('.adjust-recovery').val());
        var rec = parseFloat(parent.find('.recovery').val());
        parent.find('.net-amount').val('');
        if(adjust == '' ||  isNaN(adjust)){
            adjust=0;
        }
        if(hold == '' ||  isNaN(hold)){
            hold=0;
        }
        if(adjustRec == '' ||  isNaN(adjustRec)){
            adjustRec=0;
        }
        if(rec == '' ||  isNaN(rec)){
            rec=0;
        }
        var net = final + adjust - hold + adjustRec - rec; 
        if((adjust !=0  || hold !=0) && net != '' && net < 0){
         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Net Payable should not be less than final amount.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
            });
            return false;
        } else {              
        if(net != '' &&  !isNaN(net)){
         parent.find('.net-amount').val(net.toFixed(2));
          SumAmount();
        }
       }
    });




    $(document).on('blur','.adjust-amount',function(e){
//$('.adjust-amount').on('blur',function(){     
        var adjust = parseFloat($(this).val());
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var final = parseFloat(parent.find('.final-amount').text());
         parent.find('.net-amount').val('');
        var net = final + adjust ;  
        if(net != '' && net < 0){
         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Adjust Amount should not be less than final amount.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
            });
            return false;
        } else {              
        if(net != '' &&  !isNaN(net)){
         parent.find('.net-amount').val(net.toFixed(2));
          SumAmount();
        }
       }
    });
    
    $(document).on('blur','.adjust-recovery',function(e){
//    $('.adjust-recovery').on('blur',function(){     
        var adjustRecovery = parseFloat($(this).val());
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var net = parseFloat(parent.find('.net-amount').val());
        var dcs = parent.find('.dcs').val();
        var plant = parent.find('.plant').val();
        var mcc = parent.find('.mcc').val();
        var bmc = parent.find('.bmc').val();
        var payment_cycle_code = parent.find('.payment_cycle').val();
        var member = parent.find('.member').val();
        var alis_code = parent.find('.alis_code').val();
        if(adjustRecovery !='' && !isNaN(adjustRecovery)){
             $.ajax({
                type: 'get',
                url:'" . Url::to(['/payment/tbl-member-payment/validate-total-recovery']) . "',
                    data:{'member_payment_alias_code':alis_code,'plant_code':plant,'mcc_plant_code':mcc,'bmc_code':bmc,'payment_cycle_code':payment_cycle_code,'dcs_code':dcs,'member_code':member,'adjust_recovery':adjustRecovery},
                     success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                        $.ajax({
                        type: 'get',
                        url: '" . Url::to(['/payment/tbl-member-payment/recovery-adjust']) . "',
                        data:{'member_payment_alias_code':alis_code,'plant_code':plant,'mcc_plant_code':mcc,'bmc_code':bmc,'payment_cycle_code':payment_cycle_code,'dcs_code':dcs,'member_code':member,'adjust_recovery':adjustRecovery},
                            success: function(data) {     
                                $('#recoverOtherMember').html(data);
                                $('#recoverOtherMemberModal').modal('toggle');    
                                $('#loadercontent').hide();
                                $('#pageloader').hide();
                            },    
                            error: function(data) {    
                                $('#loadercontent').hide();
                                $('#pageloader').hide();
                            }
                        });
                      }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Adjust Recovery Must be '+obj.recovery+' </span></div></div>');
                            parent.find('.adjust-recovery').val(obj.old_recovery);
                        }
                },
                error:function(data){

                }
            });
          
        } else{
//            parent.find('.adjust-recovery').val('');
        }
    });

";
$script .= " 
    $(document).on('change','.new_recovery input', function() { 
        var trClass = $(this).closest('tr').attr('class');
        claculateNetPay(trClass);
    });
    
    function claculateNetPay(trClass){
        var recovery = parseFloat($('#tblmemberpaymentalias-'+trClass+'-recovery').val());
        var net = parseFloat($('#tblmemberpaymentalias-'+trClass+'-final_amount').val());
        var oldRec = parseFloat($('#tblmemberpaymentalias-'+trClass+'-old_recovery').val());
        
        if(recovery == '' || isNaN(recovery)){
            recovery = 0;
        }
        if(oldRec == '' || isNaN(oldRec)){
            oldRec = 0;
        }
        var netPay = oldRec + recovery;
        if(netPay > net){
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Recovery Not More Than Net Pay.</span></div></div>');
                $('#tblmemberpaymentalias-'+trClass+'-recovery').val('');
//                setTimeout(function(){
//                    $('#tblmemberpaymentalias-'+trClass+'-recovery').focus();
//                },100);
        }else{
//            var newNetPay= net-netPay;
//            setTimeout(function(){
//                $('#tblmemberpaymentalias-'+trClass+'-final_amount.final_amount').val(newNetPay.toFixed(2)); 
//            }, 500);
        }
    } 

    $(document).on('click','.memberinstallments',function(e){
        var trClass = $(this).closest('tr').attr('class');
        var payment_cycle_code= $(this).attr('data-payment_cycle_code');
        var bmc_code= $(this).attr('data-bmc_code');
        var dcs_code= $(this).attr('data-dcs_code');
        var member_code= $(this).attr('data-member_code');
        

        var parent = $(this).parents('tr');
        var netPay = parseFloat(parent.find('.net-amount').val());
            MemberInstallment(payment_cycle_code, bmc_code, dcs_code, member_code, netPay);
    });

    function MemberInstallment(payment_cycle_code, bmc_code, dcs_code, member_code, netPay){
        if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/payment/tbl-member-payment/member-installment']) . "',
                data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code, 'member_code': member_code, 'netPay': netPay},
                beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                },
                success: function(data) {
                    $('#member_installment').html(data);
                    $('#MemberInstallmentModal').modal('toggle');              
                    $('#loadercontent').hide();
                    $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        }
    }
 ";

$this->registerJs($script, View::POS_END, 'payment-adjust-script');
?>
<?php
$script = "$('.kv-panel-before').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>