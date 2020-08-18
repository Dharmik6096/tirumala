<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>
<div class="col-sm-12">
    <table class="table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table"id='dcsViewModal'>
        <thead>
            <tr>
                <th><?php echo Yii::t('app', 'DCS Code') ?></th>
                <th><?php echo Yii::t('app', 'DCS Name') ?></th>
                <th><?php echo Yii::t('app', 'SLOC Code') ?></th>
                <th><?php echo Yii::t('app', 'SAP Code') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($data as $dcs_data) {
                ?>
                <tr>
                    <td><?= $dcs_data['dcs_code'] ?></td>
                    <td><?= $dcs_data['dcs_name'] ?></td>
                    <td><?= $dcs_data['ref_code'] ?></td>
                    <td> <?php
                        echo Html::hiddenInput('dcs[' . $dcs_data['dcs_code'] . '][asset_set_code]', $dcs_data['asset_set_code']);
                        echo Html::hiddenInput('dcs[' . $dcs_data['dcs_code'] . '][ref_code]', $dcs_data['ref_code']);
                        echo Html::hiddenInput('dcs[' . $dcs_data['dcs_code'] . '][store_location_code]', $dcs_data['store_location_code']);
                        echo Html::hiddenInput('dcs[' . $dcs_data['dcs_code'] . '][store_location_type]', $dcs_data['store_location_type']);
                        echo Html::textInput('dcs[' . $dcs_data['dcs_code'] . '][sap_code]', $dcs_data['sap_code'], ['class' => 'form-control']);
                        ?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
</div>
