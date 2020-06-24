<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Delete Map Route');
$action = Url::to(['bulk-delete']);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>

        </div>
        <div class="clearfix"></div>
        <div class="grid-search no-effect" >
            <?php
            $form = ActiveForm::begin([
                        'id' => 'delete-map-route',
                        'action' => $action,
            ]);
            ?>

            <?php
            $attribute = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) {
                        return ['class' => 'checkbox', 'value' => $model['route_mapping_source_code']];
                    }],
                ['attribute' => 'from_dest', 'label' => Yii::t('app', 'DCS Code'), 'filter' => false],
                ['attribute' => 'from_dest', 'label' => Yii::t('app', 'DCS Name'),
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->societyCode, 'dcs_name');
                    }, 'filter' => false],
            ];

            $grid_option = [
                'id' => 'delete-map-route-list',
                'attributes' => $attribute,
                'active_column' => false,
                'showPageSummary' => false,
            ];

            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div>
        <div class="panel-footer" >
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn btn-primary', 'id' => 'delete']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'delete-map-route'); ?> 
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
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Collection.</span></div></div>");
                return false;
            } else {
            $("#delete-map-route").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'delete-map-route');
