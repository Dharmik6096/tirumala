<?php

use app\modules\dcsoperation\models\TblMember;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
$class_cols = 'col-sm-3';
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Farmer'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
    <?= $breadcrum_title.$this->title; ?>
<!-- <button type="button" class="headerIcon gread_header_icon btn btn-danger apply-shortcut btn-block" data-toggle="collapse" data-target="#dash_collapse_grid"><i class="fa fa-list"></i></button> -->
    </div>
    <div class="panel-body hide-grid-export">
    <div id="plant-list" class="grid-content">
        <div id="plant-list">
            <div id="w12" class="grid-view hide-resize" >
                <div class="panel panel-default">
                <div class="col-sm-6 farmer_rmrd_block">
                    <?php 
                        echo $this->render('_dashboard_grid_block', ['date' => $date, 'class_cols' => $class_cols, 'blocks_data' => $blocks_data]);
                    ?>
                </div>
    
                <div class="col-sm-6">
                    <div id="dash_collapse_grid">
                        <div class="col-sm-12">
                            <div class="table-responsive height_grid_f dashboard_collection_grid_tbl">
                                <table class="table overflow_hidden table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?= Yii::t('app', 'Farmer') ?></th>
                                            <th><?= Yii::t('app', 'Qty') ?></th>
                                            <th><?= Yii::t('app', 'Avg. FAT') ?></th>
                                            <th><?= Yii::t('app', 'Avg. SNF') ?></th>
                                            <th><?= Yii::t('app', 'Avg. Rate') ?></th>
                                            <th><?= Yii::t('app', 'Amount') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($output)) {
                                        $i=0;
                                        foreach ($output as $data) { 
                                            ?>
                                            <tr>
                                                <td><?= ++$i; ?></td>
                                                <?php 
                                                    $tbl_member_model = new TblMember();
                                                    $tbl_member_model->member_code = $data['member_code'];
                                                ?>
                                                <td class="grid_left_align"><?= !empty(Yii::$app->general->getforeignkey($tbl_member_model->tblMember, 'member_name'))? Yii::$app->general->getforeignkey($tbl_member_model->tblMember, 'member_name') : 'N/A' ?></td>
                                                <td class="grid_right_align"><?= $data['total_quantity'] ?></td>
                                                <td class="grid_right_align"><?= $data['avgFAT'] ?></td>
                                                <td class="grid_right_align"><?= $data['avgSNF'] ?></td>
                                                <td class="grid_right_align"><?= $data['avgRate'] ?></td>
                                                <td class="grid_right_align"><?= $data['total_amount'] ?></td>
                                            </tr>
                                        <?php 
                                        }    
                                    } else {
                                        ?>
                                        <tr><td colspan="7s">No Data Available.</td></tr>
                                    <?php }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
