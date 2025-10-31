<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Delete Bulk Applicability');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'delete-bulk-applicability',
    ]);
    ?>
    <?= Html::activeHiddenInput($searchModel, 'applicable_for'); ?>
    <div class="">

        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model) {
                    return ['class' => 'checkbox', 'value' => $model['product_sale_rate_applicability_code']];
                }],
            ['attribute' => 'applicable_for', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
                }, 'filter' => false],
            ['attribute' => 'applicable_code', 'label' => Yii::t('app', 'Applicable Code'), 'filter' => false],
            ['attribute' => 'applicable_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, TRUE);
                }, 'filter' => false],
            ['attribute' => 'applicable_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, FALSE, TRUE);
                }, 'filter' => false],
            ['attribute' => 'applicable_code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for);
                }, 'filter' => false],
            [
                'attribute' => 'wef_date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }, 'filter' => FALSE],
//            ['attribute' => 'is_member_rate', 'filter' => false, 'value' => function($model) {
//                    return $model->is_member_rate == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
//                }],
        ];

        $grid_option = [
            'id' => 'delete-bulk-route-list',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn-login btn btn-primary', 'id' => 'delete']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'delete-bulk-applicability','','btn-login'); ?> 
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
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Applicability.</span></div></div>");
                return false;
            } else {
            $("#delete-bulk-applicability").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'delete-map-route');
