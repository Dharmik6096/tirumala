<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;

$attribute = [
    ['label' => Yii::t('app', 'Union'), 'filter' => false, 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'MCC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode', 'unionCode'], 'union_name');
        } elseif ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode', 'unionCode'], 'union_name');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode', 'unionCode'], 'union_name');
        }
        return $code;
    }, 'visible' => FALSE],
    ['label' => Yii::t('app', 'PLANT'), 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'MCC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode', 'plantCode'], 'name');
        } elseif ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode', 'plantCode'], 'name');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode', 'plantCode'], 'name');
        }
        return $code;
    }, 'filter' => FALSE, 'visible' => FALSE],
    ['label' => Yii::t('app', 'MCC'), 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'MCC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode'], 'name');
        } elseif ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode', 'tblMccPlant'], 'name');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode', 'mccPlantCode'], 'name');
        }
        return $code;
    }, 'filter' => FALSE, 'visible' => TRUE],
    ['label' => Yii::t('app', 'MCC') . ' Code', 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'MCC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode'], 'mcc_plant_code');
        } elseif ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode'], 'mcc_plant_code');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode'], 'mcc_plant_code');
        }
        return $code;
    }, 'filter' => FALSE, 'visible' => TRUE],
    ['label' => Yii::t('app', 'MCC') . ' Ref Code', 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'MCC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode'], 'ref_code');
        } elseif ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode', 'tblMccPlant'], 'ref_code');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode', 'mccPlantCode'], 'ref_code');
        }
        return $code;
    }, 'filter' => FALSE, 'visible' => TRUE],
    ['label' => Yii::t('app', 'BMC'), 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode'], 'bmc_name');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode', 'bmcCode'], 'bmc_name');
        }
        return $code;
    }, 'filter' => FALSE, 'visible' => FALSE],
    ['label' => Yii::t('app', 'BMC') . ' Code', 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode'], 'bmc_code');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode'], 'bmc_code');
        }
        return $code;
    }, 'filter' => FALSE, 'visible' => FALSE],
    ['label' => Yii::t('app', 'BMC') . ' Ref Code', 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode'], 'ref_code');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode', 'bmcCode'], 'ref_code');
        }
        return $code;
    }, 'filter' => FALSE, 'visible' => FALSE],
    ['label' => Yii::t('app', 'Org. Type'), 'attribute' => 'organization_type', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
    }, 'filter' => TRUE],
    ['label' => Yii::t('app', 'Org. Code'), 'attribute' => 'code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_code');
    }, 'filter' => true],
    ['label' => Yii::t('app', 'Org. Ref Code'), 'attribute' => 'refCode', 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $code = '';
        if ($type == 'MCC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode'], 'ref_code');
        } elseif ($type == 'BMC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode'], 'ref_code');
        } elseif ($type == 'VLC') {
            $code = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode'], 'ref_code');
        }
        return $code;
    }, 'filter' => true],
    ['label' => Yii::t('app', 'Org. Name'), 'attribute' => 'organization_code', 'value' => function ($model) {
        $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        $name = '';
        if ($type == 'MCC') {
            $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode'], 'name');
        } elseif ($type == 'BMC') {
            $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode'], 'bmc_name');
        } elseif ($type == 'VLC') {
            $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode'], 'dcs_name');
        }
        return $name;
    }, 'filter' => TRUE],
    ['attribute' => 'mobile_no'],
    ['attribute' => 'password'],
    [
        'attribute' => 'password_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->password_date);
        },
        'visible' => FALSE
    ],
    ['attribute' => 'device_id'],
    ['attribute' => 'version_no'],
    [
        'attribute' => 'db_version',
        'label' => Yii::t('app', 'DB Version'),
        'filter' => FALSE,
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('db_version')['data'][$model->db_version]) ? Yii::$app->dropdown->getRecords('db_version')['data'][$model->db_version] : $model->db_version;
        },
    ],
    [
        'attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_datetime($model->created_at);
        }
    ],
    [
        'attribute' => 'installation_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('installation_type', $searchModel, 'installation_type'),
        'value' => function ($model) {
            return isset($model->installation_type) ? Yii::$app->dropdown->getRecords('installation_type')['data'][$model->installation_type] : '';
        },
    ],
    [
        'attribute' => 'sync_active',
        'label' => Yii::t('app', 'Sync status'),
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('status', $searchModel, 'sync_active'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('status')['data'][$model->sync_active]) ? Yii::$app->dropdown->getRecords('status')['data'][$model->sync_active] : $model->sync_active;
        },
    ],
];

