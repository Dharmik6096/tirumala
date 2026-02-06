<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="report-area" >
    <?php
    $models = $dataProvider->getModels();
    $firstModel = !empty($models) ? $models[0] : [];

    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                $disabled = $model['data_post_status'] == 0 ? true : false;
                $value = !empty($model['member_code']) ? $model['member_code'] : (!empty($model['rate_app_code']) ? $model['rate_app_code'] : (!empty($model['purchase_rate_code']) ? $model['purchase_rate_code'] : $model['dcs_code']));
                return ['class' => 'checkbox-collection', 'value' => $value, 'disabled' => $disabled];
            }],
    ];

    if (!empty($firstModel)) {
        foreach ($firstModel as $field => $value) {
            $columnConfig = ['attribute' => $field, 'filter' => false];
            if ($field === 'is_active') {
                $columnConfig['label'] = Yii::t('app', 'Status');
                $columnConfig['value'] = function($model) {
                    return $model['is_active'] == '1' ? 'Active' : 'In Active';
                };
            } elseif ($field == 'dcs_ref_code') {
                $columnConfig['label'] = Yii::t('app', 'DCS') . Yii::t('app', ' Ref Code');
            } elseif ($field == 'data_post_status') {
                $columnConfig['value'] = function($model) {
                    return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model['data_post_status']]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model['data_post_status']] : 'Pending';
                };
            } elseif ($field == 'picked_datetime') {
                $columnConfig['value'] = function($model) {
                    return Yii::$app->controls->view_datetime($model['picked_datetime'], 'php:d-m-Y H:i:s');
                };
            } elseif ($field == 'response_datetime') {
                $columnConfig['value'] = function($model) {
                    return Yii::$app->controls->view_datetime($model['response_datetime'], 'php:d-m-Y H:i:s');
                };
            }
            $attribute[] = $columnConfig;
        }
    }

    $grid_option = [
        'id' => 'bipl-smart-list',
        'attributes' => $attribute,
        'active_column' => false,
        'default_sorting' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['repush-bulk-data']);
    ?>
</div>

<div class="col-sm-12 form-group" >
    <?= Html::button(Yii::t('app', 'Repush Bulk'), ['class' => 'btn btn-primary btn-login', 'id' => 'bipl-repush-bulk']); ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'repush-bulk-data', '', 'btn-login'); ?>
</div>
<?php
$type = $searchModel->bipl_type == '1' ? 'dcs' : ($searchModel->bipl_type == '2' ? 'rate' : ($searchModel->bipl_type == '3' ? 'rateapp' : 'member'));
$script = '
    $("#bipl-repush-bulk").click(function() {
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
        if(len == 0){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: "' . Url::to(['/dcsoperation/tbl-bipl-smart/repush-bulk-data']) . '",
                data: {
                    selection: $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").map(function() { 
                        return this.value; 
                    }).get(), type:"' . $type . '"
                },
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == \'success\'){
                        $.pjax.reload({container: "#bipl-smart-list"});
                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.msg+"</span></div></div>");
                    } else if (obj1.status == \'error\'){
                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+obj1.msg+"</span></div></div>");
                    }
                }
            });
        }
    });
';

$this->registerJs($script, View::POS_END, 'repush-bipl-smart-bulk1');
