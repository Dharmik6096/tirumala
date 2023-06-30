<?php

use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use kartik\helpers\Html;
use yii\web\View;
use app\modules\usermanagement\components\GhostHtml;
?>
<div class="panel-body">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $form = ActiveForm::begin([
                'id' => 'sap-error-file-reprocess',
                'method' => 'post'
    ]);

    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                $disabled = TRUE;
                if ($model->status == 3) {
                    $disabled = FALSE;
                }
                return ['disabled' => $disabled, 'class' => 'checkbox', 'value' => $model->ftp_txn_log_id];
            }],
        ['attribute' => 'union_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
            }, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            }, 'filter' => false],
        ['label' => Yii::t('app', 'Date'), 'value' => function($model) {
                return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->creatorId, 'applicable_date'));
            }, 'filter' => false],
        ['label' => Yii::t('app', 'Shift'), 'value' => function($model) {
                return Yii::$app->general->getmultiforeignkey($model->creatorId, ['shiftCode'], 'shift');
            }, 'filter' => false],
        ['attribute' => 'module_name', 'value' => function($model) {
                return !empty(Yii::$app->dropdown->getRecords('sap_file_type')['data'][$model->module_name]) ? Yii::$app->dropdown->getRecords('sap_file_type')['data'][$model->module_name] : '';
            }, 'filter' => FALSE],
        ['attribute' => 'file_name', 'filter' => false],
        ['attribute' => 'status', 'value' => function($model) {
                return !empty(Yii::$app->dropdown->getRecords('sap_file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('sap_file_status')['data'][$model->status] : '';
            }, 'filter' => FALSE],
    ];

    $grid_option = [
        'id' => 'ftp-txn-log-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'actions' => [
            'view-collection' => function ($url, $model) {
                $options = ['title' => 'View', 'target' => '_blank'];
                $date = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->creatorId, 'applicable_date'));
                $shift = Yii::$app->general->getforeignkey($model->creatorId, 'shift_code');
                $plant = Yii::$app->general->getforeignkey($model->mccPlantCode, 'plant_code');
                $url = $model->module_name == 'TblBmcCollection' ? '/collection/tbl-bmc-collection/index' : '/collection/tbl-milk-collection/index';
                $search = $model->module_name == 'TblBmcCollection' ? 'TblBmcCollectionSearch' : 'TblMilkCollectionSearch';
                $moduleArr = ['TblBmcCollection', 'TblMilkCollection'];
                $class = in_array($model->module_name, $moduleArr) ? '' : 'disabled';
//                $class = $model->module_name == 'TblBmcCollection' ? '' : ($model->module_name == 'TblMilkCollection' ? '' : '');
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => $class];
                return GhostHtml::a('<i class="fa fa-eye"></i>', [$url, $search => ['f_union_code' => $model->union_code, 'f_plant_code' => $plant, 'f_mcc_code' => $model->mcc_plant_code, 'from_date' => $date, "from_shift" => $shift, "to_date" => $date, "to_shift" => $shift]], $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
    <?php if (!empty($dataProvider->getModels())) { ?>
        <div class="panel-footer sticky-footer" >
            <?= Html::button(Yii::t('app', 'Re-Generate'), ['class' => 'btn btn-primary sub', 'name' => 'submit-ftp']); ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = "$('.sub').on('click',function(){
        $('#sap-error-file-reprocess').submit();
    });
    
    ";
$this->registerJs($script, View::POS_END, 'data-fpt-upload-script');
