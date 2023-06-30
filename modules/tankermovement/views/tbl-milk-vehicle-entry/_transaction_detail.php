<?php

use yii\web\View;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
?>
<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">

    <?php
    $attribute = [
            ['attribute' => 'entry_type', 'filter' => false],
            ['attribute' => 'grn_no', 'filter' => false],
            ['attribute' => 'challan_no', 'filter' => false],
            ['attribute' => 'source_org_code', 'filter' => false],
            ['attribute' => 'source_org_type', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCodeSource, 'bmc_name') . '-' . strtoupper($model->source_org_type);
            }, 'filter' => false],
            ['attribute' => 'destination_code', 'filter' => false],
            ['attribute' => 'destination_type', 'value' => function($model) {
                $rel = Yii::$app->general->getDestRelation($model->destination_type);
                $att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : 'name');
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) . '-' . strtoupper($model->destination_type);
            }, 'filter' => false],
            ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'chamber_no', 'filter' => false],
            ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'clr', 'value' => 'clr', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'chamber_quantity', 'value' => 'chamber_quantity', 'vAlign' => 'middle', 'filter' => false],
        'water',
        'temp',
        'clr',
        'protein',
        'density',
        'lactose',
        'freezing_point',
        'mbrt',
        'acidity'
    ];

    $grid_option = [
        'id' => 'milk-vehicle-txn-list',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'actions' => [
            'view-config' => function ($url, $model) {
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-config', 'title' => 'View Config Input', 'data-val' => $model->milk_vehicle_entry_transaction_code];
                return GhostHtml::a_alert('<i class="fa fa-eye"></i>', ['/tankermovement/tbl-milk-vehicle-entry/view-config', 'id' => $model->milk_vehicle_entry_transaction_code], $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
<div id='config_detail_view'></div>
<?php
$script = "$(document).ready(function(){
    $(document).on('click','.view-config',function(e){
    var id= $(this).attr('data-val');
  ViewConfig(id);
    });
    function ViewConfig(code){
        if(code != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/tankermovement/tbl-milk-vehicle-entry/view-config']) . "',
                data: {'id' : code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#config_detail_view').html(data);
                   $('#ConfigModal').modal('toggle');              
                   $('#loadercontent').hide();
                   $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        }
    }
});";
$this->registerJs($script, View::POS_END, 'receipt-config-popup');
?>
