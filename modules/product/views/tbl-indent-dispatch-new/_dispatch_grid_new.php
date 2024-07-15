<div class="padding_left_45">
    <?php

    use yii\bootstrap\ActiveForm;
    use kartik\grid\GridView;
    use yii\helpers\Html;
    use yii\web\View;

$models = new app\modules\product\models\TblIndentMaster();
    $dataProvide = $models->indentDetail($model);
    ?>
    <div class="no-effect">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'indent-dispatch-new',
        ]);
        ?>
        <div class="">
            <?php
            $attributes = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'header' => false,
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'],
                    'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model, $key, $index) {
                        $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                        $id = $model['dcs_code'] . $member_code . $model['product_code'];
                        return ['class' => 'checkbox group-checkbox child-checkbox ' . $id, 'data-id' => $id, 'value' => $model['indent_code']];
                    }],
                ['attribute' => 'member_code'],
                ['attribute' => 'qty'],
                ['attribute' => 'status_date', 'label' => Yii::t('app', 'Indent Approve Date'), 'value' => function($model) {
                        return Yii::$app->controls->view_date($model->status_date);
                    }, 'filter' => FALSE],
                ['attribute' => 'dispatch_qty', 'filter' => FALSE,
                    'format' => 'raw',
                    'value' => function ($model, $key, $index) use ($form, $dispatchModel) {
                        echo Html::activeHiddenInput($dispatchModel, '[' . $model['indent_code'] . ']indent_code', ['value' => $model->indent_code]);
                        echo Html::activeHiddenInput($dispatchModel, '[' . $model['indent_code'] . ']approve_qty', ['value' => $model->approve_qty]);
                        return $form->field($dispatchModel, '[' . $model['indent_code'] . ']dispatch_qty')->textInput(['value' => $dispatchModel->dispatch_qty, 'class' => 'form-control number-validate qty-dispatch dispatch_qty-' . $model->indent_code.' '.$model['product_code'], 'data-class' => $model['product_code'], 'data-id' => $model['indent_code']])->label(FALSE);
                    },
                ],
                ['attribute' => 'remaining_qty', 'label' => Yii::t('app', 'Remaining Qty'), 'filter' => FALSE,
                    'format' => 'raw',
                    'value' => function ($model, $key, $index) use ($form, $dispatchModel) {
                        $remaining_qty = $model->approve_qty - $dispatchModel->dispatch_qty;
                        echo Html::activeHiddenInput($dispatchModel, '[' . $model['indent_code'] . ']remaining_qty', ['value' => $remaining_qty]);
                        echo Html::activeHiddenInput($dispatchModel, '[' . $model['indent_code'] . ']is_close', ['value' => 1]);
                        return '<span id="tblindentdispatch-' . $model['indent_code'] . '-remaining">' . $remaining_qty . '</span>';
                    },
                ],
            ];
            ActiveForm::end();
            ?>
        </div>
    </div>

    <?php
    echo GridView::widget([
        'id' => 'details-grid',
        'dataProvider' => $dataProvide,
        'layout' => '{items}{pager}',
        'columns' => $attributes,
        'containerOptions' => ['style' => 'overflow: auto; height: auto !important'],
        'headerRowOptions' => ['style' => 'background-color: rgb(0, 163, 222); color: white;'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    ]);
    ?>
</div>