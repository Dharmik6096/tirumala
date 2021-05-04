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
            <table class="table table-bordered table-striped table-input" id="table">
                <?php
                if (!empty($head) && !empty($dcs)) {
                    $cnt = 0;
                    echo "<thead>";
                    foreach ($dcs as $key => $attr) {
                        if ($key == 0) {
                            echo "<tr><th class='width100px'>" . '' . "</th>";
                            foreach ($head as $s) {
                                echo "<th class='width100px'>" . $s->bill_head_name . "</th>";
                            }
                            echo "<th class='width100px'>" . Yii::t('app', 'Action') . "</th>";
                            echo "</tr>";
                        }
                    }
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($dcs as $key => $attr) {
                        $name = strtolower($model->customer_type) == 'dcs' ? $attr->dcs_name : $attr->customer_name;
                        $code = strtolower($model->customer_type) == 'dcs' ? $attr->dcs_code : $attr->customer_code;
                        ?>
                        <tr class="bill_head_row_<?= $key ?>">
                            <td><?= $name; ?></td>
                            <?php
                            $fromDate = Yii::$app->general->getforeignkey($model->paymentCycle, 'from_date');
                            $applicability = $model->getApplicabiliytData($model, $code, $fromDate);
                            $dispBtn = false;
                            foreach ($head as $s) {
                                if (in_array($s->bill_head_code, $applicability)) {
                                    echo "<td>" . 'Yes' . "</td>";
                                } else {
                                    $dispBtn = true;
                                    ?>
                                    <td>
                                        <div class="">
                                            <?= Html::hiddenInput('dcs', $code, ['class' => 'bill_head_row_' . $key, 'id' => 'dcs']); ?>
                                            <?= Html::hiddenInput('type', $model->customer_type, ['class' => 'bill_head_row_' . $key, 'id' => 'type']); ?>
                                            <?= Html::hiddenInput('date', $fromDate, ['class' => 'bill_head_row_' . $key, 'id' => 'date']); ?>
                                            <?= Html::hiddenInput('union', $model->union_code, ['class' => 'bill_head_row_' . $key, 'id' => 'union']); ?>
                                            <?= Html::hiddenInput('bmc', $model->bmc_code, ['class' => 'bill_head_row_' . $key, 'id' => 'bmc']); ?>
                                            <?= Html::hiddenInput('head_for', $model->bill_head_for, ['class' => 'bill_head_row_' . $key, 'id' => 'head_for']); ?>
                                            <input type="checkbox" class="billHeadCheckbox" id="billheadcheck-<?= $s->bill_head_code . '_' . $code ?>" name="billhead_<?= $code ?>" value="<?= $s->bill_head_code ?>">
                                            <label for="billheadcheck-<?= $s->bill_head_code . '_' . $code ?>"></label>
                                        </div>
                                    </td>
                                    <?php
                                }
                            }
                            if ($dispBtn) {
                                echo "<td>" . '<i class="fa fa-edit" onClick="saveDataBillHead(' . $key . ')"></i>' . "</td>";
                            } else {
                                echo "<td></td>";
                            }
                            ?>
                        </tr>
                        <?php
                    } echo "</tbody>";
                }
                ?>
            </table>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

<?php
$script = "
    function saveDataBillHead(setKey) {
        let action_codes = [];
        $('.bill_head_row_'+setKey+' .billHeadCheckbox').each(function () {
            if ($(this).is(':checked')) {
              action_codes.push($(this).val());
            }
        });
        if(action_codes.length == 0){
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please Check At Least One Check Box.</span></div></div>');

        }
        let union = $('.bill_head_row_'+setKey+' #union').val();
        let bmc = $('.bill_head_row_'+setKey+' #bmc').val();
        let dcs = $('.bill_head_row_'+setKey+' #dcs').val();
        let type = $('.bill_head_row_'+setKey+' #type').val();
        let date = $('.bill_head_row_'+setKey+' #date').val();
        let head_for = $('.bill_head_row_'+setKey+' #head_for').val();

        if(action_codes.length > 0){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['save-applicability']) . "',
                    data: {'union_code':union, 'bill_head_codes':action_codes,'bmc_code':bmc,'applicable_code':dcs,'applicable_for':type,'wef_date':date,'bill_head_for':head_for},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            location.reload();
                        }
                    },
                    error:function(data){

                    }
                });
            }
    }
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
      ";
$this->registerJs($script, View::POS_END, 'bill-head-grid');
