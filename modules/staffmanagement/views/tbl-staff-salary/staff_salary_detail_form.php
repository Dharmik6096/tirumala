<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\web\View;
use app\modules\staffmanagement\models\TblStaffSalaryTransaction;

$url = $type == 'edit' ? ['update', 'id' => $salaryModel->staff_salary_code] : ['create'];
$label = $type == 'edit' ? 'update' : 'Add';

$form = ActiveForm::begin(['options' => [
                'field-class' => 'form-group col-sm-3'
            ], 'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
            //'labelOptions' => [ 'class' => false],
        ]]);
?>
<div class="row theme-box ">
<div class="col-sm-12">
    <div class="col-sm-5 ">
        <div class="table-responsive col-sm-12 hide_help_block padding_10_0 view-subtitle2" id='checkHasSubLedgerField'>
        <div class="col-sm-12 theme-box-heading">
            <div class="col-sm-4 col-xs-4">
                <h5><?php echo Yii::t('app', 'Addition'); ?></h5>
            </div>
            <div class="col-sm-4 col-xs-4">
                <h5><?php echo Yii::t('app', 'Total Amount'); ?></h5>
            </div>
            <div class="col-sm-4 col-xs-4">
                <?= $form->field($salaryModel, 'addition', ['options' => ['class' => 'form-group hide_help_block']])->textInput(['readOnly' => TRUE])->label(FALSE) ?>
            </div>
        </div>
            <table class="table table-bordered table-striped table-main table-language web_theme_table">
                <thead>
                    <tr>
                        <th><?php echo Yii::t('app', 'Head') ?></th>
                        <th><?php echo Yii::t('app', 'Old Value') ?></th>
                        <th><?php echo Yii::t('app', 'New Value') ?></th>
                        <th><?php echo Yii::t('app', 'LWP') ?> <br></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($staffHeadAdd as $headData) {
                        $salaryTrns = new TblStaffSalaryTransaction();
                        $code = !empty($staffSalary->staff_salary_code) ? $staffSalary->staff_salary_code : NULL;
                        $trnsData = $salaryTrns->getSalaryTrans($code, $headData->salary_head_code);
                        $oldVal = 0;
                        if (!empty($trnsData)) {
                            $oldVal = $trnsData->value;
                            $transModel->lwp_effect = $trnsData->lwp_effect;
                        }

                        $newVal = 0;
                        if (!empty($salaryModel->staff_salary_code)) {
                            $newData = $salaryTrns->getSalaryTrans($salaryModel->staff_salary_code, $headData->salary_head_code);
                            if (!empty($newData)) {
                                $newVal = $newData->value;
                                $transModel->lwp_effect = $newData->lwp_effect;
                                $transModel->staff_salary_transaction_code = $newData->staff_salary_transaction_code;
                            }
                        }
                        $transModel->value = $newVal;
                        ?>
                        <tr><?= Html::activeHiddenInput($transModel, '[' . $headData->salary_head_code . ']staff_salary_transaction_code'); ?> 
                            <td><?= $headData->salary_head_name ?></td>
                            <td><?= $oldVal ?></td>
                            <td class='number-validate'> <?= $form->field($transModel, '[' . $headData->salary_head_code . ']value', ['options' => ['class' => 'form-group number-validate addition']])->textInput(['value' => $newVal])->label(FALSE); ?></td>
                            <td class='hide_help_block member-checkbox-list'>
                                <?php
                                echo $form->field($transModel, '[' . $headData->salary_head_code . ']lwp_effect')->checkbox(['data-val' => $headData->salary_head_code, 'class' => 'checkbox'], false)->label(false);
                                ?>

                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="table-responsive col-sm-12 hide_help_block padding_10_0 view-subtitle2" id='checkHasSubLedgerField'>
            <div class="col-sm-12 theme-box-heading">
                <div class="col-sm-4 col-xs-4">
                    <h5><?php echo Yii::t('app', 'Deduction'); ?></h5>
                </div>
                <div class="col-sm-4 col-xs-4">
                    <h5><?php echo Yii::t('app', 'Total Deduction'); ?></h5>
                </div>
                <div class="col-sm-4 col-xs-4">
                    <?= $form->field($salaryModel, 'deduction', ['options' => ['class' => 'form-group hide_help_block']])->textInput(['readOnly' => TRUE])->label(FALSE) ?>
                </div>
            </div>
            <table class="table table-bordered table-striped table-main table-language web_theme_table">
                <thead>
                    <tr>
                        <th><?php echo Yii::t('app', 'Head') ?></th>
                        <th><?php echo Yii::t('app', 'Old Value') ?></th>
                        <th><?php echo Yii::t('app', 'New Value') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($staffHeadDeduct as $headData) {
                        $salaryTrns = new TblStaffSalaryTransaction();
                        $code = !empty($staffSalary->staff_salary_code) ? $staffSalary->staff_salary_code : NULL;
                        $trnsData = $salaryTrns->getSalaryTrans($code, $headData->salary_head_code);
                        if (!empty($trnsData)) {
                            $oldVal = $trnsData->value;
                        } else {
                            $oldVal = 0;
                        }
                        $newVal = 0;
                        if (!empty($salaryModel->staff_salary_code)) {
                            $newData = $salaryTrns->getSalaryTrans($salaryModel->staff_salary_code, $headData->salary_head_code);
                            if (!empty($newData)) {
                                $newVal = $newData->value;
                                $transModel->lwp_effect = $newData->lwp_effect;
                                $transModel->staff_salary_transaction_code = $newData->staff_salary_transaction_code;
                            }
                        }
                        $transModel->value = $newVal;
                        ?>
                        <tr> <?= Html::activeHiddenInput($transModel, '[' . $headData->salary_head_code . ']staff_salary_transaction_code'); ?>
                            <td><?= $headData->salary_head_name ?></td>
                            <td><?= $oldVal ?></td>
                            <td class='number-validate'> <?= $form->field($transModel, '[' . $headData->salary_head_code . ']value', ['options' => ['class' => 'form-group number-validate deduction']])->textInput(['class'=>'number-validate form-control'])->label(FALSE); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<div class="clearfix"></div>
<div class="col-sm-12 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', $label),
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to($url),
                'beforeSend' => new JsExpression("function(data){
                                        var len = $('input[class=\'checkbox\']:checked').length;
                                        if(len == 0){
                                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please Check Atleast one LWP</span></div></div>');
                                            return false;
                                        }     
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                'success' => new JsExpression('function(data){
//                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    window.location = data.url;
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                }else{
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
            ],
            'options' => ['class' => 'btn btn-default btn-raised',
                'type' => 'submit'],
        ]);
        AjaxSubmitButton::end();
        ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
    </div>

</div>
<?php ActiveForm::end(); ?>



