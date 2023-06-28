<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\jui\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>


<?php
$form = ActiveForm::begin(['options' => [
                'class' => 'staff_install_final',
                'field-class' => 'form-group col-sm-3',
            ],
            'validateOnBlur' => TRUE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<div class="panel-subheading">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-main table-language">
                <thead>

                <th><?php echo $model->getAttributeLabel('installment_no') ?></th>
                <th><?php echo $model->getAttributeLabel('amount') ?></th>
                <th><?= Yii::t('app', 'Date') ?></th>
                <th><?= Yii::t('app', 'Status') ?></th>
                <th><?= Yii::t('app', 'Action') ?></th>
                </tr>
                </thead>
                <tbody class="appendRaw">
                    <?php
                    $i = 1;
                    if (!empty($staffModelData)) {
                        foreach ($staffModelData as $detailData) {
                            ?>  
                            <tr class="<?= $i ?>">
                                <?= Html::activeHiddenInput($detailData, '[' . $i . ']union_code'); ?> 
                                <?= Html::activeHiddenInput($detailData, '[' . $i . ']staff_installment_code'); ?> 
                                <?= Html::activeHiddenInput($detailData, '[' . $i . ']installment_no'); ?> 
                                <?= Html::activeHiddenInput($detailData, '[' . $i . ']staff_addition_deduction_no'); ?> 
                                <?= Html::activeHiddenInput($detailData, '[' . $i . ']amount'); ?> 
                                <?= Html::activeHiddenInput($detailData, '[' . $i . ']deduction_date', ['value' => date('m-Y', strtotime($detailData->deduction_date))]) ?> 

                                <td class="installment_no"><?= $i ?></td>
                                <td class="amount"><?= $detailData->amount ?></td>
                                <td class="deduction_date"><?= date('m-Y', strtotime($detailData->deduction_date)) ?></td>
                                <td class="salary_processed"><?= ($detailData->salary_processed) == 0 ? 'Unprocessed' : '' ?></td>
                                <td class="installment_no"><a href="javascript:void(0)" class='edit' onClick="editSalaryInstall('<?= $i ?>')" id='<?= $i ?>' title='Edit'><span class='glyphicon glyphicon-pencil'></span></a></td>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="panel-footer col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?php
    $btn = ($type == 'create') ? 'web-create-salary-installment' : 'web-update-salary-installment';
    $url = ($type == 'create') ? ['create-salary-installment'] : ['update-salary-installment', 'id' => $model->staff_addition_deduction_no];
    AjaxSubmitButton::begin([
        'label' => !empty($label) ? Yii::t('app', $label) : Yii::t('app', 'Save'),
        'id' => $btn,
        'ajaxOptions' => [
            'type' => 'POST',
            'url' => Url::to($url),
            'beforeSend' => new JsExpression("function(data){  
                        var amount = 0;
                        $('.amount').each(function(i){
                            var selectAmt = $(this).text(); 
                            amount = parseFloat(amount) + parseFloat(selectAmt);
                        });
                        
                        var netAmt = $('#tblstaffadditiondeduction-amount').val();
                        netAmt = parseFloat(netAmt);
                        if(netAmt < amount) {
                           bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Total amount is not matched') . "</span>');
//                           bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle text-info\'></i></div><div class=\'col-sm-10 padding-left-0\'>Total amount is not matched.</div></div>');
                           return false;                        
                        }
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    }"),
            'success' => new JsExpression('function(data){ 
                                if (data.status == "success"){
                                    window.location="' . \Yii::$app->request->getHostInfo() . '"+data.url;
                                }else{
                                    $(".help-block").parent("div").removeClass("has-error");
                                    $(".help-block").html("");
                                    $(\'#loadercontent\').hide();
                                    $(\'#pageloader\').hide(); 
                                    $.each(data, function(key, val) {
                                        var parent_div = $("#"+key).parent("div");
                                        parent_div.find(".help-block").remove();
                                        $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                        $("#"+key).closest(".form-group").addClass("has-error");            
                                        if($(".field-"+key+" .select2").text().length > 0) {
                                        $(".field-"+key+" .help-block").insertAfter($(".field-"+key+" .select2"));
                                        }

                                    });            
                                }
                     }'),
        ],
        'options' => ['class' => 'btn btn-primary web_form_btn mr10',
            'type' => 'submit'],
    ]);
    AjaxSubmitButton::end();
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 

</div>
<?php ActiveForm::end(); ?>