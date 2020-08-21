<?php
$this->title = Yii::$app->label->title('create', 'Dispatch Rate Recalculation');

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\grid\GridView;
?>
<div class="panel panel-default panel-grid panel-main hide_grid_search_filter hide_grid_settings_filter">
    <div class="panel-heading">
        <?= $this->title ?>
    </div>
    <div class="panel-body">
        <div class="grid-search clearfix large-search">
            <?php
            $rec_data = !empty($dataProvider) ? $dataProvider->allModels : '';
            $disable_search = empty($rec_data) ? FALSE : TRUE;
            echo $this->render('_dispatch_search', ['searchModel' => $searchModel, 'disable_search' => $disable_search]);
            ?>
        </div>

        <?php
        if (!empty($rec_data)) {
            $form = ActiveForm::begin([
                        'options' => ['id' => 'recalculation-form'],
                        'validateOnBlur' => FALSE,
                        
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
            ]);
            ?>
            <div class="col-sm-12">
                <?php echo $form->errorSummary($model); ?>
            </div>
            <?php
            if ($searchModel->recalc_type == 'all') {
                ?>
                <div class="col-sm-3">
                    <?= Html::activeHiddenInput($searchModel, 'recalc_for', ['value' => 'member']) ?>
                    <?= Yii::$app->dropdown->dcsRateChart($model, $form, 'tblraterecalculationsearch-union_code,tblraterecalculationsearch-recalc_for', 'rate_code', $model->getAttributeLabel('rate_code')); ?>
                </div>
            <?php } ?>
            <?= Html::activeHiddenInput($searchModel, 'union_code') ?>
            <?= Html::activeHiddenInput($searchModel, 'plant_code') ?>
            <?= Html::activeHiddenInput($searchModel, 'mcc_plant_code') ?>
            <?= Html::activeHiddenInput($searchModel, 'bmc_code') ?>
            <?= Html::activeHiddenInput($searchModel, 'recalc_type') ?>

            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php if (!empty($rec_data)) { ?>
                        <span class="btn_show">
                            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                        </span>
                        <?= Yii::$app->controls->custombutton('RESET', ['dcs-dispatch']); ?>
                        <?= Yii::$app->controls->custombutton('CANCEL', ['index']); ?>
                    <?php } ?>
                </div>

                <div class="hide_toolbar_only hide_filters_only">
                    <?php
                    if ($searchModel->recalc_type == 'all') {
                        $attribute = [
                            ['attribute' => 'type', 'filter' => false],
                            ['attribute' => 'code', 'filter' => false],
                            ['attribute' => 'code_ex', 'filter' => false],
                            ['attribute' => 'name', 'filter' => false],
                            ['attribute' => 'qty', 'filter' => false],
                            ['attribute' => 'amount', 'filter' => false],
                        ];
                    } else {
                        $attribute = [
                            ['class' => 'kartik\grid\CheckboxColumn',
                                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                                'checkboxOptions' => function($model) {
                            return ['value' => $model['code'] . '###' . $model['purchase_rate_code'] . '###' . $model['from_date'] . '###' . $model['to_date']];
                        }],
                            ['attribute' => 'type',
                                'value' => function($model) {
                                    return Yii::t('app', $model['type']);
                                },
                                'filter' => false],
                            ['attribute' => 'code', 'filter' => false],
                            ['attribute' => 'code_ex', 'filter' => false],
                            ['attribute' => 'name'],
                            ['label' => 'From Date', 'attribute' => 'from_date', 'value' => function($model) {
                                    $shift = explode(' ', $model['from_date'])[1] == '06:00:00.000000' ? ' (M)' : ' (E)';
                                    return Yii::$app->controls->view_date($model['from_date']) . $shift;
                                }, 'filter' => false],
                            ['label' => 'To Date', 'attribute' => 'to_date', 'value' => function($model) {
                                    $shift = explode(' ', $model['to_date'])[1] == '06:00:00.000000' ? ' (M)' : ' (E)';
                                    return !empty($model['to_date']) ? Yii::$app->controls->view_date($model['to_date']) . $shift : '';
                                }, 'filter' => false],
                            ['attribute' => 'wef_date', 'value' => function($model) {
                                    $shift = explode(' ', $model['wef_date'])[1] == '06:00:00.000000' ? ' (M)' : ' (E)';
                                    return Yii::$app->controls->view_date($model['wef_date']) . $shift;
                                }, 'filter' => false],
                            ['header' => 'Rate Id', 'attribute' => 'purchase_rate_code', 'filter' => false],
                            ['attribute' => 'qty', 'filter' => false],
                            ['attribute' => 'amount', 'filter' => false],
                        ];
                    }
                    $grid_option = [
                        'id' => 'dispatch-rate-recalculation-list',
                        'attributes' => $attribute,
                        'active_column' => false,
                    ];
                    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                    ?>
                </div>
                <?php
                ActiveForm::end();
            }
            ?>
        </div>
    </div>
</div>
