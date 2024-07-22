<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'VCG/MRG Member Approval');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
        'id' => 'vcg-mrg-member-approval',
    ]);
    ?>
    <div class="">
        <?php
        echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
        ?>
        <?php
        $attribute = [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function ($model, $key, $index) {
                    return ['class' => 'checkbox', 'value' => $model['VCG_MRG_member_id']];
                }
            ],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => FALSE],
            ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref Code.'), 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'member_code', 'filter' => FALSE],
            ['attribute' => 'member_name', 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
            }, 'label' => Yii::t('app', 'Member Name'), 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'member_tr_code', 'filter' => FALSE],
            ['attribute' => 'wef_date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->wef_date);
            }, 'filter' => FALSE],
            ['attribute' => 'type', 'filter' => FALSE],
            ['attribute' => 'status', 'filter' => FALSE],
            ['attribute' => 'remark', 'filter' => FALSE],
        ];
        $grid_option = [
            'id' => 'vcg-mrg-member-approval-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['vcg-mrg-member-approval']);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit', 'id' => 'approve', 'value' => 'APPROVED', 'name' => 'approve']);
                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'REJECTED', 'name' => 'reject']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = '
    $(".submit").click(function() {
        var id= $(this).attr("value");
        $(".set_operation").val(id);
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
        if(len == 0){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
            return false;
        } else {
            $("#vcg-mrg-member-approval").submit();
        }
    });
';
$this->registerJs($script, View::POS_END, 'vcg-mrg-member-approval');
