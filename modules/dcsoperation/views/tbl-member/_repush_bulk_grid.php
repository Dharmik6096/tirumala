<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="grid-searchno-effect" >
    <?php
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                $disabled = $model['data_post_status'] == 0 ? true : false;
                return ['class' => 'checkbox-collection', 'value' => $model['member_code'], 'disabled' => $disabled];
            }],
        ['attribute' => 'union_code', 'filter' => false, 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
            }, 'visible' => FALSE],
        ['attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        ['attribute' => 'ex_member_code', 'filter' => false],
        ['attribute' => 'sap_farmer_code', 'filter' => false],
        ['attribute' => 'surname', 'filter' => false],
        ['attribute' => 'gender_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->genderCode, 'gender');
            }, 'filter' => false],
        ['attribute' => 'address', 'value' => 'address', 'filter' => false],
        ['attribute' => 'ref_code', 'label' => Yii::t('app', 'BMC Ref Code'), 'value' => function($model) {
                return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['bmcCode'], 'ref_code');
            }, 'filter' => false],
        ['attribute' => 'ref_code', 'label' => Yii::t('app', 'DCS Ref Code'),
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            }, 'filter' => false],
        ['attribute' => 'bank_account_no', 'filter' => false],
        ['attribute' => 'ifsc', 'filter' => false],
        ['attribute' => 'branch_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->branchCode, 'branch_name');
            }, 'filter' => false],
        ['attribute' => 'account_holder_name', 'filter' => false],
        ['attribute' => 'mobile_no', 'filter' => false],
        ['attribute' => 'registration_date', 'value' => function($model) {
                return Yii::$app->controls->view_date($model->registration_date);
            }, 'filter' => false],
        ['attribute' => 'is_active', 'label' => Yii::t('app', 'Status'), 'filter' => false,
            'value' => function($model) {
                return $model->is_active == '1' ? (Yii::$app->general->getforeignkey($model->activeStatus, 'is_active') === 0 ? 'In Active' : 'Active') : 'In Active';
            },],
        ['attribute' => 'data_post_status', 'value' => function($model) {
                return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status] : Yii::$app->dropdown->getRecords('send_status')['data'][0];
            }, 'filter' => false],
        ['attribute' => 'picked_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->picked_datetime);
            }],
        ['attribute' => 'response_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->response_datetime);
            }],
        ['attribute' => 'resp_status', 'filter' => false],
        ['attribute' => 'resp_desc', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'member-list',
        'attributes' => $attribute,
        'active_column' => false,
        'actions' => [
            'view' => FALSE,
        ],
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
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
$script = '
    $("#bipl-repush-bulk").click(function() {
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
             bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
                return false;
            } else {
            $.ajax({
                type: "POST",
                url: "' . Url::to(['/dcsoperation/tbl-member/repush-bulk-data']) . '",
                data: {selection: $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").map(function() { return this.value; }).get()},
                success: function(data) {
                    // Handle response data
                }
            });
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'repush-member-bulk');
