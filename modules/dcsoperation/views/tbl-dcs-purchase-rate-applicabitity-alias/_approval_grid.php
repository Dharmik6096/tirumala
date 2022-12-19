<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\components\GhostHtml;

$visible = !empty($searchModel->status) ? FALSE : TRUE;
?>
<div class="grid-search no-effect" >
    <?php
    $form = ActiveForm::begin([
                'id' => 'approve-applicability',
    ]);
    ?>

    <?php
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model, $key, $index) {
                echo Html::hiddenInput('operation', '', ['class' => 'set_operation']);
                return ['class' => 'checkbox-collection', 'value' => $model['rate_app_alias_code']];
            }, 'visible' => $visible],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, TRUE, FALSE);
            }, 'filter' => false],
        ['attribute' => 'applicable_for', 'filter' => false],
        ['attribute' => 'wef_date', 'value' => function($model) {
                return Yii::$app->controls->view_date($model->wef_date);
            }, 'filter' => false],
        ['attribute' => 'shift_code', 'label' => Yii::t('app', 'Shift'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
            }, 'filter' => false],
        ['attribute' => 'applicable_code', 'filter' => false],
        ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, FALSE, FALSE);
            }, 'filter' => false],
        ['attribute' => 'purchase_rate_code', 'label' => Yii::T('app', 'Rate Id'), 'filter' => false],
        ['attribute' => 'error_desc', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'approve-applicability',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php if (!empty($dataProvider->getModels()) && $visible) { ?>
        <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']); ?>
        <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']); ?>
    <?php }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'applicabilty-approve'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
     var id= $(this).attr("value");
     $(".set_operation").val(id);
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Applicability.</span></div></div>");
                return false;
            } else {
            $("#approve-applicability").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'approve-applicability');
