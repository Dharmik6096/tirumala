<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'bulk-pending-approval',
    ]);
    ?>
    <div class="">
        <?php
        echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
        echo Html::hiddenInput('approve_remarks', 'approve_remarks', ['class' => 'set_remarks']);
        ?>
        <?php
        $attribute = [
                [
                'class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key, $index) {
                    return [
                        'class' => 'checkbox',
                        'value' => $model['process_approval_code'],
                    ];
                }
            ],
                ['attribute' => 'union_code', 'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
                }, 'filter' => false, 'visible' => false],
                ['attribute' => 'bmc_code', 'value' => 'bmc_code', 'filter' => false],
                ['attribute' => 'union_code', 'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->tblDcsBmc, 'bmc_name');
                }, 'filter' => false],
                ['attribute' => 'society_code', 'value' => 'dcs_code', 'filter' => false],
                ['attribute' => 'created_at', 'vAlign' => 'middle', 'value' => function($model, $key, $index) use ($form) {
                    echo Html::activeHiddenInput($model, '[' . $model['process_approval_code'] . ']provisional_member_code', ['value' => $model->provisional_member_code]);
                    return Yii::$app->controls->view_date($model->created_at);
                }, 'filter' => false],
                ['attribute' => 'member_code', 'value' => 'member_code'],
                ['attribute' => 'ex_member_code', 'value' => 'ex_member_code'],
                ['attribute' => 'member_name', 'value' => 'member_name'],
                ['attribute' => 'mobile_no', 'visible' => TRUE, 'filter' => true],
                ['attribute' => 'application_no', 'filter' => true],
                ['attribute' => 'provisional_from'],
                ['attribute' => 'provisional_status', 'value' => function($model) {
                    return isset(Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->provisional_status]) ? Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->provisional_status] : '';
                }],
                ['attribute' => 'remarks', 'format' => 'raw', 'value' => function ($model, $key, $index) use ($form) {
                    return '<span class=\'remarks\'>' . $form->field($model, '[' . $model['process_approval_code'] . ']remarks')->textInput(['value' => $model->remarks, 'class' => 'form-control',])->label(FALSE) . '</span>';
                },],
        ];

        $grid_option = [
            'id' => 'bulk-pending-approval-list-data',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                ?>
                <?= $form->field($provisionalModel, 'remarks', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            
                <div class="clearfix"></div><br>
                <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit btn-login', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']); ?>
                <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit btn-login', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']); ?>
            <?php }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'pending-approval', '', 'btn-login'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = '
    $(".kv-panel-before").hide();

    $(".submit").click(function() {
        var id= $(this).attr("value");
        $(".set_operation").val(id);
        var remarks = $("#tblmemberprovisional-remarks").val();
        $(".set_remarks").val(remarks);
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Provisional Member.</span></div></div>");
                return false;
            } else {
                $("#bulk-pending-approval").submit();
            }
    });
';
$this->registerJs($script, View::POS_END, 'bulk-pending-approval');
