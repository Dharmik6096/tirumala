<div class="padding_left_45">
    <?php

    use app\components\ActiveForm;
    use yii\web\View;
    use yii\helpers\Html;
    use kartik\grid\GridView;
    use yii\helpers\Url;
    use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Indent Approval');
    ?>
    <div class=" no-effect">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'indent-approval-new',
        ]);
        ?>
        <div class="">
            <?php //Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); 
            ?>
            <?php
            $attribute = [
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'header' => false,
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'],
                    'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function ($model, $key) {
                        $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
                        $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                        $id = $model['dcs_code'] . $member_code . $model['product_code'];
                        return ['class' => 'checkbox group-checkbox child-checkbox ' . $id, 'disabled' => $disabled, 'data-id' => $id, 'id' => 'tblindentmaster-' . $id . '-process_approval_code', 'value' => $model['process_approval_code']];
                    }
                ],
                ['attribute' => 'member_code', 'label' => 'Member Code', 'filter' => FALSE, 'visible' => ($visible ? FALSE : TRUE)],
                ['attribute' => 'member_ref_code', 'label' => 'Ref Code.', 'filter' => FALSE],
                ['attribute' => 'member_name', 'filter' => FALSE],
                ['attribute' => 'qty', 'filter' => FALSE],
                ['attribute' => 'indent_date', 'value' => function ($model) {
                        return Yii::$app->controls->view_date($model['indent_date']);
                    }, 'filter' => FALSE],
                [
                    'attribute' => 'approve_qty', 'filter' => FALSE,
                    'format' => 'raw',
                    'value' => function ($model) use ($form, $indentMaster) {
                        $level = $indentMaster->getApprovalLevel($model['indent_code']);
                        if (empty($level)) {
                            $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
                            $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                            $id = $model['dcs_code'] . $member_code . $model['product_code'];
                            echo Html::activeHiddenInput($indentMaster, '[' . $model['process_approval_code'] . ']qty', ['value' => $model['qty'], 'id' => 'tblindentmaster-' . $id . '-qty']);
                            echo Html::activeHiddenInput($indentMaster, '[' . $model['process_approval_code'] . ']rate', ['value' => $model['rate'], 'id' => 'tblindentmaster-' . $id . '-rate']);
                            return $form->field($indentMaster, '[' . $model['process_approval_code'] . ']approve_qty')->textInput(['value' => $model['qty'], 'class' => 'form-control number-validate approve_qty', 'id' => 'tblindentmaster-' . $id . '-approve_qty', 'data-id' => $id, 'disabled' => $disabled])->label(FALSE);
                        } else {
                            return $model['approve_qty'];
                        }
                    },
                ],
                [
                    'attribute' => 'rejected_qty', 'filter' => FALSE,
                    'format' => 'raw',
                    'value' => function ($model) use ($form, $indentMaster) {
                        $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
                        $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                        $id = $model['dcs_code'] . $member_code . $model['product_code'];
                        if ($model['rejected_qty'] == "") {
                            $model['rejected_qty'] = 0;
                        }
                        return $form->field($indentMaster, '[' . $model['process_approval_code'] . ']rejected_qty')->textInput(['value' => $model['rejected_qty'], 'class' => 'form-control number-validate', 'id' => 'tblindentmaster-' . $id . '-rejected_qty', 'disabled' => $disabled, 'readonly' => TRUE])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'amount', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE),
                    'format' => 'raw',
                    'value' => function ($model) use ($form, $indentMaster) {
                        $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                        $id = $model['dcs_code'] . $member_code . $model['product_code'];
                        return $form->field($indentMaster, '[' . $model['process_approval_code'] . ']amount')->textInput(['value' => $model['amount'], 'class' => 'form-control number-validate cls-amount cls-' . $id, 'id' => 'tblindentmaster-' . $id . '-amount', 'data-id' => $id, 'readonly' => TRUE])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'approve_remarks', 'filter' => FALSE,
                    'format' => 'raw',
                    'value' => function ($model) use ($form, $indentMaster) {
                        $level = $indentMaster->getApprovalLevel($model['indent_code']);
                        if (empty($level)) {
                            $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
                            $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                            $id = $model['dcs_code'] . $member_code . $model['product_code'];
                            return $form->field($indentMaster, '[' . $model['process_approval_code'] . ']approve_remarks')->textInput(['value' => $model['approve_remarks'], 'class' => 'form-control', 'id' => 'tblindentmaster-' . $id . '-approve_remarks', 'disabled' => $disabled])->label(FALSE);
                        } else {
                            return $model['approve_remarks'];
                        }
                    },
                ],
            ];
            ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php
    echo GridView::widget([
        'id' => 'details-grid',
        'dataProvider' => $dataProvider,
        'layout' => '{items}{pager}',
        'columns' => $attribute,
        'rowOptions' => function ($model, $key, $index, $grid) {
            $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
            $id = $model['dcs_code'] . $member_code . $model['product_code'];
            return [
                'data-id' => $id,
            ];
        },
        'containerOptions' => ['style' => 'overflow: auto; height: auto !important'],
        'headerRowOptions' => ['style' => 'background-color: rgb(0, 163, 222); color: white;'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    ]);
    ?>
</div>