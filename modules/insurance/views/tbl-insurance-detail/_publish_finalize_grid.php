<?php

use app\components\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use kartik\grid\GridView;
?>
<div class="panel panel-default panel-grid panel-main hide-grid-settings">
    <div class="panel-body">
        <div class="grid-search no-effect" >
            <?php
            $form = ActiveForm::begin([
                        'id' => 'insurance-detail-publish-finalize-form',
                        'validateOnBlur' => false,
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'options' => ['method' => 'post']
            ]);
//            echo $form->errorSummary($model);
            echo Html::hiddenInput('process_flag', 'draft', ['class' => 'process_flag']);
            echo Html::hiddenInput('insurance_master_code', $searchModel->insurance_master_code);
            $attribute = [
                ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => false],
                ['attribute' => 'dcs_code_ex', 'label' => Yii::t('app', 'DCS Code Ex'), 'filter' => false],
                ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'filter' => false],
                ['attribute' => 'member_count', 'filter' => false],
                ['attribute' => 'status', 'filter' => false],
                ['attribute' => 'from_date',
                    'filterType' => GridView::FILTER_DATE,
                    'filterWidgetOptions' => ['pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true]],
                    'value' => function($model) {
                    return Yii::$app->controls->view_date($model['from_date']);
                    }],
                ['attribute' => 'to_date',
                    'filterType' => GridView::FILTER_DATE,
                    'filterWidgetOptions' => ['pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true]],
                    'value' => function($model) {
                        return Yii::$app->controls->view_date($model['to_date']);
                    }],
            ];

            $grid_option = [
                'id' => 'insurance-master-detail-publish-finalize-list',
                'attributes' => $attribute,
                'active_column' => false,
                'default_sorting' => FALSE,
                'actions' => [
//                    'view' => true,
                    'member-details' => function ($url, $model) {
                        $options = [
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'data-original-title' => 'Member Details',
                            'target' => '_blank'
                        ];
                        return Html::a('<i class="fa fa-user-circle-o"></i>', ['/insurance/tbl-insurance-detail/member-index', 'header_detail' => $model['dcs_code_ex'] . ' > ' . $model['dcs_name'], 'TblInsuranceDetailSearch' => ['dcs_code' => $model['dcs_code'], 'insurance_master_code' => $model['insurance_master_code']]], $options);
                    },
                ]
            ];
            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
            ?>
            <div class="clearfix"></div>

            <div class="col-md-12" >
                <?php if (!empty($dataProvider->getModels()) && strtoupper($dataProvider->getModels()[0]['status']) != 'FINALIZE') { ?>
                    <?php if (strtoupper($dataProvider->getModels()[0]['status']) == 'DRAFT') { ?>

                        <div class="col-sm-2">
                            <?= Yii::$app->controls->date($searchModel, $form, 'from_date', '', false); ?>
                        </div>
                        <div class="col-sm-2">
                            <?= Yii::$app->controls->date($searchModel, $form, 'to_date', '', false); ?>
                        </div>
                        <div class="clearfix"></div>
                        <?php
                        echo Html::button(Yii::t('app', 'Publish'), ['class' => 'btn btn-primary ', 'id' => 'publish']);
                    } else {
                        echo Html::button(Yii::t('app', 'Finalize'), ['class' => 'btn btn-primary', 'id' => 'finalize']);
                    }
                }
                echo Yii::$app->controls->custombutton('Cancel', ['/insurance/tbl-insurance-master/index']);
                ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = '  
$(document).on("click", "#publish", function(){
    $(".process_flag").val("publish");
    const fromDate = $("#tblinsurancedetailsearch-from_date").val(); 
    const toDate = $("#tblinsurancedetailsearch-to_date").val(); 
    $("#insurance-detail-publish-finalize-form").append(`<input type="hidden" name="from_date" value="${fromDate}">`);
    $("#insurance-detail-publish-finalize-form").append(`<input type="hidden" name="to_date" value="${toDate}">`);
    $("#insurance-detail-publish-finalize-form").submit();
});
$(document).on("click", "#finalize", function(){
    $(".process_flag").val("finalize");
    $("#insurance-detail-publish-finalize-form").submit();
});';
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
