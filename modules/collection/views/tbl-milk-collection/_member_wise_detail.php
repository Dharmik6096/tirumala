<?php

use demogorgorn\ajax\AjaxSubmitButton;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
?>
<div class="modal modal-default fade" id="MemberDeleteModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Milk Collection Detail') ?></h4>
            </div>
            <div class='row pad-10'>
                <div class="col-md-12">
                    <div class="row">
                        <div class="grid-search no-effect">
                            <?php
                            $attribute = [
                                ['attribute' => 'dcs_code',
                                    'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                                    }, 'filter' => false],
                                ['header' => 'Member Code', 'attribute' => 'member_code', 'filter' => false],
                                ['header' => Yii::t('app', 'Member Code Ex'), 'attribute' => 'member_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
                                    }, 'filter' => false],
                                ['header' => Yii::t('app', 'Member'), 'attribute' => 'member_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                                    }, 'filter' => false],
                                ['label' => 'Date', 'attribute' => 'date_time_of_collection',
                                    'filterType' => GridView::FILTER_DATE,
                                    'filterWidgetOptions' => [
                                        'pluginOptions' => ['format' => 'dd-mm-yyyy',
                                            'autoclose' => true]
                                    ],
                                    'value' => function($model) {
                                        return Yii::$app->controls->view_date($model->date_time_of_collection);
                                    }, 'filter' => false],
                                ['attribute' => 'shift_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
                                    }, 'filter' => FALSE],
                                ['attribute' => 'sample_no', 'filter' => FALSE],
                                ['attribute' => 'milk_type_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
                                    }, 'filter' => FALSE],
                                ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->milkQualityCode, 'milk_quality_type_name');
                                    }, 'filter' => FALSE],
                                ['attribute' => 'qty', 'filter' => FALSE],
                                ['attribute' => 'fat', 'filter' => FALSE],
                                ['attribute' => 'snf', 'filter' => FALSE],
                                ['attribute' => 'clr', 'filter' => FALSE],
                                ['attribute' => 'rtpl', 'filter' => FALSE],
                                ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
                            ];

                            $grid_options = [
                                'id' => 'member-wise-detail-form',
                                'attributes' => $attribute,
                                'active_column' => false,
                                'showPageSummary' => false,
                            ];

                            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_options, ['#'], false);
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

