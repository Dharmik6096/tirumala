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
    ['attribute' => 'tanker_rate_code', 'value' => 'tanker_rate_code',],
    ['attribute' => 'rate_gen_method_code', 'value' => function ($model) {
           return isset(Yii::$app->dropdown->getRecords('tanker_rate_gen_method')['data'][$model->rate_gen_method_code]) ? Yii::$app->dropdown->getRecords('tanker_rate_gen_method')['data'][$model->rate_gen_method_code] : '';
        },],
    ['attribute' => 'rate_for', 'value' => function ($model) {
      
            return isset(Yii::$app->dropdown->getRecords('tanker_rate_for')['data'][$model->rate_for]) ? Yii::$app->dropdown->getRecords('tanker_rate_for')['data'][$model->rate_for] : '';  
    },],
    [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'shift_code', 'value' => 'shiftCode.shift',],
    'description',
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false, 'visible' => false],
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
//            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => $disable];
//
//            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/tankermovement/tbl-tanker-rate-details/create-rate', 'id' => $model->tanker_rate_code, 'method' => $model->rate_gen_method_code], $options);
//        },
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/tankermovement/tbl-tanker-rate-applicability/create', 'id' => $model->tanker_rate_code], $options);
        },
        'view_rate' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            if ($disable == '' && $model->rate_gen_method_code == 1) {
                $disable = 'disabled';
            }
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View Rate Chart', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-chart-bar" aria-hidden="true"></i>', ['/tankermovement/tbl-tanker-rate-details/rate-chart', 'id' => $model->tanker_rate_code, 'milk_type' => 1, 'milk_quality' => 1], $options);
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
