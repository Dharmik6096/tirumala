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
                                <table class="table overflow_hidden table-striped">
                                    <thead>
                                        <tr>
                                        <?php 
                                        if (!empty($dpu_data)) {?>
                                            <th class="dash_grid_v_header text_center">#</th><?php
                                        }?>
                                            <?php
                                                foreach ($labels as $key => $value) {
                                                ?>
                                                <th rowspan="2" class="dash_grid_v_header text_center"><?= Yii::t('app', $model->getAttributeLabel($value))?></th>
                                            <?php
                                                }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($dpu_data)) {
                                        $i=1; 
                                        foreach ($dpu_data as $key => $value) {   
                                        ?>
                                        <tr>
                                            <td><?= $i++;?></td>
                                            <?php
                                            foreach ($labels as $key => $label_value) {?>
                                                <td><?= $value[$label_value] ?></td>    
                                            <?php 
                                            }
                                            ?>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                            ?>
                                            <tr><td colspan="<?= count($labels)?>">No Data Available.</td></tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <!-- </div> -->
                    <!-- </div>
            </div>
    </div>
</div> -->