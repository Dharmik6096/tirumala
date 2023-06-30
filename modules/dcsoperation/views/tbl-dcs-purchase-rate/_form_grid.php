<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="grid-search clearfix">
    <?php
    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
        echo $this->render('_search', ['model' => $searchModel]);
    ?></div>

<?php
$attribute = [
        ['attribute' => 'purchase_rate_code', 'value' => 'purchase_rate_code',],
        ['attribute' => 'reference_code', 'value' => 'reference_code',],
        [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'width' => '200px',
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
        ['attribute' => 'shift_id',
        'filter' => false,
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftId, 'shift');
        },],
        ['attribute' => 'shift_applicability',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftApplicability, 'shift');
        },],
        ['attribute' => 'rate_gen_method_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->rateMethod, 'method');
        },],
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
        ['attribute' => 'for_member', 'value' => function($model) {
            return $model->for_member == 1 ? 'YES' : 'NO';
        }, 'filter' => false],
    'ts_rate',
    'description',
        ['attribute' => 'rate_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('rate_price_type', $searchModel, 'rate_type'),
        'value' => function ($model) {
            return !empty($model->rate_type) ? Yii::$app->dropdown->getRecords('rate_price_type')['data'][$model->rate_type] : '';
        }, 'visible' => false],
        ['attribute' => 'rate_value', 'visible' => false],
];

$grid_option = [
    'id' => 'purchase-rate-grid',
    'attributes' => $attribute,
    'active_column' => false,
    //'rowcolor' => 'danger',
    'rowOptions' => function ($model) {
        $rowcolor = '';
        if ($model->flg_sentbox_entry == 'E') {
            $rowcolor = 'danger';
        }
        return ['class' => $rowcolor];
    },
    'actions' => [
        'view' => true,
//                'update_data' => function ($url, $model) {
//                    $disable = ($model->is_active == 0) ? 'disabled' : '';
//                    if ($disable == '') {
//                        $disable = (strtoupper($model->originating_org_type) == 'UNION') ? '' : 'disabled';
//                        if ($model->rate_gen_method_code == 3 || $model->flg_sentbox_entry == 'Y') {
//                            $disable = 'disabled';
//                        }
//                    }
//                    $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
//                    return GhostHtml::a('<span class="fa fa-pencil-alt"></span>', ['/dcsoperation/tbl-dcs-purchase-rate-details/create-rate', 'id' => $model->purchase_rate_code, 'method' => $model->rate_gen_method_code], $options);
//                },
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/dcsoperation/tbl-dcs-purchase-rate/purchase-rate-applicability', 'id' => $model->purchase_rate_code], $options);
        },
        'view_rate' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['title' => Yii::t('app', 'Rate Chart'), 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View Rate Chart', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-chart-bar" aria-hidden="true"></i>', ['/dcsoperation/tbl-dcs-purchase-rate-details/rate-chart', 'id' => $model->purchase_rate_code, 'milk_type' => 1, 'milk_quality' => 1], $options);
        },
        'export_rate_chart' => function ($url, $model) {
            $options = ['title' => Yii::t('app', 'Export Rate Chart'),];
            return GhostHtml::a('<i class="fa fa-download" aria-hidden="true"></i>', ['/dcsoperation/tbl-dcs-purchase-rate-details/export-rate-chart', 'id' => $model->purchase_rate_code], $options);
        },
        'deactivate' => function ($url, $model) {
            $active = ($model->is_active == 0) ? FALSE : TRUE;
            $icon_class = $active ? 'fa-close' : 'fa-check';
            $title = $active ? 'Deactivate' : 'Activate';
            $name = $active ? 'Deactivate' : 'Activate';
            $name .= '-' . $model->purchase_rate_code;
            $class = 'deact-rate';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => $title, 'class' => $class, 'data-val' => $model->purchase_rate_code, 'data-name' => $name];
            return GhostHtml::a_alert('<i class="fa ' . $icon_class . '""></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php
$script = "
$(document).ready(function(){
    $(document).on('click','.deact-rate',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to  \"'+name+'\"?</span></div></div>',
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
                        url: '" . Url::to(['active-deactivate']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#purchase-rate-grid'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
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
});


";
$this->registerJs($script, View::POS_END, 'dcs-rate-avtivate-deactivate');
