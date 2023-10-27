<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;

$action = Url::to(['sap-upload']);
$downloadSapFiles = json_encode($fileDownloadArr);
$eiplCode = Yii::$app->session->get('eiplCode');
?>
<div class=""></div>

<?php
$form = ActiveForm::begin([
            'id' => 'upload-sap-milk-collection',
//            'action' => $action,
        ]);
?>
<div class="grid-search no-effect" >

    <?php
    echo Html::hiddenInput('operation', 'upload', ['id' => 'set_operation']);

    $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
//                if ($model['coll_count'] != $model['summary_count']) {
//                    return ['disabled' => true, 'class' => 'checkbox-collection', 'value' => $model['union_code'] . '###' . $model['plant_code'] . '###' . $model['mcc_plant_code'] . '###' . $model['bmc_code'] . '###' . $model['dcs_code'] . '###' . $model['date_time_of_collection'] . '###' . $model['shift_id'],];
//                } else {
                return ['class' => 'checkbox-collection', 'value' => $model['union_code'] . '###' . $model['plant_code'] . '###' . $model['mcc_plant_code'] . '###' . $model['bmc_code'] . '###' . $model['dcs_code'] . '###' . $model['date_time_of_collection'] . '###' . $model['shift_id']];
//                }
            }],
            ['attribute' => 'bmc_name', 'filter' => FALSE],
            ['attribute' => 'dcs_code', 'filter' => FALSE],
            ['attribute' => 'dcs_ref_code', 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'filter' => FALSE],
            ['label' => 'Date', 'attribute' => 'date_time_of_collection',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model['date_time_of_collection']);
            }, 'filter' => false],
            ['label' => 'Shift', 'attribute' => 'shift_code', 'filter' => FALSE],
            ['attribute' => 'coll_qty', 'filter' => FALSE],
            ['attribute' => 'coll_count', 'filter' => FALSE],
            ['attribute' => 'summary_qty', 'filter' => FALSE],
            ['attribute' => 'summary_count', 'filter' => FALSE],
            ['attribute' => 'qty_difference', 'filter' => FALSE],
    ];

    $grid_option = [
        'id' => 'sap-upload-milk-collection-list',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
        'actions' => [
            'ftp-upload' => function ($url, $model) use($eiplCode) {
                $class = ($eiplCode == 'DODLA') ? 'link-disable' : '';
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'ftp-uploads', 'title' => 'FTP Uploads', 'data-union_code' => $model['union_code'], 'data-plant_code' => $model['plant_code'], 'data-mcc_plant_code' => $model['mcc_plant_code'], 'data-bmc_code' => $model['bmc_code'], 'data-dcs_code' => $model['dcs_code'], 'data-date_time_of_collection' => $model['date_time_of_collection'], 'data-shift_id' => $model['shift_id'], 'data-qty_difference' => $model['qty_difference'], 'class' => '' . $class];
                return GhostHtml::a_alert('<i class="fa fa-upload"></i>', ['/collection/tbl-milk-collection/dcs-wise-ftp-upload', 'union_code' => $model['union_code'], 'plant_code' => $model['plant_code'], 'mcc_plant_code' => $model['mcc_plant_code'], 'bmc_code' => $model['bmc_code'], 'dcs_code' => $model['dcs_code'], 'date_time_of_collection' => $model['date_time_of_collection']], $options);
            },
            'member-detail' => function ($url, $model) {
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'memberwisedetail', 'title' => 'Member Wise Detail', 'data-union_code' => $model['union_code'], 'data-plant_code' => $model['plant_code'], 'data-mcc_plant_code' => $model['mcc_plant_code'], 'data-bmc_code' => $model['bmc_code'], 'data-dcs_code' => $model['dcs_code'], 'data-date_time_of_collection' => $model['date_time_of_collection']];
                return GhostHtml::a_alert('<i class="fa fa-eye"></i>', ['/collection/tbl-milk-collection/member-wise-detail', 'union_code' => $model['union_code'], 'plant_code' => $model['plant_code'], 'mcc_plant_code' => $model['mcc_plant_code'], 'bmc_code' => $model['bmc_code'], 'dcs_code' => $model['dcs_code'], 'date_time_of_collection' => $model['date_time_of_collection']], $options);
            },
        ]
    ];
    $rowOptions = function ($model) {
        return $model['coll_count'] != $model['summary_count'] ? ['class' => 'danger'] : '';
    };
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false, [], [], true, $rowOptions);
    ?>
