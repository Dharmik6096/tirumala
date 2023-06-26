<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\usermanagement\components\GhostHtml;
use yii\grid\GridView;
use yii\web\View;
use yii\helpers\Url;

//$this->title = Yii::$app->label->title('view', 'Reports');
$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : '');
$inclass = !empty($result) ? '' : 'in';

$model->from_date = empty($model->from_date) ? date('d-m-Y') : $model->from_date;
$model->to_date = empty($model->to_date) ? date('d-m-Y') : $model->to_date;
$showPopup = FALSE;
if (isset($data['url1'])) {
    $this->params['menu'][] = Yii::$app->controls->custombutton($data['url1'][0], $data['url1'][1], $data['url1'][2]);
}
$downloadSapFiles = json_encode($fileDownloadArr);
?>
<div class="panel panel-default panel-grid panel-main">

    <div class="panel-heading"><?= Html::encode($this->title) ?></div>

    <div class="panel-body">
        <div class="report-area hide-grid-settings hide-grid-search not_ellipsis">
            <div class="table-responsive mt10 panel-collapse collapse <?= $inclass ?>" id="panel1">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'id' => 'ftpgenerate-form',
                                'field-class' => 'form-group col-sm-3'
                            ],
                            'validateOnBlur' => FALSE,
//                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>    
                <?php
                $param = isset($data['param']) ? explode(',', $data['param']) : [];
                foreach ($param as $key => $value) {
                    $value_array = explode(':', $value);
                    $value = $value_array[0];

                    if (isset($value_array[1]) && $value_array[1] == 'string') {
                        ?>
                        <div class="col-sm-3">
                            <?php
                            echo Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-2 padding-left-5 padding-right-5', false);
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
                            <?= Yii::$app->dropdown->union_plant($model, $form, 'ftpgenerate-union_code', 'plant_code', 'Plant'); ?>
                        </div>
                    <?php } if (in_array($value, array('mcc_code'))) { ?>
                        <div class="col-sm-3 val_mcc_code">
                            <?php
                            if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                Yii::$app->dropdown->union_mcc($model, $form, 'ftpgenerate-union_code', $value, $model->getAttributeLabel('mcc_code'));
                            } else {
                                echo Yii::$app->dropdown->plant_mcc($model, $form, 'ftpgenerate-plant_code', $value, 'MCC');
                            }
                            ?>                
                        </div>
                        <?php
                    }
                    if (in_array($value, array('bmc_code'))) {
                        ?>
                        <div class="col-sm-3 val_bmc_code">
                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'ftpgenerate-mcc_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
                        </div>
                        <?php
                    }

                    if (isset($value_array[1]) && $value_array[1] == 'hidden') {
                        echo Html::activeHiddenInput($model, $value);
                    }
                }
                ?>
                <div class="clearfix"></div>
                <div class="col-sm-2 mt25">
                    <?php
                    if ($param) {
                        echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut generate', 'name' => 'html', 'value' => 'html', 'id' => 'html']);
                    }
                    ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

            <?php
            $form = ActiveForm::begin(['options' => [
                            'id' => 'ftpgenerate-form-result',
                            'field-class' => 'form-group col-sm-3'
                        ],
                        'validateOnBlur' => FALSE,
//                            'validateOnEnter' => TRUE,
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
            ]);
            ?>   
            <?php if (!empty($result) && !(isset($data['download_only']))) { ?>

                <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="fa fa-search"></i></a>
                    <!--<div onclick="exportThisWithParameter('w11-container', '<?= $this->title ?>')" class="widget_table_search_btn downloadDashboardExcel right_30 mis_custom_report"><i class="fa fa-file-excel-o"></i></div>-->
                    <?php
                    if (is_array($result)) {
                        echo GhostHtml::submitButton(Yii::t('app ', 'Upload File '), ['class' => 'btn btn-default apply-shortcut submit', 'name' => 'ftp-submit', 'value' => 'ftp-submit', 'id' => 'ftp-submit']);
                    }
                    if (is_array($result)) {
                        echo GhostHtml::submitButton(Yii::t('app ', 'Download File'), ['class' => 'btn btn-default apply-shortcut submit', 'name' => 'ftp-submit', 'value' => 'download', 'id' => 'download']);
                    }
                    ?>
                </div>
            <?php } ?>
            <?php
            echo Html::activeHiddenInput($model, 'union_code');
            echo Html::activeHiddenInput($model, 'plant_code');
            echo Html::activeHiddenInput($model, 'mcc_code');
            echo Html::activeHiddenInput($model, 'bmc_code');
            echo Html::activeHiddenInput($model, 'from_date');
            echo Html::activeHiddenInput($model, 'to_date');
            echo Html::activeHiddenInput($model, 'from_shift');
            echo Html::activeHiddenInput($model, 'to_date');
            echo Html::activeHiddenInput($model, 'to_shift');
            echo Html::activeHiddenInput($model, 'status');
            echo Html::activeHiddenInput($model, 'token');
            ?>
            <?php
            if (!empty($result[0]['message'])) {
                $result = '';
                $showPopup = TRUE;
            }
            if (!empty($result) && !is_array($result)) {
                echo "<b><p class='text-center mt-50'>" . $result . "</p></b>";
            } else if (!empty($result) && !(isset($data['download_only']))) {
                $attr = [];
                foreach ($result[0] as $att => $value) {
                    if (!isset($data['select_only']) || !in_array($att, $data['select_only'])) {
                        $attr_arr = [];
                        $attr_arr['attribute'] = $att;
                        $attr[] = $attr_arr;
                    }
                }
                $grid_option = [
                    'id' => 'ftp-data-list',
                    'class' => 'hide-grid-settings hide-grid-search',
                    'attributes' => $attr,
                    'active_column' => false,
                ];

                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], true);
            }
            ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>       

<?php
$script = "
    var popup='" . $showPopup . "';

//    $(document).on('click', '.generate', function() {
//        $('#ftpgenerate-form').submit();
//    });


//    $(document).on('click', '.ftp-submit', function() {
//        $('#ftpgenerate-form-result').submit();
//    });


    if(popup) {
    var status='Force Generate';
        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure to Re-Generate Files ?<br/>Some files may be already generated for given date range.</span></div></div>',
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
                  $('#ftpgenerate-status').val(status);
//                  $('.generate').trigger('click');
//                  $('.generate').trigger('click');
                    $('#ftpgenerate-form').submit();
                    $('#ftpgenerate-form').submit();
                    $('#ftpgenerate-form').submit();
                    $('#ftpgenerate-form').submit();
                }
            }
        });
    }

    ";
$this->registerJs($script, View::POS_END, 'sap-data');
?>

<?php
$baseUrl = Yii::$app->request->baseUrl;
$count = count($fileDownloadArr);
$timeOutForLoader = ($count * 1000) + 2000;


if (!empty($downloadSapFiles)) {
    $scriptDownload = "

    $('#loadercontent').show();
    $('#pageloader').show();
    timeOut = 500;
    var baseUrl = '" . $baseUrl . "/web/FtpUpload/';
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


";
$this->registerJs($scriptDownload, View::POS_READY, 'ftp-upload-file-download');
}

?>