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
        <div class=" no-effect" >
            <div class="col-sm-12 ">
    <!--                <h5 class="modal-title mt10 pb5"><?php // echo Yii::t('app', 'RMRD');                            ?></h5>  
                <hr class="line-color margin_0">  -->
                <div class="view-subtitle margin_0 theme-box-heading"><h5 class=""><?= Yii::t('app', 'Document Mapping') ?></h5></div>
                <?php
                $form = ActiveForm::begin([
                            'id' => 'document-mapping-form',
                ]);
                ?>
                <?= Html::hiddenInput('master_type', $model->master_type, ['id' => 'master_type']); ?>

                <?php
                $attribute = [
                        ['class' => 'kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        'headerOptions' => ['class' => 'skip-export '], 'contentOptions' => ['class' => 'skip-export kv-align-center'],
                        'checkboxOptions' => function($model) use ($selectedArray) {
                            return ['class' => 'checkbox', 'value' => $model['doc_id'], 'checked' => in_array($model['doc_id'], $selectedArray)];
                        }],
                        ['attribute' => 'doc_id', 'value' => function($model) {
                            return $model->doc_name;
                        }, 'filter' => false],
//                        ['attribute' => 'document_name', 'filter' => false],
//                    ['attribute' => 'is_mandate', 'filter' => false],
                ];

                DynaGrid::begin([
                    'columns' => $attribute,
                    'options' => ['id' => 'document-mapping'],
                    'theme' => 'simple-default',
                    'showPersonalize' => FALSE,
                    'storage' => 'cookie',
                    'showSort' => true,
                    'gridOptions' => [
                        'dataProvider' => $dataProvider,
                        'tableOptions' => array('class' => 'table table-bordered table-hover'),
                        'filterModel' => false,
                        'showPageSummary' => false,
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
            echo GhostHtml::a(Yii::t('app', 'Save'), ['/document/tbl-document-mapping/document-mapping'], ['class' => 'btn btn-primary', 'id' => 'mapping-document']);
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
<?php } ?>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#mapping-document").click(function() {
        $("#document-mapping-form").submit();
    });
      ';
$this->registerJs($script, View::POS_END, 'mapping-document-list');
