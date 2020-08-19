<?php

use yii\helpers\Html;
?>

<div class="col-sm-12">
    <table class="table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table">
        <thead>
            <tr>
                <th class="pl5 br0"><?= Yii::t('app', 'Asset Code') ?></th>
                <th class="pl5 br0"><?= $model->getAttributeLabel('asset_code') ?></th>
                <th class="br0"><?= $model->getAttributeLabel('qty') ?></th> 
                <th class="br0"><?= $model->getAttributeLabel('serial_number') ?></th> 
            </tr>
        </thead>
        <tbody class="append_machine">
            <?php
            foreach ($assetset as $key => $detail) {
                $is_sr_no = Yii::$app->general->getforeignkey($detail->assetCode, 'is_serial_number');
                ?>
                <tr class="<?= $key ?>">
                    <td><?= $detail->asset_code; ?>
                    <td><?= Yii::$app->general->getforeignkey($detail->assetCode, 'asset_name'); ?>
                        <?= Html::activeHiddenInput($model, '[asset_set][' . $key . ']asset_transaction_code', ['value' => $detail->asset_transaction_code]); ?>
                        <?= Html::activeHiddenInput($model, '[asset_set][' . $key . ']is_serial_number', ['value' => $is_sr_no]); ?>
                    </td>
                    <td><?= $detail->qty; ?></td>
                    <td>
                        <?php
                        echo Html::activeTextInput($model, '[asset_set][' . $key . ']serial_number', ['class' => 'form-control', 'readonly' => ($is_sr_no) ? FALSE : TRUE]);
                        ?><?php //Html::activeHiddenInput($model, '[asset_set][' . $key . ']selected_sr_no', ['value' => $detail->serial_number]);  ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <br/>
</div>
