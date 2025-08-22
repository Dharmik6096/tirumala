<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<?php if (!empty($dataProvider->getModels())) {
    ?>
    <div class="showHideData">
        <div class=" no-effect" >
            <div class="col-sm-6 ">
    <!--                <h5 class="modal-title mt10 pb5"><?php // echo Yii::t('app', 'RMRD'); ?></h5>  
                <hr class="line-color margin_0">  -->
                <div class="view-subtitle margin_0 theme-box-heading"><h5 class=""><?= Yii::t('app', 'RMRD') ?></h5></div>
                <?php
                $form = ActiveForm::begin([
                            'id' => 'widget-mapping-form',
                ]);
                ?>
                <?= Html::hiddenInput('union_code', $model->union_code, ['id' => 'union_code']); ?>
                <?= Html::hiddenInput('login_type', $model->login_type, ['id' => 'login_type']); ?>
                <?= Html::hiddenInput('department', $model->department, ['id' => 'department']); ?>

                <?php
                $attribute = [
                    ['class' => 'kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        'headerOptions' => ['class' => 'skip-export '], 'contentOptions' => ['class' => 'skip-export kv-align-center'],
                        'checkboxOptions' => function($model) use ($selectedArray) {
                            return ['class' => 'checkbox', 'value' => $model['widget_id'], 'checked' => in_array($model['widget_id'], $selectedArray)];
                        }],
                    ['attribute' => 'widget_name', 'filter' => false],
                    ['attribute' => 'widget_type', 'filter' => false],
                ];

                DynaGrid::begin([
                    'columns' => $attribute,
                    'options' => ['id' => 'widget-mapping-rmrd'],
                    'theme' => 'simple-default',
                    'showPersonalize' => FALSE,
                    'storage' => 'cookie',
                    'showSort' => true,
                    'gridOptions' => [
                        'dataProvider' => $dataProvider,
                        'tableOptions' => array('class' => 'table table-bordered table-hover'),
                        'filterModel' => false,
                        'showPageSummary' => false,
//                        'floatHeader' => true,
//                        'floatOverflowContainer' => true,
                        'pjax' => false,
                        'panel' => ['heading' => false, 'before' => '',
                            'after' => '<div class="text-right padding-right-5">{pager}</div>',
                            'footer' => false],
                        'toolbar' => false
                    ]
                ]);
                DynaGrid::end();
                ?>
            </div>
            <div class="col-sm-6 " >
                   <!--<h5 class="modal-title mt10"><?php // echo Yii::t('app', 'FARMER'); ?></h5>-->  
                <div class="view-subtitle margin_0 theme-box-heading"><h5 class=""><?= Yii::t('app', 'FARMER') ?></h5></div>
                <?php
                $attributeOther = [
                    ['class' => 'kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                        'checkboxOptions' => function($model) use ($selectedArray) {
                            return ['class' => 'checkbox', 'value' => $model['widget_id'], 'checked' => in_array($model['widget_id'], $selectedArray)];
                        }],
                    ['attribute' => 'widget_name', 'filter' => false],
                    ['attribute' => 'widget_type', 'filter' => false],
                ];

                DynaGrid::begin([
                    'columns' => $attributeOther,
                    'options' => ['id' => 'widget-mapping-farmer'],
                    'theme' => 'simple-default',
                    'showPersonalize' => FALSE,
                    'storage' => 'cookie',
                    'showSort' => true,
                    'gridOptions' => [
                        'dataProvider' => $dataProviderOther,
                        'tableOptions' => array('class' => 'table table-bordered table-hover'),
                        'filterModel' => false,
                        'showPageSummary' => false,
//                        'floatHeader' => true,
//                        'floatOverflowContainer' => true,
                        'pjax' => false,
                        'panel' => ['heading' => false, 'before' => '',
                            'after' => '<div class="text-right padding-right-5">{pager}</div>',
                            'footer' => false],
                        'toolbar' => false
                    ]
                ]);
                DynaGrid::end();
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-2 mt10" >
            <?php
            echo GhostHtml::a(Yii::t('app', 'Save'), 'javaScript:void(0);', ['class' => 'btn btn-primary', 'id' => 'mapping-widget']);
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
<?php } ?>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#mapping-widget").click(function() {
        $("#widget-mapping-form").submit();
    });
      ';
$this->registerJs($script, View::POS_END, 'mapping-widget-list');
