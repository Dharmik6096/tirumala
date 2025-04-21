<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>
<div class="grid-search no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'data-exchange-log',
                'method' => 'get',
    ]);
    ?>

    <?php
    $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model, $key, $index) {
                $code = $model['data_exchange_log_code'];
                $disabled = ($model->data_post_status == 2) ? true : false;
                return ['class' => 'checkbox-collection', 'value' => $code, 'disabled' => $disabled];
            }],
            ['attribute' => 'process_name'],
            ['attribute' => 'process_code', 'value' => function($model) {
                if (strtolower($model->process_name) == 'member provisional') {
                    return Yii::$app->general->getforeignkey($model->memberProvisionalCode, 'member_name');
                } else {
                    return Yii::$app->general->getforeignkey($model->memberProvisionalFamilyDetailCode, 'family_member_name');
                }
            }, 'filter' => false, 'label' => 'name'],
            ['attribute' => 'resp_param_1'],
            ['attribute' => 'resp_param_2'],
            ['attribute' => 'resp_param_3'],
            ['attribute' => 'resp_param_4'],
            ['attribute' => 'resp_param_5'],
            ['attribute' => 'resp_param_6'],
        // ['attribute' => 'update_key'],
        [
            'attribute' => 'picked_datetime',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->picked_datetime);
            }],
            [
            'attribute' => 'response_datetime',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->response_datetime);
            }],
            ['attribute' => 'resp_desc'],
            ['attribute' => 'resp_status'],
            ['attribute' => 'data_post_status', 'value' => function ($model) {
                return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status] : '';
            }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('send_status', $searchModel, 'data_post_status')],
    ];

    $grid_option = [
        'id' => 'data-exchange-grid',
        'attributes' => $attribute,
        'active_column' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
<div class="panel-footer" >
    <?php
    if (!empty($dataProvider->getModels())) {
        ?>
        <?= Html::button(Yii::t('app', 'Re Push'), ['class' => 'btn btn-primary', 'id' => 'repush']); ?> 
    <?php }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#repush").click(function() {
        var id = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").map(function() {
            return $(this).val();
        }).get();
        
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Log.</span></div></div>");
                return false;
            } 
                $.ajax({
                    url: "' . Url::to(['repush']) . '",
                    type: "POST",
                    data: { selection: id },
                    success: function(response) {
                        bootbox.alert("Data Exchange Log Sucessfully Deleated.");
                    },
                    error: function(xhr) {
                        bootbox.alert("Something went wrong while re-pushing.");
                    }
                });                        
        });
       ';
$this->registerJs($script, View::POS_END, 'data-exchange-log-js');