</div>
<div class="col-sm-12 margin-top-10 form-group" >
    <?php
    if (!empty($dataProvider->getModels() && $eiplCode != 'DODLA')) {
        echo Html::button(Yii::t('app', 'PUSH To FTP'), ['class' => 'btn btn-primary', 'id' => 'upload', 'value' => 'upload', 'name' => 'upload']);
    }
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'DOWNLOAD'), ['class' => 'btn btn-primary', 'id' => 'download', 'value' => 'download', 'name' => 'download']);
        if ($eiplCode == 'DODLA') {
            echo Html::button(Yii::t('app', 'BULK DOWNLOAD'), ['class' => 'btn btn-primary', 'id' => 'bulk_download', 'value' => 'bulk_download', 'name' => 'bulk_download']);
            echo Html::button(Yii::t('app', 'BULK DOWNLOAD(SHIFT)'), ['class' => 'btn btn-primary', 'id' => 'bulk_download_shift_wise', 'value' => 'bulk_download_shift_wise', 'name' => 'bulk_download_shift_wise']);
        }
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
</div>

<?php ActiveForm::end(); ?>
<div id='member_detail'></div>


<?php
$script = '
    $(".kv-panel-before").hide();
    $("#upload").click(function() {
         var id= $(this).attr("value");
         $("#set_operation").val(id);
            var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
                return false;
            } else {
                $("#upload-sap-milk-collection").submit();
//                location.reload();
            }
    });
      ';

$script .= " 
    $(document).on('click','.memberwisedetail',function(e){
        var trClass = $(this).closest('tr').attr('class');
        var union_code= $(this).attr('data-union_code');
        var plant_code= $(this).attr('data-plant_code');
        var mcc_plant_code= $(this).attr('data-mcc_plant_code');
        var bmc_code= $(this).attr('data-bmc_code');
        var dcs_code= $(this).attr('data-dcs_code');
        var dcs_code= $(this).attr('data-dcs_code');
        var date= $(this).attr('data-date_time_of_collection');
            MemberWiseDetail(union_code,plant_code,mcc_plant_code,bmc_code,dcs_code,date);
    });

    function MemberWiseDetail(union_code,plant_code,mcc_plant_code,bmc_code,dcs_code,date){
        if(union_code != '' && plant_code != '' && mcc_plant_code != '' && bmc_code != '' && dcs_code != ''&& date != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/collection/tbl-milk-collection/member-wise-detail']) . "',
                data: {'union_code' : union_code,'plant_code' : plant_code,'mcc_plant_code' : mcc_plant_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code,'date_time_of_collection' : date},
                beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                },
                success: function(data) {
                    $('#member_detail').html(data);
                    $('#MemberDeleteModal').modal('toggle');              
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
    
$(document).on('click','.ftp-uploads',function(e){
    var union_code = $(this).attr('data-union_code');
    var plant_code= $(this).attr('data-plant_code');
    var mcc_plant_code= $(this).attr('data-mcc_plant_code');
    var bmc_code= $(this).attr('data-bmc_code');
    var dcs_code= $(this).attr('data-dcs_code');
    var date_time_of_collection= $(this).attr('data-date_time_of_collection');
    var shift_id= $(this).attr('data-shift_id');
    var qty_difference= $(this).attr('data-qty_difference');
    var msg='Are you sure you want to Upload File on FTP';
    if(qty_difference != 0){
        msg='here is difference Available in QTY Are you sure you Want to Upload on FTP';
    }

    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>'+msg+'</span></div></div>',
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
              $('#loader').show();
                 $.ajax({
                        type: 'get',
                        url: '" . Url::to(['dcs-wise-ftp-upload']) . "',
                        data:{'union_code':union_code,'mcc_plant_code':mcc_plant_code,'bmc_code':bmc_code,'dcs_code':dcs_code,'date_time_of_collection':date_time_of_collection,'shift_id':shift_id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#sap-upload-milk-collection-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
            }
        }
    });
    });

 ";

$this->registerJs($script, View::POS_END, 'upload-sap-milk-collection');
?>

<?php
$baseUrl = Yii::$app->request->baseUrl;
$count = count($fileDownloadArr);
$timeOutForLoader = ($count * 1000) + 2000;
$scriptDownload = "

$('#download,#bulk_download,#bulk_download_shift_wise').click(function() {
         var id= $(this).attr('value');
         $('#set_operation').val(id);
            var len = $('input[class=\"checkbox-collection kv-row-checkbox\"]:checked').length;
            if(len == 0){
                 bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please select at least one Collection.</span></div></div>');
                return false;
            } else {
                $('#upload-sap-milk-collection').submit();
               
            }
});
";
if (!empty($downloadSapFiles)) {
    $scriptDownload .= "
    var timeOut = 500;
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
        $('#loaderconte nt').hide();
        $('#pageloader').hide();
    }, timeOutForLoader);
    return false;
   ";
}
$this->registerJs($scriptDownload, View::POS_READY, 'mis-report-script-other-download');
?>