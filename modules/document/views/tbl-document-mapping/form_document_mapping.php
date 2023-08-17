<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;

$button = Yii::$app->label->button('create');
$this->title = Yii::t('app', 'Document Mapping');
$defaultToggle = true;
?>
<?php if (!empty($dataProvider->getModels())) {
    ?>
    <div class="showHideData">
        <div class="panel panel-default panel-main">
            <div class="panel-heading"><?= Html::encode($this->title) ?></div>
            <div class="panel-body">
                <div class="padding-0">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'id' => 'document-mapping-form',
                                    'field-class' => 'form-group col-sm-3'
                                ],
                                'validateOnBlur' => FALSE,
                                'validateOnChange' => FALSE,
                                'enableClientValidation' => true,
                                'validateOnSubmit' => true,
                    ]);
                    ?>                     
                    <?= Html::hiddenInput('master_type', $model->master_type, ['id' => 'master_type']); ?>

                    <?php echo $form->errorSummary($model); ?>
                    <div class="panel-body set_checkbox padding_top_0 tbl_border">

                        <div class="clearfix"></div>
                        <div class="panel-subheading hide_help_block">
                            <div class="row">
                                <div class="table-responsive whiteSpaceAllowTable">
                                    <?php
                                    if (!empty($dataProvider->getModels())) {
                                        $data = $dataProvider->getModels();
                                        $defaultToggle = false;
                                        $count = count($data);
                                        $disp_table = $count / 2;
                                        $first_table = ceil($disp_table);
                                        $second_table = $first_table * 2;
                                        ?>
                                        <div class="col-sm-6">
                                            <table class="table table-bordered table-striped table-main table-language table-rate">
                                                <thead>
                                                    <tr>
                                                        <th width='5%' height='35' class='center-align center_text'><?= Html::checkbox('allowCashCheckAll', false, ['id' => 'allowCashCheckAll', 'class' => 'checkbox', 'label' => '']) ?></th>
                                                        <th width='80%' height='35'><?php echo $model->getAttributeLabel('document_name') ?></th>
                                                        <th width='5%' height='35'><?php echo $model->getAttributeLabel('is_mandate') ?></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for ($i = 0; $i < $first_table; $i++) { ?>
                                                        <tr>
                                                            <td class='center-align center_text'>
                                                                <?php
                                                                $val = $data[$i]['doc_id'];
                                                                $selected = in_array($val, $selectedArray);
                                                                echo Html::checkbox('docId[]', $selected, ['class' => 'allow-cash-checkbox checkbox', 'label' => '', 'value' => $val]);
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?= $data[$i]->doc_name ?></td>
                                                            <?php
                                                            $mandateselected = in_array($val, $mandateselectedArray);
                                                            ?>
                                                            <td><?= Html::checkbox('isMandate[]', $mandateselected, ['class' => '', 'label' => '', 'value' => $val]); ?></td>
                                                        </tr>

                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-6">
                                            <table class="table table-bordered table-striped table-main table-language table-rate">
                                                <thead>
                                                    <tr>
                                                        <th width='5%' height='35'></th>
                                                        <th width='80%' height='35'><?php echo $model->getAttributeLabel('document_name') ?></th>
                                                        <th width='5%' height='35'><?php echo $model->getAttributeLabel('is_mandate') ?></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($i = $first_table; $i < $second_table && $i < $count; $i++) {
                                                        ?>
                                                        <tr>
                                                            <td class='center-align center_text'>
                                                                <?php
                                                                $val = $data[$i]['doc_id'];
                                                                $selected = in_array($val, $selectedArray);
                                                                echo Html::checkbox('docId[]', $selected, ['class' => 'allow-cash-checkbox checkbox', 'label' => '', 'value' => $val]);
                                                                ?>
                                                            </td>
                                                            <td><?= $data[$i]->doc_name ?></td>
                                                            <?php
                                                            $mandateselected = in_array($val, $mandateselectedArray);
                                                            ?>
                                                            <td><?= Html::checkbox('isMandate[]', $mandateselected, ['class' => '', 'label' => '', 'value' => $val]); ?></td>
                                                        </tr>

                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <?php
                                    } else {
                                        echo "<p class='text-center'>" . Yii::t('app', 'Data Not Available') . "</p>";
                                        $defaultToggle = true;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <?php if (!empty($dataProvider->getModels())) { ?>
                            <?php
                            echo GhostHtml::a(Yii::t('app', 'Save'), ['/document/tbl-document-mapping/document-mapping'], ['class' => 'btn btn-primary', 'id' => 'mapping-document']);
                            ?>
                            <?= Yii::$app->controls->custombutton('cancel', 'index'); ?>

                        <?php } ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php } ?>

<?php
$script = "
  
  $('.kv-panel-before').hide();
    $('#mapping-document').click(function() {
        $('#document-mapping-form').submit();
    });
    $('#allowCashCheckAll').click(function () {
        var check =this.checked;
        $('.allow-cash-checkbox').each(function () {
            this.checked = check;
        });
    });
    $('#allowCashCheckAll').prop('checked', true);
    $('.allow-cash-checkbox').each(function () {
        if(this.checked == false){
            $('#allowCashCheckAll').prop('checked', false);
        }
    });
";

$this->registerJs($script, View::POS_END, 'force-rate-download');
