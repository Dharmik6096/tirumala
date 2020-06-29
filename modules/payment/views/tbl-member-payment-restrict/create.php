<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\jui\DatePicker;
use yii\helpers\Url;

$button = Yii::$app->label->button('create');
$this->title = Yii::t('app', 'Member Payment Restrict');
$defaultToggle = true;
?>
<div class="panel panel-default panel-main">

    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="padding-0">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?> 
        <?php
        $form = ActiveForm::begin(['options' => [
                        'field-class' => 'form-group col-sm-3'
                    ], 'validateOnBlur' => FALSE,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
                    'fieldConfig' => [
        ]]);
        ?>
        <?php echo $form->errorSummary($saveModel); ?>
        <div class="panel-body set_checkbox padding_top_0 tbl_border">

            <div class="grid-search search-filter padding_left_0 padding_right_0 searchBtnReport text-right beforeGridLoad">
                <?php if (!empty($model)) { ?>

                    <div class="col-sm-2 padding-left-0 text-left">
                        <?= Yii::$app->controls->date($searchModel, $form, 'wef_date', 'form-group col-sm-2', false); ?>
                    </div>
                <?php } ?>
                <div class="btn-group btn btn-default mis_report_modal_toggle"><i class="fa fa-search"></i></div>
            </div>
            <div class="clearfix"></div>
            <div class="panel-subheading hide_help_block">
                <div class="row">
                    <div class="table-responsive">
                        <?php
                        if (!empty($model)) {
                            $defaultToggle = false;
                            $count = count($model);
                            $disp_table = $count / 3;
                            $first_table = ceil($disp_table);
                            $second_table = $first_table * 2;
                            ?>
                            <div class="col-sm-4 ">
                                <table class="table table-bordered table-striped table-main table-language table-rate">
                                    <thead>
                                        <tr>
                                            <th width='17%' height='25' class='center-align center_text'><?= Html::checkbox('allowCashCheckAll', false, ['id' => 'allowCashCheckAll', 'class' => 'checkbox checkboxHeight', 'label' => '']) ?></th>
                                            <th width='60%' height='25'><?php echo $model[0]->getAttributeLabel('dcs_name') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < $first_table; $i++) { ?>
                                            <tr>
                                                <td class='center-align center_text'><?= $form->field($saveModel[$i], '[' . $i . ']dcs_code')->checkbox(['class' => 'allow-cash-checkbox checkboxHeight', 'value' => $model[$i]->dcs_code], false)->label(false); ?></td>
                                                <td><?= $model[$i]->dcs_name . '(' . $model[$i]->dcs_code_ex . ')'; ?><?= Html::activeHiddenInput($saveModel[$i], '[' . $i . ']wef_date', ['class' => 'setWefDate']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-4 ">
                                <table class="table table-bordered table-striped table-main table-language table-rate">
                                    <thead>
                                        <tr>
                                            <th width='17%' height='25'></th>
                                            <th width='60%' height='25'><?php echo $model[0]->getAttributeLabel('dcs_name') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($i = $first_table; $i < $second_table; $i++) {
                                            ?>
                                            <tr>
                                                <td class='center-align center_text'><?= $form->field($saveModel[$i], '[' . $i . ']dcs_code')->checkbox(['class' => 'allow-cash-checkbox checkboxHeight', 'value' => $model[$i]->dcs_code], false)->label(false); ?></td>
                                                <td><?= $model[$i]->dcs_name . '(' . $model[$i]->dcs_code_ex . ')'; ?><?= Html::activeHiddenInput($saveModel[$i], '[' . $i . ']wef_date', ['class' => 'setWefDate']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-4 ">
                                <table class="table table-bordered table-striped table-main table-language table-rate">
                                    <thead>
                                        <tr>
                                            <th width='17%' height='25'></th>
                                            <th width='60%' height='25'><?php echo $model[0]->getAttributeLabel('dcs_name') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($i = $second_table; $i < $count; $i++) {
                                            ?>
                                            <tr>
                                                <td class='center-align center_text'><?= $form->field($saveModel[$i], '[' . $i . ']dcs_code')->checkbox(['class' => 'allow-cash-checkbox checkboxHeight', 'value' => $model[$i]->dcs_code], false)->label(false); ?></td>
                                                <td><?= $model[$i]->dcs_name . '(' . $model[$i]->dcs_code_ex . ')'; ?><?= Html::activeHiddenInput($saveModel[$i], '[' . $i . ']wef_date', ['class' => 'setWefDate']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        } else {
                            if (!empty($searchModel->f_mcc_code)) {
                                echo "<p class='text-center'>" . Yii::t('app', 'Selected MCC does not contains any DCS or Each DCS has already in Force Rate Download stage') . "</p>";
                            } else {
                                echo "<p class='text-center'>" . Yii::t('app', 'Data Not Available') . "</p>";
                            }
                            $defaultToggle = true;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">

            <?php if (!empty($model)) { ?>
                <?= Yii::$app->controls->save($button, $model[0]); ?>
                <?= Yii::$app->controls->reset(); ?>
            <?php } ?>
            <?= Yii::$app->controls->cancel($searchModel); ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$script = "
    
    $(document).on('change', '#tblmemberpaymentrestrict-wef_date', function(){
        var wefDate = $(this).val();
        $('.setWefDate').val(wefDate);
    });

    $('#allowCashCheckAll').click(function () {
        var check =this.checked;
        $('.allow-cash-checkbox').each(function () {
            this.checked = check;
        });
    });
    $('.allow-cash-checkbox').each(function () {
        $('#allowCashCheckAll').prop('checked', true);
        if(this.checked == false){
            $('#allowCashCheckAll').prop('checked', false);
        }
    });
    
    $('#paymentModeCheckAll').click(function () {
        var check =this.checked;
        $('.payment-mode-checkbox').each(function () {
            this.checked = check;
        });
    });
    $('.payment-mode-checkbox').each(function () {
        $('#paymentModeCheckAll').prop('checked', true);
        if(this.checked == false){
            $('#paymentModeCheckAll').prop('checked', false);
        }
    });
    $(document).ready(function () {
        var lengthOfUl = $('.error-summary ul li').length;
        if(lengthOfUl > 0) {
            $('.error-summary ul li:eq(0)').before('<li>" . Yii::t('app', 'Member payment is Already restricted for following.') . "</li>');
        }
    });
    $('.mis_report_modal_toggle').on('click', function(){
        $('#mis_report_search_filter').modal('toggle');
    });
        
";
if ($defaultToggle) {
    $script .= "
        $(document).ready(function () {
            $('#mis_report_search_filter').modal('toggle');
        });
        
        
    ";
}
$this->registerJs($script, View::POS_END, 'force-rate-download');
