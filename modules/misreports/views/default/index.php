<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\usermanagement\components\GhostHtml;
use yii\grid\GridView;
use yii\web\View;

//$this->title = Yii::$app->label->title('view', 'Reports');
$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : '');
$inclass = !empty($result) ? '' : 'in';
$model->from_date = empty($model->from_date) ? date('d-m-Y') : $model->from_date;
$model->to_date = empty($model->to_date) ? date('d-m-Y') : $model->to_date;
$title = isset($this->title) ? $this->title : Yii::t('app', 'Search');
$defaultToggle = true;
$model->p_date = empty($model->p_date) ? date('d-m-Y') : $model->p_date;
$model->date = empty($model->date) ? date('d-m-Y') : $model->date;
if (isset($data['url1'])) {
    $this->params['menu'][] = Yii::$app->controls->custombutton($data['url1'][0], $data['url1'][1], $data['url1'][2]);
}

$downloadSapFiles = json_encode($fileDownloadArr);
?>
<div class="panel panel-default panel-main">

    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <?php
    $removeExportType = [];
    $exportEvents = [];
    if (isset($data['export_title']) && $data['export_title'] && !empty($result)) {
        $report_type = ($model->report_type == 0) ? Yii::t('app', 'VM') : (($model->report_type == 1) ? Yii::t('app', 'WQ') : Yii::t('app', 'SD'));
        $codeToAppend = '';
        if (!empty($model->bmc_code)) {
            if (is_array($model->bmc_code) && count($model->bmc_code) > 1) {
                $codeToAppend = 'All';
            } else {
                $codeToAppend = $model->getBmcCode($model->bmc_code);
            }
        } else if (!empty($model->mcc_code)) {
            if (is_array($model->mcc_code) && count($model->mcc_code) > 1) {
                $codeToAppend = 'All';
            } else {
                $codeToAppend = $model->getMccCode($model->mcc_code);
            }
        }
        $this->title = $codeToAppend . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($model->from_date)) . '_' . $model->from_shift;
        $removeExportType = ['CSV'];
        $exportEvents = ['onRenderSheet' => function($sheet, $widget) {
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword("password");
            },];
    } else if (!empty($data['removeExportType'])) {
        $removeExportType = $data['removeExportType'];
    }
    $this->title = !empty($data['export_file_name']) ? $data['export_file_name'] : $this->title;
    $multiArray = !empty($data['multiArray']) ? $data['multiArray'] : [];
    $reportClass = 'report-area';
    if (!empty($result) && isset($data['kartik_grid_view'])) {
        $reportClass = '';
    }
    ?>
    <div class="panel-body padding-0">
        <div class="<?= $reportClass ?> not_ellipsis">
            <div class="modal modal-default fade" id="mis_report_search_filter" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?php echo $title; ?></h4>
                        </div>
                        <!--<div class="table-responsive mt10 panel-collapse collapse <?= $inclass ?>" id="panel1">-->
                        <div class="">
                            <?php
                            $form = ActiveForm::begin(['options' => [
                                            'id' => 'report-form',
                                            'field-class' => 'form-group col-sm-6   '
                                        ],
                                        'method' => 'get',
                                        'validateOnBlur' => FALSE,
                                        'validateOnChange' => FALSE,
                                        'enableClientValidation' => true,
                                        'validateOnSubmit' => true,
                            ]);
                            ?>    
                            <div class="row margin_0">

                                <div class="modal-body">
                                    <?php //Yii::$app->dropdown->federation($model, $form, 'federation_code', false);   ?>  
                                    <?php
                                    $param = isset($data['param']) ? explode(',', $data['param']) : [];
                                    foreach ($param as $key => $value) {
                                        $value_array = explode(':', $value);
                                        $value = $value_array[0];

                                        if (isset($value_array[1]) && $value_array[1] == 'string') {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-3 padding-left-5 padding-right-5', false);
                                                ?>
                                            </div>    
                                            <?php
                                            if (isset($value_array[2])) {
                                                ?>
                                                <div class="col-sm-3 shift">
                                                    <?php
                                                    echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel($value_array[2]), false, $value_array[2]);
                                                    ?>
                                                </div>    
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('union_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                                            </div>   <?php
                                        }
                                        if (in_array($value, array('plant_code'))) {
                                            ?>
                                            <div class="col-sm-3 val_plant_code">
                                                <?= Yii::$app->dropdown->union_plant($model, $form, 'reportsmodelold-union_code', 'plant_code', 'Plant'); ?>
                                            </div>
                                        <?php } if (in_array($value, array('mcc_code'))) { ?>
                                            <div class="col-sm-3 val_mcc_code">
                                                <?php
                                                $multiple = in_array($value, $multiArray) ? true : false;
                                                if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                    echo Yii::$app->dropdown->union_mcc($model, $form, 'reportsmodelold-union_code', $value, $model->getAttributeLabel('mcc_code'), $multiple);
                                                } else {
                                                    echo Yii::$app->dropdown->plant_mcc($model, $form, 'reportsmodelold-plant_code', $value, Yii::t('app', 'MCC'), $multiple);
                                                }
                                                ?>                
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('bmc_code'))) {
                                            $multiple = in_array($value, $multiArray) ? true : false;
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'reportsmodelold-mcc_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), $multiple); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('dcs_code'))) {
                                            ?>
                                            <div class="col-sm-3 val_dcs_code">
                                                <?= Yii::$app->dropdown->bmc_society($model, $form, 'reportsmodelold-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (isset($value_array[1]) && $value_array[1] == 'txt') {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= $form->field($model, $value_array[0])->textInput(['maxlength' => true]) ?>
                                            </div>    
                                            <?php
                                        }
                                        if (in_array($value, array('customer_code'))) {
                                            ?>
                                            <div class="col-sm-3 val_dcs_code">
                                                <?= Yii::$app->dropdown->merge_dcs_customer($model, $form, 'reportsmodelold-bmc_code', 'customer_code', Yii::t('app', 'Name')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('customer_type'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->customer_type($model, $form, 'reportsmodelold-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('vendor_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->customer_code($model, $form, 'reportsmodelold-bmc_code,reportsmodelold-customer_type', 'vendor_code', $model->getAttributeLabel('vendor_code'), FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('payment_cycle_code'))) {

                                            if (isset($value_array[1]) && isset($value_array[2]) && $value_array[1] == 'default') {
                                                echo Html::hiddenInput('customer_type', $value_array[2], ['id' => 'reportsmodelold-customer_type']);
                                                $where = json_encode(['data_lock_member' => 1]);
                                            } else {
                                                $where = json_encode(['data_lock_bmc' => 1]);
                                            }
                                            echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
                                            echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->paymentCycle($model, $form, 'reportsmodelold-union_code,reportsmodelold-bmc_code,reportsmodelold-customer_type,applicable_for,data_lock_bmc', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('member_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'reportsmodelold-dcs_code', '', $model->getAttributeLabel('member')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_organization_type'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'static') {
                                                ?>

                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->dropdownStatic($value_array[2], $model, $form, 'form-group padding-right-5', $model->getAttributeLabel('p_organization_type'), false, 'p_organization_type') ?> 
                                                </div>
                                                <?php
                                            }
                                        }

                                        if (in_array($value, array('rate_type', 'bank_type', 'report_status'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'static') {
                                                ?>

                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->dropdownStatic($value_array[2], $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('p_purchase_rate_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'rate_type') {
                                                ?>
                                                <div class="col-sm-3 val_dcs_code">
                                                    <?php // Yii::$app->dropdown->org_type_rate($model, $form, 'reportsmodelold-p_organization_type', 'p_purchase_rate_code', $model->getAttributeLabel('p_purchase_rate_code'));   ?>
                                                    <?= Yii::$app->dropdown->memberRateChart($model, $form, 'reportsmodelold-union_code,reportsmodelold-rate_type', 'p_purchase_rate_code', $model->getAttributeLabel('p_purchase_rate_code')); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('member_type'))) {
                                            ?>
                                            <div class="col-sm-3 val_dcs_code">
                                                <?= Yii::$app->dropdown->dropdown('member-type', $model, $form, '', $model->getAttributeLabel($value), false, 'member_type'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('route_code'))) {
                                            ?>
                                            <div class="col-sm-3 val_dcs_code">
                                                <?= Yii::$app->dropdown->union_routes($model, $form, 'reportsmodelold-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Route', $value); ?>                                           
                                            </div>
                                            <?php
                                        }
                                    }

                                    if (isset($data['report_type'])) {
                                        echo $form->field($model, 'report_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($data['report_type'], ['prompt' => Yii::t('app', 'Select Type')]);
                                    }
                                    ?>

                                    <div class="modal-footer mt10 col-sm-12">
                                        <?php
                                        if ($param) {
                                            if (!empty($fileDownloadArr)) {
                                                echo Html::hiddenInput('upload_ftp_file', '0', ['id' => 'reportsmodelold-upload_ftp_file']);
                                            }
                                            echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'html', 'value' => 'html', 'id' => 'html']);
                                        }
                                        ?>
                                        <button type="button" class="btn btn-danger close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $class = 'beforeGridLoad';
            if (!empty($result)) {
                $defaultToggle = false;
                if (!(isset($data['download_only'])) && is_array($result)) {
                    $class = '';
                }
            }
            if (!empty($model->getErrors())) {
                $defaultToggle = true;
            }
            ?>

            <div class="grid-search search-filter searchBtnReport text-right <?= $class ?>">
                <div class="btn-group btn btn-default mis_report_modal_toggle"><i class="fa fa-search"></i></div>
            </div>

            <?php if (!empty($result) && !(isset($data['download_only']))) { ?>

                <!--                                <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="fa fa-search"></i></a>
                                                </div>-->
            <?php } ?>
            <?php
            if (!empty($result) && !is_array($result)) {
                echo "<b><p class='text-center mt-50'>" . $result . "</p></b>";
            } else if (!empty($result) && !(isset($data['download_only']))) {
                $attr = [];
                foreach ($result[0] as $att => $value) {
                    $attr_arr = [];
                    $format = 'raw';
                    if (in_array($att, ['Quantity', 'FAT', 'CLR', 'SNF'])) {
                        $format = ['decimal', 2];
                    }
//                    $attr_arr['attribute'] = $att;
                    $attr_arr = [];
                    if (!empty($data['to_decrypt'])) {
                        $attr_arr['value'] = function($model) use ($att) {
                            return !empty($model[$att]) ? (Yii::$app->general->decryptData($model[$att]) !== FALSE ? Yii::$app->general->decryptData($model[$att]) : $model[$att]) : (isset($model[$att]) && $model[$att] == 0 && $model[$att] != '' ? 0 : '');
                        };
                    }

                    $str = ucwords(str_replace('_', ' ', $att));
                    $attr_arr['attribute'] = $att;
                    $attr_arr['label'] = Yii::t('app', $str);
                    $attr_arr['format'] = $format;
                    $attr_arr['filter'] = false;
                    $attr[] = $attr_arr;
//                    $attr[] = ['attribute' => $att, 'label' => Yii::t('app', $str), 'format' => $format, 'filter' => false];
                }
                $grid_option = [
                    'id' => 'mis-report-list',
                    'attributes' => $attr,
                    'active_column' => false,
                ];

                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], true, $removeExportType, $exportEvents);
            }
            ?>
        </div>
        <?php
        if (!empty($fileDownloadArr)) {
            echo GhostHtml::submitButton('<i class="text-white far fa-file"></i>', ['class' => 'btn btn-default submit_btn downloadSapFiles apply-shortcut', 'name' => 'download', 'value' => 'download', 'id' => 'download', 'title' => Yii::t('app', 'download')]);
            echo GhostHtml::submitButton('FTP Upload', ['class' => 'btn btn-default apply-shortcut uploadSapFiles', 'name' => 'ftp-upload', 'value' => 'ftp-upload', 'id' => 'ftp-upload', 'title' => Yii::t('app', 'Ftp Upload')]);
        }
        ?>
    </div>
</div>       
<?php
$baseUrl = Yii::$app->request->baseUrl;
$count = count($fileDownloadArr);
$timeOutForLoader = ($count * 1000) + 2000;
$script = "
$('.mis_report_modal_toggle').on('click', function(){
    $('#mis_report_search_filter').modal('toggle');
});

var timeOut = 500;
$(document).on('click', '.downloadSapFiles', function(e){
    e.preventDefault();
    $('#loadercontent').show();
    $('#pageloader').show();
    timeOut = 500;
    var baseUrl = '" . $baseUrl . "/web/sap_data_files/';
    var downloadFilesJson = '" . $downloadSapFiles . "';
    var timeOutForLoader = " . $timeOutForLoader . ";
    var downloadFilesJsonAr = JSON.parse(downloadFilesJson);
    $.each(downloadFilesJsonAr, function(ind, vl) {
        setTimeout(() => {
            window.location.href = baseUrl + vl;
        }, timeOut);
        timeOut = timeOut + 1000;
    });
    setTimeout(() => {
        $('#loadercontent').hide();
        $('#pageloader').hide();
    }, timeOutForLoader);
    return false;
});

$(document).on('click', '.uploadSapFiles', function(e){
    $('#reportsmodelold-upload_ftp_file').val('1');
    $('#report-form').submit();
});

";

if ($defaultToggle) {
    $script .= "
        $(document).ready(function () {
            $('#mis_report_search_filter').modal('toggle');
        });
    ";
}
$this->registerJs($script, View::POS_READY, 'mis-report-script');
?>
