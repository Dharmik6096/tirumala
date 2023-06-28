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
    ?>
</div>

<?php
$attribute = [
        ['attribute' => 'purchase_rate_code', 'value' => 'purchase_rate_code',],
        ['attribute' => 'reference_code', 'value' => 'reference_code',],
        ['attribute' => 'dcs_purchase_rate_code'],
        [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
        ['attribute' => 'shift_id', 'value' => 'shiftId.shift',],
        ['attribute' => 'shift_applicability', 'value' => 'shiftApplicability.shift',],
        ['attribute' => 'rate_gen_method_code', 'value' => 'rateMethod.method',],
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false],
    'ts_rate',
    'description',
];

$grid_option = [
    'id' => 'purchase-rate-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'rowcolor' => 'danger',
    'actions' => [
        'view' => true,
//        'update_data' => function ($url, $model) {
//            $disable = ($model->is_active == 0) ? 'disabled' : '';
//            if ($disable == '' && $model->rate_gen_method_code == 3) {
//                $disable = 'disabled';
//            }
//            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $disable];
//
//            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/dcsoperation/tbl-purchase-rate-details/create-rate', 'id' => $model->purchase_rate_code, 'method' => $model->rate_gen_method_code], $options);
//        },
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/dcsoperation/tbl-purchase-rate/purchase-rate-applicability', 'id' => $model->purchase_rate_code], $options);
        },
        'view_rate' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Rate Chart', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-chart-bar" aria-hidden="true"></i>', ['/dcsoperation/tbl-purchase-rate-details/rate-chart', 'id' => $model->purchase_rate_code, 'milk_type' => 1, 'rate_class' => 0, 'milk_quality' => 1], $options);
        },
        'export_rate_chart' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'Export Rate Chart')];
            return GhostHtml::a('<i class="fa fa-download" aria-hidden="true"></i>', ['/dcsoperation/tbl-purchase-rate-details/export-rate-chart', 'id' => $model->purchase_rate_code], $options);
        },
        'deactivate' => function ($url, $model) {
            $active = ($model->is_active == 0) ? FALSE : TRUE;
            $icon_class = $active ? 'fa-close' : 'fa-check';
            $title = $active ? 'Deactivate' : 'Activate';
            $name = $active ? 'Deactivate' : 'Activate';
            $name .= '-' . $model->purchase_rate_code;
            $class = 'deact-rate';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => $title, 'class' => $class, 'data-val' => $model->purchase_rate_code, 'data-name' => $name];
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
