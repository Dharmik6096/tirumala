<?php

use yii\web\View;
use yii\helpers\Url;
use app\modules\organisation\models\TblDcs;

$class_cols = 'col-sm-3';

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Not Installed VLC'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $breadcrum_title . $this->title; ?>
        <button onclick="exportThisWithParameter('recovery_grid', '<?= $this->title ?>')" type="button" class="headerIcon btn btn-danger apply-shortcut btn-block right_30" ><i class="fa fa-file-excel-o"></i></button> 
        <span class="right_align_date mr50"><?= Yii::$app->controls->view_date($date) ?></span>s
    </div>
    <div class="panel-body hide-grid-export overflow_visible">
        <div id="plant-list" class="grid-content">
            <div id="plant-list">
                <div id="w12" class="grid-view hide-resize" >
                    <div class="panel panel-default">

                        <div class="col-sm-6 farmer_rmrd_block">
                            <?php
                            echo $this->render('_dashboard_grid_mobile_block', ['date' => $date, 'class_cols' => $class_cols, 'blocks_data' => $blocks_data, 'union_code' => $union_code]);
                            ?>
                        </div>
                        <div id="recovery_grid" class="col-sm-6">
                            <div id="dash_collapse_grid">
                                <div class="col-sm-12">
                                    <div class="table-responsive height_grid_f dashboard_collection_grid_tbl">
                                        <table class="table overflow_hidden table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="custom_grid_header">#</th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Bmc Name') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Bmc Ref Code') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Dcs Name') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Dcs Ref Code') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Dcs Code') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Contact Person') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Mobile NO') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Status') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($output)) {
                                                    $i = 0;
                                                    foreach ($output as $data) {
                                                        ?>
                                                        <tr>
                                                            <td class="custom_grid_normal"><?= ++$i; ?></td>
                                                            <?php
                                                            $tbl_dcs_model = new TblDcs();
                                                            $tbl_dcs_model->dcs_code = $data['dcs_code'];
                                                            ?>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['bmc_name'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['bmc_ref_code'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['dcs_name'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['dcs_ref_code'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['dcs_code'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['contact_person'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['mobile_no'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['is_active'] ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
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
