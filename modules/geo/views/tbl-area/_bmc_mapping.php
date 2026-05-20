<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use yii\web\View;

$title = Yii::$app->label->title('create', 'Area Mapping');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">Area Mapping For :: <?= Yii::$app->general->getforeignkey($searchModel->mainAreaCode, 'area_code') . ' - ' . Yii::$app->general->getforeignkey($searchModel->mainAreaCode, 'area_name') ?></div>

    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'save-form',
                    ],
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => false,
        ]);
        ?>
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h5 class="theme-box-heading"><?php echo Yii::t('app', $title); ?></h5>
                </div>
                <?php echo $form->errorSummary($model); ?>
                <div class="col-sm-12 margin-top-10">
                    <div class="col-md-12 padding_10_0 theme-box theme_border_left theme_border_right theme_border_bottom view-subtitle">
                        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                            <h4 class="theme-box-heading">Apply To</h4>
                        </div>
                        <?php
                        echo Html::radioList('applicable_type', $model->applicable_type, ['BMC' => 'BMC', 'DCS' => 'DCS'], [
                            'separator' => " ",
                            'id' => 'applicable_type',
                            'class' => 'app-radio-list radio-list',
                            'itemOptions' => ['class' => 'applicable-type-filter'],
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-6  margin-bottom-10">
                        <div class="btn-group">
                            <span class="input-group-btn">
                                <span id="show-only-selected-data" class="btn btn-default btn-sm">
                                    <i class="fa fa-minus"></i> Show only selected
                                </span>
                                <span id="show-all" class="btn btn-default hide btn-sm">
                                    <i class="fa fa-plus"></i> Show all
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-12 padding_10_0">
                        <!-- BMC Filter Section (for DCS only) -->
                        <div class="col-sm-4 padding-left-0 selectBmcArea" style="display:none;" id="bmc-filter-container">
                            <h4 class="theme-box-heading padding_left_0 padding_right_0"><?= Yii::t('app', 'BMC List') ?></h4>
                            <div class="app-check-list-bmc ">
                                <div class="form-group">
                                    <div class="checkbox app-check-all-bmc app-check-list-padding">
                                        <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => 'f_bmc_code', 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
                                        <label class="route-text">
                                            <?= Html::checkbox('checkall', false, ['id' => 'checkAllBmcList', 'class' => 'bmc-list-checkbox']) ?>
                                            <label for="checkAllBmcList"><?= Yii::t('app', 'Check ALL BMC') ?></label>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div id="bmc-filter-list" class="app-check-list-padding row mb15">
                                    
                                </div>
                            </div>
                        </div>

                        <!-- Data Section (BMC or DCS) -->
                        <div class="col-sm-12 padding-left-0 padding-right-0 applicableCodeArea" id="applicable-data-container">
                            <h4 class="theme-box-heading padding_left_0 padding_right_0" id="data-list-title"><?= Yii::t('app', 'Applicable List') ?></h4>
                            <div class="app-check-list ">
                                <div class="form-group">
                                    <div class="checkbox app-check-all app-check-list-padding">
                                        <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => 'f_data_code', 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
                                        <label class="route-text">
                                            <?= Html::checkbox('checkall', false, ['id' => 'checkAllDataList', 'class' => 'data-list-checkbox']) ?>
                                            <label for="checkAllDataList"><?= Yii::t('app', 'Check All') ?></label>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div id="bmc-list" class="app-check-list-padding row mb15">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">                    
                            <?= Yii::$app->controls->save($button, $model); ?>
                            <?= Yii::$app->controls->reset(); ?>
                            <?= Yii::$app->controls->cancel($model); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div id="mapping-grid-container" class="col-sm-12 padding-left-0 padding-right-0">
            <?php
            echo $this->render('_mapped_bmc', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
            </div>
        </div>
    </div>

    <?php
    echo $this->render('@app/components/views/mapping_checkbox_script');
    
    $ajaxUrl = Url::to(['get-applicability-data']);
    $dcsAjaxUrl = Url::to(['get-dcs-by-bmc']);
    $area_code = $searchModel->area_code;
    $applicable_type = $model->applicable_type;

    $selectedCodes = isset($selectedCodes) ? $selectedCodes : [];
    $selectedBmcs = isset($selectedBmcs) ? $selectedBmcs : [];

    $script = "
        var applicable_type = '{$applicable_type}';
        var selectedCodes = " . json_encode($selectedCodes) . ";
        var selectedBmcs = " . json_encode($selectedBmcs) . ";
        var isInitialLoad = true;

        if(applicable_type != '' && applicable_type != null && applicable_type != 'undefined') {
            fetchApplicabilityData();
        }
        function checkBoxFilter(val){
            var id = $(val).attr('id');
            var value = $(val).val();
            var targetListId = id === 'f_bmc_code' ? 'bmc-filter-list' : 'bmc-list';
            $('#' + targetListId + ' div.checklist').each(function() {
                if ($(this).text().search(new RegExp(value, 'i')) < 0) {
                    $(this).hide();
                    $(this).find(':input').prop('disabled', true);
                } else {
                    $(this).show();
                    $(this).find(':input').prop('disabled', false);
                }
            });
        }

        function fetchApplicabilityData() {
            var type = $('input[name=applicable_type]:checked').val();
            if(type != '' && type != undefined) {
                $('#bmc-filter-list').html('');
                $('#bmc-list').html('');
                $('#checkAllBmcList').prop('checked', false);
                $('#checkAllDataList').prop('checked', false);
                $('#f_bmc_code').val('');
                $('#f_data_code').val('');

                if (type == 'DCS') {
                    $('#bmc-filter-container').show();
                    $('#applicable-data-container').removeClass('col-sm-12').addClass('col-sm-8');
                    $('#data-list-title').text('DCS List');
                } else {
                    $('#bmc-filter-container').hide();
                    $('#applicable-data-container').removeClass('col-sm-8').addClass('col-sm-12');
                    $('#data-list-title').text('BMC List');
                }

                $.ajax({
                    url: '{$ajaxUrl}',
                    type: 'POST',
                    data: {applicable_type: type, id: '{$area_code}', _csrf: yii.getCsrfToken()},
                    success: function(response) {
                        if(response.status == 'success') {
                            if (type == 'DCS') {
                                $('#bmc-filter-list').html(response.data);
                                if (isInitialLoad && selectedBmcs && selectedBmcs.length > 0) {
                                    $.each(selectedBmcs, function(index, value) {
                                        $('.bmc-filter-checkbox[value=\"' + value + '\"]').prop('checked', true);
                                    });
                                }
                                fetchDcsData();
                            } else {
                                $('#bmc-list').html(response.data);
                                if (isInitialLoad && selectedCodes && selectedCodes.length > 0) {
                                    $.each(selectedCodes, function(index, value) {
                                        $('.data-checkbox[value=\"' + value + '\"]').prop('checked', true);
                                    });
                                }
                                isInitialLoad = false;
                            }
                            $('#mapping-grid-container').html(response.grid);
                        }
                    }
                });
            }
        }
        
        function fetchDcsData() {
            var selectedBmc = [];
            $('.bmc-filter-checkbox').each(function () {
                if ($(this).is(':checked')) {
                    selectedBmc.push($(this).val());
                }
            });
            $('#bmc-list').html('');
            $('#checkAllDataList').prop('checked', false);
            $('#f_data_code').val('');
            
            $.ajax({
                url: '{$dcsAjaxUrl}',
                type: 'POST',
                data: {selected_bmc: selectedBmc, id: '{$area_code}', _csrf: yii.getCsrfToken()},
                success: function(response) {
                    if(response.status == 'success') {
                        $('#bmc-list').html(response.data);
                        if (isInitialLoad && selectedCodes && selectedCodes.length > 0) {
                            $.each(selectedCodes, function(index, value) {
                                $('.data-checkbox[value=\"' + value + '\"]').prop('checked', true);
                            });
                        }
                        isInitialLoad = false;
                        $('#mapping-grid-container').html(response.grid);
                    }
                }
            });
        }

        $('.applicable-type-filter').on('change', function() {
            fetchApplicabilityData();
        });

        $(document).on('change', '.bmc-filter-checkbox', function() {
            fetchDcsData();
        });

        $('#checkAllBmcList').click(function (event) {
            $('.bmc-filter-checkbox').not(':disabled').prop('checked', $(this).is(':checked'));
            fetchDcsData();
        });
        
        $('#checkAllDataList').click(function (event) {
            $('.data-checkbox').not(':disabled').prop('checked', $(this).is(':checked'));
        });

        $('.save-form').on('submit', function(e) {
            var type = $('input[name=\"applicable_type\"]:checked').val();
            if ($('.data-checkbox:checked').length === 0) {
                e.preventDefault();
                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one ' + type + '.</span></div></div>');
                return false;
            }
        });

        var isDeleting = false;
        $(document).on('click', '#source-grid .delete-record', function() {
            isDeleting = true;
        });

        $(document).on('pjax:success', '#source-grid', function(event) {
            if (isDeleting) {
                isDeleting = false;
                var type = $('input[name=\"applicable_type\"]:checked').val();
                if (type == 'DCS') {
                    fetchDcsData();
                } else {
                    fetchApplicabilityData();
                }
            }
        });
    ";
    $this->registerJs($script, \yii\web\View::POS_END);
    ?>