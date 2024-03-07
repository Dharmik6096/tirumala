<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Delete Member Payment Restrict');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'delete-member-payment-restrict',
    ]);
    ?>
    <div class="">

        <?php
        $attribute = [
                ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model) {
                    return ['class' => 'checkbox', 'value' => $model['member_payment_restrict_code']];
                }],
                ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                }, 'filter' => false],
                ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                }, 'filter' => false],
                ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
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
                }, 'filter' => false],
        ];

        $grid_option = [
            'id' => 'delete-bulk-payment-list',
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
            <?= Yii::$app->controls->custombutton('Cancel', 'delete-bulk-payment-restrict'); ?> 
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
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Member Payment Restrict.</span></div></div>");
                return false;
            } else {
            $("#delete-member-payment-restrict").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'delete-member-payment');
