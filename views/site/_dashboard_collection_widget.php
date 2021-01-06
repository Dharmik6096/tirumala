<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
$class_cols = 'col-sm-3';

$labels = [];
if (!empty($dpu_data)){
    foreach ($dpu_data[0] as $key => $value) {
        array_push($labels,$key);
    }
}
?>

<!-- <div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?php //$this->title; ?>
    </div>
    <div class="panel-body">
        <div class="">
            <?php
            // $form = ActiveForm::begin([
            //             'action' => ['collection-dashboard'],
            //             'method' => 'post',
            //         ]);
            ?>
            <div class="col-sm-8 pt5 padding_left_0">
                <div class="col-sm-2">
                    <?php// Yii::$app->controls->date($model, $form, 'date', '', true, false, false, false); ?>
                </div>
                <div class="col-sm-2">
                    <?php// Yii::$app->controls->search(); ?>
                </div>
            </div>
            <?php //ActiveForm::end(); ?>
        </div>
        <div class="col-sm-12">
            <div id="w12" class="grid-view hide-resize" > -->
                    <!-- <div class="panel panel-default"> -->
                            <div class="table-responsive dashboard_collection_grid_tbl max_h_100-100">
                                <table class="table overflow_hidden table-striped" id="dup_collection_table">
                                    <thead>
                                        <tr>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'Ref. Code')?></td>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'DCS Name')?></td>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'Collection Date')?></td>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'Shift')?></td>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'No of Sample')?></td>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'Qty')?></td>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'Pending')?></td>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'Error')?></td>
                                            <td class="custom_grid_header header_labels"><?= Yii::t('app', 'Processed')?></td>
                                        </tr>
                                    </thead>
                                    <tbody class="dpu_data_collection_tbl">
                                        
                                    </tbody>
                                </table>
                            </div>
                        <!-- </div> -->
                    <!-- </div>
            </div>
    </div>
</div> -->