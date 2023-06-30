<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>
<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', $title));
?>
<div class="tbl-dcs-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <div>
            <?php
            $attribute = [
                ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false],
                ['attribute' => 'purchase_rate_code', 'filter' => false],
                ['attribute' => 'rate_description', 'value' => 'purchaseRateCode.description', 'filter' => false],
                [
                    'attribute' => 'wef_date', 'filter' => false,
                    'filterType' => GridView::FILTER_DATE,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['format' => 'dd-mm-yyyy',
                            'autoclose' => true]
                    ],
                    'value' => function($model) {
                return Yii::$app->controls->view_date($model->wef_date);
            },],
                ['attribute' => 'shift_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
                    },
                    'filter' => false,],
            ];

            $grid_option = [
                'id' => 'dcs-rate-list',
                'attributes' => $attribute,
                'active_column' => FALSE,
                'actions' => [
                    'update-rate-status' => function ($url, $model) {
                        $name = $model->purchase_rate_code . '-' . $model->purchaseRateCode->description;
                        $icon_class = ($model->is_active == 1) ? 'fa-close' : 'fa-check';
                        $title = ($model->is_active == 1) ? 'Block' : 'Un-Block';
                        $name = ($model->is_active == 1) ? 'Block ' . $name : 'Un-Block ' . $name;
                        $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => $title, 'class' => 'deact-rate', 'data-val' => $model->rate_app_code, 'data-name' => $name];
                        return GhostHtml::a_alert('<i class="fa ' . $icon_class . '""></i>', $url, $options);
                    }
                        ]
                    ];

                    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                    ?>


                </div>
            </div>
        </div>
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
                        url: '" . Url::to(['update-rate-status']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#dcs-rate-list'});
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
        $this->registerJs($script, View::POS_END, 'dcs-rate-status-update');
        