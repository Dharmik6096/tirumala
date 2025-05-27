<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => true],
    ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => true],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'vAlign' => 'middle',
        'filter' => false
    ],
    ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        },
        'vAlign' => 'middle',
        'filter' => false
    ],
    ['attribute' => 'party_type', 'visible' => true, 'filter' => true],
    ['attribute' => 'party_code', 'visible' => true, 'filter' => true],
    ['attribute' => 'ref_code', 'visible' => true, 'filter' => true],
    ['attribute' => 'party_name', 'visible' => true, 'filter' => true],

];


$grid_option = [
    'id' => 'general-party-master-list-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'view' => FALSE,
        'update' => function ($url, $model) {
            $class = ($model->party_type == 'EMPLOYEE') ? '' : 'disabled';
            $options = ['data-code' => $model->general_party_master_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Update', 'class' => $class,];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/product/tbl-general-party-master/update', 'id' => $model->general_party_master_code], $options);
        },
        'deactive' => function ($url, $model) {
            $name = $model->party_name;
            if ($model->is_active == 1) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deact-escalation', 'data-val' => $model->general_party_master_code, 'data-name' => $name, 'class' => 'delete-record '];
                return GhostHtml::a_alert('<i class="fa fa-close"></i>', ['/product/tbl-general-party-master/delete','general_party_master_code' => $model->general_party_master_code], $options);
            } else {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Activate', 'class' => 'react-escalation', 'data-val' => $model->general_party_master_code, 'data-name' => $name];
                return GhostHtml::a_alert('<i class="fa fa-check"></i>', ['javascript:void(0);'], $options);
            }
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);

$script = " 

$(document).on('click','.delete-record',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Deactive \"'+name+'\"?</span></div></div>',
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
                    type: 'post',
                    url: '" . Url::to(['/product/tbl-general-party-master/delete']) . "',
                    data: 'id='+id,
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        console.log(obj1);
                        if (obj1.status == 'success')
                        {
                            $.pjax.reload({container: '#general-party-master-list-grid'});
                            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                        }
                        else if (obj1.status == 'error'){
                            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                        }
                    },
                    error:function(data){
                    }
                });
            }
        }
    });
})";
$this->registerJs($script, View::POS_END, 'party-script');
?>