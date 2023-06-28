<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\usermanagement\components\GhostHtml;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
$action = Url::to(['bulk-delete']);
?>
<div class=""></div>

<?php
$form = ActiveForm::begin([
            'id' => 'delete-bulk-milk-collection',
//            'action' => $action,
        ]);
?>
<div class="grid-search no-effect" >

    <?php
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                return ['class' => 'checkbox-collection', 'value' => $model['dcs_code'] . '###' . $model['date_time_of_collection']];
            }],
        ['attribute' => 'dcs_code', 'filter' => FALSE],
        ['attribute' => 'dcs_ref_code', 'filter' => FALSE],
        ['attribute' => 'dcs_name', 'filter' => FALSE],
        ['label' => 'Date', 'attribute' => 'date_time_of_collection',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model['date_time_of_collection']);
            }, 'filter' => false],
        ['label' => 'Shift', 'attribute' => 'shift_code', 'filter' => FALSE],
        ['attribute' => 'qty', 'filter' => FALSE],
        ['attribute' => 'fat', 'label' => 'Avg FAT', 'filter' => FALSE],
        ['attribute' => 'snf', 'label' => 'Avg SNF', 'filter' => FALSE],
//        ['attribute' => 'clr', 'filter' => FALSE],
        ['attribute' => 'rtpl', 'label' => 'Avg Rate', 'filter' => FALSE],
        ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
    ];

    $grid_option = [
        'id' => 'delete-bmc-collection-list',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
        'actions' => [
            'member-delete' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'memberwisedelete', 'data-original-title' => 'Member Detail', 'data-union_code' => $model['union_code'], 'data-plant_code' => $model['plant_code'], 'data-mcc_plant_code' => $model['mcc_plant_code'], 'data-bmc_code' => $model['bmc_code'], 'data-dcs_code' => $model['dcs_code'], 'data-date_time_of_collection' => $model['date_time_of_collection']];
                return GhostHtml::a_alert('<i class="fa fa-plus"></i>', ['/collection/tbl-milk-collection/delete-member-wise', 'union_code' => $model['union_code'], 'plant_code' => $model['plant_code'], 'mcc_plant_code' => $model['mcc_plant_code'], 'bmc_code' => $model['bmc_code'], 'dcs_code' => $model['dcs_code'], 'date_time_of_collection' => $model['date_time_of_collection']], $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="col-sm-12 margin-top-10 form-group" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn btn-primary', 'id' => 'delete']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
</div>

<?php ActiveForm::end(); ?>
<div id='member_delete'></div>


<?php
$script = '
    $(".kv-panel-before").hide();
    $("#delete").click(function() {
            var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
                return false;
            } else {
                $("#delete-bulk-milk-collection").submit();
//                location.reload();
            }
    });
      ';

$script .= " 
    $(document).on('click','.memberwisedelete',function(e){
        var trClass = $(this).closest('tr').attr('class');
        var union_code= $(this).attr('data-union_code');
        var plant_code= $(this).attr('data-plant_code');
        var mcc_plant_code= $(this).attr('data-mcc_plant_code');
        var bmc_code= $(this).attr('data-bmc_code');
        var dcs_code= $(this).attr('data-dcs_code');
        var dcs_code= $(this).attr('data-dcs_code');
        var date= $(this).attr('data-date_time_of_collection');
            MemberWiseDelete(union_code,plant_code,mcc_plant_code,bmc_code,dcs_code,date);
    });

    function MemberWiseDelete(union_code,plant_code,mcc_plant_code,bmc_code,dcs_code,date){
        if(union_code != '' && plant_code != '' && mcc_plant_code != '' && bmc_code != '' && dcs_code != ''&& date != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/collection/tbl-milk-collection/delete-member-wise']) . "',
                data: {'union_code' : union_code,'plant_code' : plant_code,'mcc_plant_code' : mcc_plant_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code,'date_time_of_collection' : date},
                beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                },
                success: function(data) {
                    $('#member_delete').html(data);
                    $('#MemberDeleteModal').modal('toggle');              
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
 ";

$this->registerJs($script, View::POS_END, 'delete-bulk-milk-collection');
