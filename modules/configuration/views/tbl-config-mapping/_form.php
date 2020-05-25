<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<?php
if (!empty($dataProvider->getModels())) {
    ?>
    <div class="showHideData">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'widget-mapping-form',
        ]);
        ?>
        <div class="row">

            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblconfigmapping-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblconfigmapping-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
            </div>  
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblconfigmapping-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
            </div>
        </div>
        <div class="grid-search no-effect" >
            <div class="view-subtitle margin_0"><h5 class="pd5"><?= Yii::t('app', $searchModel->config_for) ?></h5></div>
            <hr class="line-color margin_0">

            <?= Html::hiddenInput('config_for', $model->config_for, ['id' => 'config_for']); ?>
            <?= Html::hiddenInput('process_name', $model->process_name, ['id' => 'process_name']); ?>

            <?php
            $attribute = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) use ($selectedArray) {
                        return ['class' => 'checkbox', 'value' => $model['config_code'], 'checked' => in_array($model['config_code'], $selectedArray)];
                    }],
                ['attribute' => 'config_name', 'filter' => false],
            ];

            $grid_option = [
                'id' => 'config-mapping-list',
                'attributes' => $attribute,
                'active_column' => false,
                'showPageSummary' => false,
            ];

            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="panel-footer" >
            <?php
            echo GhostHtml::a(Yii::t('app', 'Save'), ['/configuration/tbl-config-mapping/create'], ['class' => 'btn btn-primary', 'id' => 'config-mapping']);
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
<?php } ?>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#config-mapping").click(function(e) {
        e.preventDefault();
        $("#widget-mapping-form").submit();
    });
      ';
$this->registerJs($script, View::POS_END, 'config-mapping-list');
