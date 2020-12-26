<?php

use app\modules\dcsoperation\models\TblMember;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
$class_cols = 'col-sm-3';
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Farmer')).' of '.$dcs_name;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
<?= $this->title; ?>
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
    
                <div class="col-sm-6 milk-collection  h450">
                    <div class="table-responsive dashboard_collection_grid_tbl">
                        <table class="table table-striped">
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
                                        <td class="grid_left_align"><?= Yii::$app->general->getforeignkey($tbl_member_model->tblMember, 'member_name') ?></td>
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