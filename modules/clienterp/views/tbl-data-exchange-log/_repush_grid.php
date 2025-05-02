<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use app\modules\dcsoperation\models\TblMemberProvisional;
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
                $disabled = in_array($model['data_post_status'], [0, 2]) ? true : false;
                return ['class' => 'checkbox-collection', 'value' => $code, 'disabled' => $disabled];
            }],
            ['attribute' => 'application_no'],
            ['attribute' => 'code'],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Dcs Code Ex'), 'value' => function($model) {
                $member = new TblMemberProvisional();
                $dcsData = $member->getDcs($model['dcs_code']);
                return !empty($dcsData) ? $dcsData->dcs_code_ex : '';
            }],
            ['attribute' => 'name', 'filter' => false],
            ['attribute' => 'resp_param_1', 'label' => Yii::t('app', 'Mandt')],
            ['attribute' => 'resp_param_2', 'label' => Yii::t('app', 'Crnam')],
            ['attribute' => 'resp_param_3', 'label' => Yii::t('app', 'Created at')],
            ['attribute' => 'resp_param_4', 'label' => Yii::t('app', 'Crzet')],
            ['attribute' => 'resp_param_5', 'label' => Yii::t('app', 'Adhar no')],
            ['attribute' => 'resp_param_6', 'label' => Yii::t('app', 'Account no')],
            [
            'attribute' => 'picked_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model['picked_datetime']);
            }],
            [
            'attribute' => 'response_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model['response_datetime']);
            }],
            ['attribute' => 'resp_desc'],
            ['attribute' => 'resp_status'],
    ];

    $grid_option = [
        'id' => 'data-exchange-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'actions' => [
            'views' => function($url, $model) {
                if (strtolower($model['process_name']) == 'member provisional') {
                    $label = 'Provisional Member View';
                    $url = '/dcsoperation/tbl-member-provisional/view';
                } else {
                    $label = 'Provisional Member Family Detail View';
                    $url = '/clienterp/tbl-data-exchange-log/view-family';
                }
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => $label];
                return Html::a('<i class="fa fa-eye"></i>', [$url, 'id' => $model['code']], $options);
            },
            'view_history' => function($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Data Exchange Log History View'];
                return Html::a('<i class="fa fa-history"></i>', ['/clienterp/tbl-data-exchange-log/view-history', 'id' => $model['process_code']], $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php
    $models = $dataProvider->getModels();
    $status = !empty($models) ? array_values(array_filter(array_column($models, 'data_post_status'))) : [];
    if (!empty($dataProvider->getModels()) && !empty($status) && !in_array(0, $status) && !in_array(2, $status)) {
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
                      //  bootbox.alert("Something went wrong while re-pushing.");
                    }
                });                        
        });
       ';
$this->registerJs($script, View::POS_END, 'data-exchange-log-js');
