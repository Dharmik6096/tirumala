<?php

use app\modules\dcsoperation\models\TblMember;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;

$class_cols = 'col-sm-3';
$this->title = Yii::t('app', Yii::$app->label->title('list', $title));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $breadcrum_title . $this->title; ?>
<!-- <button type="button" class="headerIcon gread_header_icon btn btn-danger apply-shortcut btn-block" data-toggle="collapse" data-target="#dash_collapse_grid"><i class="fa fa-list"></i></button> -->
        <span class="right_align_date right_align_date_rm_padding"><?= Yii::$app->controls->view_date($date) ?></span>
    </div>
    <div class="panel-body hide-grid-export">
        <div id="plant-list" class="grid-content">
            <div id="plant-list">
                <div id="w12" class="grid-view hide-resize" >
                    <div class="panel panel-default">
                        <div class="col-sm-6 farmer_rmrd_block">
                            <?php
                            echo $this->render('_dashboard_grid_block', ['date' => $date, 'class_cols' => $class_cols, 'blocks_data' => $blocks_data, 'union' => $union]);
                            ?>
                        </div>

                        <div class="col-sm-6">
                            <div id="dash_collapse_grid">
                                <div class="col-sm-12">
                                    <div class="table-responsive height_grid_f dashboard_collection_grid_tbl">
                                        <table class="table overflow_hidden table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="custom_grid_header">#</th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'BMC Code') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'BMC Name') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'DCS Code') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'DCS Name') ?></th>
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
                                                            $tbl_dcs_model = new \app\modules\organisation\models\TblDcs();
                                                            $tbl_dcs_model->dcs_code = $data['dcs_code'];
                                                            ?>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['bmc_code'] ?></td>
                                                            <td class="number_align custom_grid_normal"><?= $data['bmc_name'] ?></td>
                                                            <td class="number_align custom_grid_normal"><?= $data['dcs_code'] ?></td>
                                                            <td class="number_align custom_grid_normal"><?= $data['dcs_name'] ?></td>
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
