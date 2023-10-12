<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>
<div class="grid-search">
    <?php
//        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'payment_head_name'],
//    ['attribute' => 'transporter_code'],
    ['attribute' => 'payment_head_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('payment_head_type', $searchModel), 'value' => function($model) {
            return (Yii::$app->dropdown->getRecords('payment_head_type')['data'][$model->payment_head_type] != '') ? Yii::$app->dropdown->getRecords('payment_head_type')['data'][$model->payment_head_type] : '';
        },],
    ['attribute' => 'sequence_no'],
];

$grid_option = [
    'id' => 'payment-head-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => function ($url, $model) {
            $name = $model->payment_head_name;
            $class = $model->is_active == 0 ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->payment_head_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        'deactive' => function ($url, $model) {
            $name = $model->payment_head_name;
            $class = $model->is_active == 0 ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deact-party ' . $class, 'data-val' => $model->payment_head_code, 'data-name' => $name];
            return GhostHtml::a_alert('<i class="fa fa-close"></i>', ['/tankermovement/tbl-payment-head/deactivate-payment-head'], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.deact-party',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to deactivate \"'+name+'\"?</span></div></div>',
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
                        url: '" . Url::to(['deactivate-payment-head']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#payment-head-list'});
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
});";
$this->registerJs($script, View::POS_END, 'payment-head-list');
?>
