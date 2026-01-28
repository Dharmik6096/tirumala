<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="grid-searchno-effect" >
    <?php
    $isVisible = $searchModel->bipl_type == '1';
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                $disabled = $model['data_post_status'] == 0 ? true : false;
                $value = $model['code'];
                return ['class' => 'checkbox-collection', 'value' => $value, 'disabled' => $disabled];
            }],
        ['attribute' => 'union_name', 'filter' => false],
        ['attribute' => 'code', 'filter' => false],
        ['attribute' => 'ex_code', 'filter' => false],
        ['attribute' => 'name', 'filter' => false],
        ['attribute' => 'sap_vendor_code', 'filter' => false],
        ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS Ref Code'), 'filter' => false],
        ['attribute' => 'bmc_ref_code', 'label' => Yii::t('app', 'BMC Ref Code'), 'filter' => false],
        ['attribute' => 'route_ref_code', 'label' => Yii::t('app', 'Route Ref Code'), 'filter' => false, 'visible' => $isVisible],
        ['attribute' => 'sap_route_code', 'filter' => false, 'visible' => $isVisible],
        ['attribute' => 'mobile_no', 'filter' => false],
        ['attribute' => 'is_active', 'label' => Yii::t('app', 'Status'), 'filter' => false,
            'value' => function($model) {
                return $model['is_active'] == '1' ? 'Active' : 'In Active';
            }],
        ['attribute' => 'effective_date'],
        ['attribute' => 'data_post_status', 'filter' => false],
        ['attribute' => 'last_name', 'filter' => false, 'visible' => !$isVisible],
        ['attribute' => 'gender', 'filter' => false, 'visible' => !$isVisible],
        ['attribute' => 'address', 'filter' => false, 'visible' => !$isVisible],
        ['attribute' => 'picked_datetime'],
        ['attribute' => 'response_datetime'],
        ['attribute' => 'resp_status', 'filter' => false],
        ['attribute' => 'resp_desc', 'filter' => false],
    ];

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
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Repush Bulk'), ['class' => 'btn btn-primary', 'id' => 'bipl-repush-bulk']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'repush-bulk-data'); ?>
</div>
<?php
$type = $isVisible ? 'dcs' : 'member';
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
