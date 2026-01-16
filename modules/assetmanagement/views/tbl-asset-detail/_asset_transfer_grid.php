<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Asset Transfer');
?>
<?php
$form = ActiveForm::begin([
    'id' => 'update-asset-transfer',
    'method' => 'post',
]);
?>
<div class=" no-effect">
    <div>
        <?php
        $attribute = [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function ($model) {
                    return ['class' => 'checkbox', 'value' => $model['asset_transaction_code']];
                }
            ],
            ['attribute' => 'serial_number', 'filter' => FALSE, 'visible' => true],
            ['attribute' => 'asset_code', 'filter' => FALSE, 'visible' => true],
            ['attribute' => 'asset_code', 'label' => 'Asset Name', 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->assetCode, 'asset_name');
            }],
            ['attribute' => 'detail_code', 'label' => 'Contact person name', 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->contactDetailCode, 'firstname');
            }],
        ];

        $grid_option = [
            'id' => 'update-asset-transfer-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
        <div class="clearfix"></div>
        <div class="col-sm-2 mt10">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Yii::$app->dropdown->contactDetails($txnModel, $form, 'tblassettransactionsearch-to_dcs', 'detail_code', FALSE, FALSE, '', FALSE, TRUE);
            }
            ?>
        </div>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Transfer'), ['class' => 'btn btn-primary', 'id' => 'transfer']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'update-asset-transfer'); ?>
        </div>

<?php ActiveForm::end(); ?>
<?php
$script = '
$(".kv-panel-before").hide();
$("#transfer").click(function() {
    var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
        if(len == 0){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
            return false;
        } else {
        $("#update-asset-transfer").submit();
    }
});
';
        $this->registerJs($script, View::POS_END, 'update-asset');
