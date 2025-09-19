<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;

$unionCode = $searchModel->union_code;
$plantCode = $searchModel->plant_code;
$mccPlantCode = $searchModel->mcc_plant_code;
$bmcCode = $searchModel->bmc_code;
$dpuType = $searchModel->dpu_type;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'repush-form',
            'action' => ['repush'],
            'method' => 'post',
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="panel-body set_checkbox padding_top_0 tbl_border">
    <div class="clearfix"></div>
    <div class="panel-subheading hide_help_block">
        <div class="row">
            <div class="table-responsive">
                <?php
                $modelData = $dataProvider->getModels();
                if (!empty($modelData)) {
                    $count = count($modelData);
                    $disp_table = $count / 3;
                    $first_table = ceil($disp_table);
                    $second_table = $first_table * 2;
                    ?>

                    <?= Html::hiddenInput('union_code', $unionCode); ?>
                    <?= Html::hiddenInput('plant_code', $plantCode); ?>
                    <?= Html::hiddenInput('mcc_plant_code', $mccPlantCode); ?>
                    <?= Html::hiddenInput('bmc_code', $bmcCode); ?>
                    <?= Html::hiddenInput('dpu_type', $dpuType); ?>
                    <?= Html::hiddenInput('file_type', null, ['id' => 'repush_file_type_hidden_input']); ?>

                    <div class="col-sm-4 set_overflow padding_10_0 theme-box view-subtitle2">
                        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                            <h4 class="theme-box-heading"><?= Yii::t('app', 'Society') ?> </h4>
                        </div>
                        <table class="table table-bordered table-striped table-main table-language table-rate">
                            <thead>
                                <tr>
                                    <th width='17%' height='35' class='center-align center_text'>
                                        <?= Html::checkbox('allowCashCheckAll_1', false, ['id' => 'allowCashCheckAll_1', 'class' => 'checkbox check-all-dcs']) ?>
                                    </th>
                                    <th width='60%' height='35'> <?= Yii::t('app', 'Society Name') . ' (' . 'Ex Code' . ') ' . '(' . 'Ref Code' . ')'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < $first_table; $i++) {
                                    $dcs = $modelData[$i];
                                    ?>
                                    <tr>
                                        <td class='center-align center_text'>
                                            <?= Html::checkbox('dcs_codes[]', false, ['value' => $dcs->dcs_code, 'class' => 'allow-cash-checkbox', 'id' => 'dcs_checkbox_' . $dcs->dcs_code]) ?>
                                        </td>
                                        <td><?= $dcs->dcs_name . ' (' . $dcs->dcs_code_ex . ') (' . $dcs->ref_code . ')'; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-4 set_overflow padding_10_0 theme-box view-subtitle2">
                        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                            <h4 class="theme-box-heading"><?= Yii::t('app', 'Society') ?></h4>
                        </div>
                        <table class="table table-bordered table-striped table-main table-language table-rate">
                            <thead>
                                <tr>
                                    <th width='17%' height='35'></th>
                                    <th width='60%' height='35'> <?= Yii::t('app', 'Society Name') . ' (' . 'Ex Code' . ') ' . '(' . 'Ref Code' . ')'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = $first_table; $i < $second_table; $i++) {
                                    if (isset($modelData[$i])) {
                                        $dcs = $modelData[$i];
                                        ?>
                                        <tr>
                                            <td class='center-align center_text'>
                                                <?= Html::checkbox('dcs_codes[]', false, ['value' => $dcs->dcs_code, 'class' => 'allow-cash-checkbox', 'id' => 'dcs_checkbox_' . $dcs->dcs_code]) ?>
                                            </td>
                                            <td><?= $dcs->dcs_name . ' (' . $dcs->dcs_code_ex . ') (' . $dcs->ref_code . ')'; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-4 set_overflow padding_10_0 theme-box view-subtitle2">
                        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                            <h4 class="theme-box-heading"> <?= Yii::t('app', 'Society') ?></h4>
                        </div>
                        <table class="table table-bordered table-striped table-main table-language table-rate">
                            <thead>
                                <tr>
                                    <th width='17%' height='35'></th>
                                    <th width='60%' height='35'> <?= Yii::t('app', 'Society Name') . ' (' . 'Ex Code' . ') ' . '(' . 'Ref Code' . ')'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = $second_table; $i < $count; $i++) {
                                    if (isset($modelData[$i])) {
                                        $dcs = $modelData[$i];
                                        ?>
                                        <tr>
                                            <td class='center-align center_text'>
                                                <?= Html::checkbox('dcs_codes[]', false, ['value' => $dcs->dcs_code, 'class' => 'allow-cash-checkbox', 'id' => 'dcs_checkbox_' . $dcs->dcs_code]) ?>
                                            </td>
                                            <td><?= $dcs->dcs_name . ' (' . $dcs->dcs_code_ex . ') (' . $dcs->ref_code . ')'; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    echo "<p class='text-center'>" . Yii::t('app', 'Data Not Available') . "</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?php if (!empty($modelData)) { ?>
        <?= Html::submitButton(Yii::t('app', 'MEMBER Re-Push'), ['class' => 'btn btn-primary btn-login btn', 'id' => 'member-repush-btn']) ?>
        <?= Html::submitButton(Yii::t('app', 'RATE Re-Push'), ['class' => 'btn btn-primary btn-login btn', 'id' => 'rate-repush-btn']) ?>
    <?php } ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
 $('#member-repush-btn').on('click', function(e) {
        e.preventDefault(); 
        if (!validateDcsSelection()) {
            return;
        }
        $('#member-repush-btn').addClass('disabled no_pointer');
        $('#repush_file_type_hidden_input').val('MEMBER');
        $('#repush-form').submit();
    });

    $('#rate-repush-btn').on('click', function(e) {
        e.preventDefault(); 
        if (!validateDcsSelection()) {
            return;
        }
        $('#rate-repush-btn').addClass('disabled no_pointer');
        $('#repush_file_type_hidden_input').val('RATE');
        $('#repush-form').submit();
    });

    function validateDcsSelection() {
        if ($('.allow-cash-checkbox:checked').length == 0) {
            bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span> Please select at least one DCS.</span></div></div>');
            return false;
        }
        return true;
    }

    $('#allowCashCheckAll_1').click(function () {
        var check = this.checked;
        $('.allow-cash-checkbox').each(function () {
            this.checked = check;
        });
    });

    $(document).on('change', '.allow-cash-checkbox', function() {
        if (!this.checked) {
            $('#allowCashCheckAll_1').prop('checked', false);
        } else {
            var allChecked = true;
            $('.allow-cash-checkbox').each(function() {
                if (!this.checked) {
                    allChecked = false;
                    return false; 
                }
            });
            $('#allowCashCheckAll_1').prop('checked', allChecked);
        }
    });

    $(document).ready(function() {
        var allChecked = true;
        if($('.allow-cash-checkbox').length > 0) {
            $('.allow-cash-checkbox').each(function() {
                if (!this.checked) {
                    allChecked = false;
                    return false;
                }
            });
            $('#allowCashCheckAll_1').prop('checked', allChecked);
        } else {
            $('#allowCashCheckAll_1').prop('checked', false); 
        }
    });

     ";
$this->registerJs($script, View::POS_END, 'member-rate-repush-js');