$grid_option = [
    'id' => 'android-installation-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'download' => function ($url, $model) {
            $class = $model->installation_type == 1 ? '' : ' disabled ';
            $options = ['title' => Yii::t('app', 'Download'), 'class' => $class];
            $path = $model->db_path;
            return GhostHtml::a('<i class="fa fa-download"></i>', ['/installation/tbl-android-installation/download', 'id' => Yii::$app->basePath . $path], $options);
        },
        'deactive' => function ($url, $model) {
            $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
            $name = '';
            if ($type == 'MCC') {
                $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode'], 'name');
            } elseif ($type == 'BMC') {
                $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode'], 'bmc_name');
            } elseif ($type == 'VLC') {
                $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode'], 'dcs_name');
            }
            $icon = '<i class="fa fa-times"></i>';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Activate', 'title' => Yii::t('app', 'Deactivate'), 'class' => 'deactivate-identity', 'data-val' => $model->android_installation_details_id, 'data-name' => $name];
            return GhostHtml::a_alert($icon, ['/installation/tbl-android-installation/deactivate-identity'], $options);
        },
        'generate-password' => function ($url, $model) {
            $class = 'android-pwd';
            $Disableclass = $model->password_date == date('Y-m-d') ? 'disabled' : '  ';
            $code = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_code');
            $type = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
            $name = '';
            if ($type == 'MCC') {
                $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['mccCode'], 'name');
            } elseif ($type == 'BMC') {
                $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['bmcCode'], 'bmc_name');
            } elseif ($type == 'VLC') {
                $name = Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode'], 'dcs_name');
            }
            $options = ['title' => 'Generate password', 'class' => $class . ' ' . $Disableclass, 'data-val' => $model->android_installation_details_id, 'data-code' => $code, 'data-name' => $name, 'data-type' => $type];
            return GhostHtml::a_alert('<i class="fa fa-key"></i>', ['/installation/tbl-android-installation/android-password'], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.deactivate-identity',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
    message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to deactivate Identity For \"'+name+'\"?</span></div></div>',buttons: {
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
                        url: '" . Url::to(['deactivate-identity']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#android-installation-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
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
 
});";
$this->registerJs($script, View::POS_END, 'member-index');
?>
<?php

$script = "
  $(document).ready(function(){
    $(document).on('click','.android-pwd',function(e){
    var id= $(this).attr('data-val');
    var code= $(this).attr('data-code');
    var name = $(this).attr('data-name');
    var type = $(this).attr('data-type');
    bootbox.confirm({
    message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Generate password of \"'+name+'\"?</span></div></div>',
    buttons: {
            'cancel': {
                            label: '" . Yii::t('app', 'Cancel') . "',
                            className: 'btn btn-default'
              },
            'confirm': {
                           label: '" . Yii::t('app', 'OK') . "',
                           className: 'btn btn-default',
             }
        },
        callback: function(result) {
            if (result) {
                 $.ajax({
                        type: 'post',
                        url: '" . Url::to(['android-password']) . "',
                        data:{'id':id,'type':type,'code':code,'name':name},
                        success: function(data) {     
                         var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                            
                                bootbox.confirm({
                                   message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+obj1.msg+'</span></div></div>',
                                   buttons: {
                                        'cancel': {
                                                        label: '" . Yii::t('app', 'Cancel') . "',
                                                        className: 'btn btn-default disp_none'
                                          },
                                           'confirm': {
                                                          label: '" . Yii::t('app', 'OK') . "',
                                                          className: 'btn btn-default',
                                            }
                                       },
                                       callback: function(result) {
                                                 $.pjax.reload({container: '#android-installation-list'});
                                       }
                                   });

//                                $.pjax.reload({container: '#android-installation-list'});
//                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                        },                       
            });
            }
        }
    });
    });
});";
$this->registerJs($script, View::POS_END, 'android-pwd');
?>