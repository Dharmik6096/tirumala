<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class=" no-effect">


    <?php
    $form = ActiveForm::begin([
                'id' => 'block-request',
    ]);
    ?>
    <div class="">
        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function ($model, $key, $index) {
                    return ['class' => 'checkbox', 'value' => $model['report_txn_log_id']];
                }],
            ['attribute' => 'report_type', 'visible' => true, 'filter' => false],
            ['attribute' => 'report_title', 'visible' => true, 'filter' => false],
            ['attribute' => 'created_at', 'value' => function ($searchModel) {
                    return Yii::$app->controls->view_datetime($searchModel->created_at);
                }, 'filter' => false
            ],
            ['attribute' => 'user_code',
                'value' => function ($model) {
                  return Yii::$app->general->getforeignkey($model->userCode, 'name');
                }
            ],
            [
                'attribute' => 'status',
                'filter' => Yii::$app->dropdown->dropdownfilterStatic('report_req_status', $searchModel, 'status'),
                'value' => function ($searchModel) {
                    return isset(Yii::$app->dropdown->getRecords('report_req_status')['data'][$searchModel->status]) ? Yii::$app->dropdown->getRecords('report_req_status')['data'][$searchModel->status] : '';
                }, 'filter' => FALSE],
        ];

        $grid_option = [
            'id' => 'block-request-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
        ];
        ?>
            <?php Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['block-request']); ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Block Request'), ['class' => 'btn btn-primary submit', 'id' => 'block', 'value' => 'block', 'name' => 'block']);
            }
            ?>
        <?= Html::button(Yii::t('app', 'Reset'), ['class' => 'btn btn-default', 'id' => 'reset-btn']); ?>
        </div>

<?php ActiveForm::end(); ?>
    </div>
</div>


<?php
$script = '
    $(".kv-panel-before").hide();
 
    $(".submit").click(function() {
        var id= $(this).attr("value");

        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
        if(len == 0){
        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
        return false;
                    }
        else {
        $("#block-request").submit();
        }
    });

    $("#reset-btn").click(function() {
        $("input[class=\"checkbox kv-row-checkbox\"]:checked").prop("checked", false);
    });
';
$this->registerJs($script, View::POS_END, 'block-request');
?>

