<?php

use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Delete Map Route Source');
$action = Url::to(['bulk-delete']);
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'delete-map-route',
                'action' => $action,
    ]);
    ?>
    <?= Html::activeHiddenInput($searchModel, 'customer_type'); ?>
    <div class="">

        <?php
        if (empty($searchModel->customer_type) || $searchModel->customer_type == 'DCS') {
            $attribute = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) {
                        return ['class' => 'checkbox', 'value' => $model['route_code'] . '###' . $model['dcs_code']];
                    }],
                ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->routeMapping, 'route_code_ex');
                    }, 'filter' => false],
                ['attribute' => 'route_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->routeMapping, 'route_code_ex');
                    }, 'filter' => false],
                ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Name'),
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->routeMapping, 'route_name');
                    }, 'filter' => false],
                ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code'), 'filter' => false],
                ['attribute' => 'dcs_code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'filter' => false],
                ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'Name'), 'filter' => false],
            ];
        } else {
            $attribute = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) {
                        return ['class' => 'checkbox', 'value' => $model['customer_code']];
                    }],
                ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->routeCode, 'route_code_ex');
                    }, 'filter' => false],
                ['attribute' => 'route_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->routeCode, 'route_code_ex');
                    }, 'filter' => false],
                ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Name'),
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
                    }, 'filter' => false],
                ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code'), 'filter' => false],
                ['attribute' => 'customer_code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'filter' => false],
                ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'filter' => false],
            ];
        }

        $grid_option = [
            'id' => 'delete-map-route-list',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn btn-primary', 'id' => 'delete']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'delete-map-route','','btn-login'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#delete").click(function() {
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Route Source.</span></div></div>");
                return false;
            } else {
            $("#delete-map-route").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'delete-map-route');
