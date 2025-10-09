<?php

use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;

$class_cols = 'col-sm-3';
$union_url = Url::to(['site/get-rmrd-unions', 'date' => $date, 'widget_for' => 'rmrd']);

$this->title = Yii::t('app', Yii::$app->label->title('list', 'MCC'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $breadcrum_title . $this->title; ?>
        <button type="button" class="headerIcon gread_header_icon btn btn-danger apply-shortcut btn-block" data-toggle="collapse" data-target=".grid_card"><i class="fa fa-list"></i></button>
        <button onclick="exportThisWithParameter('recovery_grid', '<?= $this->title ?>')" type="button" class="headerIcon btn btn-danger apply-shortcut btn-block right_30" ><i class="fa fa-file-excel-o"></i></button> 
        <span class="right_align_mcc_shift"><?= '(' . Yii::$app->general->getShiftName($shift) . ')' ?></span>
        <span class="right_align_date"><?= Yii::$app->controls->view_date($date) ?></span>
    </div>
    <div class="panel-body hide-grid-export overflow_visible">
        <div id="plant-list" class="grid-content">
            <div id="plant-list">
                <div id="w12" class="grid-view hide-resize" >
                    <div class="panel panel-default">
                        <div class="col-sm-6 farmer_rmrd_block">
                            <?php
                            echo $this->render('_dashboard_grid_rmrd_block', ['date' => $date, 'class_cols' => $class_cols, 'blocks_data' => $blocks_data, 'union' => $union, 'shift' => $shift]);
                            ?>
                        </div>

                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <div class="grid_card collapse">
                                    <?php
                                    if (!empty($output)) {
                                        foreach ($output as $data) {
                                            // $tbl_plant_model = new TblMccPlant();
                                            // $tbl_plant_model->mcc_plant_code = $data['mcc_code'];
                                            ?>
                                            <div class="div_grid_block padding_left_0 padding_right_0 padding_top_0 dashboardWidgetDetailPortion col-sm-3">
                                                <div class="div_grid_block_content">
                                                    <!-- <p class="dash_grid_block_header"><?= Yii::t('app', 'Union') ?></p> -->
                                                    <h4 class="dash_grid_block_header"><?= $data['mcc_name'] ?></h4>
                                                    <!-- <h4 class="dash_block_value block_value" id="farmer_rmrd_block_mcc"></h4> -->
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12 padding_left_0 padding_right_0 border_top_1">
                                                        <div class="col-sm-4 dash_grid_block_desc text_center border_right_1 pt_4"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Quantity') ?></span></div>
                                                        <div class="col-sm-4 dash_grid_block_desc text-center border_right_1 pt_4"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Avg. FAT/ SNF') ?></span></div>
                                                        <div class="col-sm-4 dash_grid_block_desc text-center pt_4 border_top_1"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Avg. Rate') ?></span></div>
                                                        <div class="col-sm-4 dash_grid_block_desc text-center border_right_1">
                                                            <span class="dash_grid_block_desc_title">
                                                                <?= $data['total_quantity'] ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-4 dash_grid_block_desc text-center border_right_1">
                                                            <span class="dash_grid_block_desc_title">
                                                                <?= $data['avgFAT'] ?>/ <?= $data['avgSNF'] ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-4 dash_grid_block_desc text-center ">
                                                            <span class="dash_grid_block_desc_title">
                                                                <?= $data['avgRate'] ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-4">
                                                            <div class="width_grid_dash">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="width_grid_dash">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="width_grid_dash">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 padding_left_0 padding_right_0 border_bottom_1">
                                                        <div class="col-sm-4 padding_left_0 padding_right_0">
                                                            <div class="col-sm-12 dash_grid_block_desc text-center border_right_1 border_top_1"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Amount') ?></span></div>
                                                            <div class="col-sm-12 dash_grid_block_desc text-center border_right_1"><span class="dash_grid_block_desc_title"><span><?= $data['total_amount'] ?></span></span></div>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <?php
                                                            if (\Yii::$app->session->get('hasBMC') == 1) {
                                                                $col_class = 'col-sm-4';
                                                            } else {
                                                                $col_class = 'col-sm-6';
                                                            }
                                                            ?>
                                                            <?php if (\Yii::$app->session->get('hasBMC') == 1) { ?>
                                                                <div class="<?= $col_class ?> dash_grid_block_desc text-center"><span class="dash_grid_block_ans"><?= Yii::t('app', 'BMC') ?></span></div>
                                                            <?php } ?>
                                                            <div class="<?= $col_class ?> dash_grid_block_desc text-center"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Society') ?></span></div>
                                                            <?php
                                                            if (\Yii::$app->session->get('hasBMC') == 1) {
                                                                $url = Url::to(['site/get-rmrd-bmcs', 'date' => $date, 'union_code' => $union, 'mcc_code' => $data['mcc_code'], 'widget_for' => 'rmrd', 'shift' => $shift]);
                                                                ?>
                                                                <div class="<?= $col_class ?> dash_grid_block_desc text-center href_link_underline"><a href="<?= $url; ?>" ><span class="dash_grid_block_desc_title"><span><?= $data['total_bmc'] ?></span></span></a></div>
                                                            <?php }
                                                            ?>
                                                            <?php $url = Url::to(['site/get-rmrd-dcs', 'date' => $date, 'union_code' => $union, 'mcc_code' => $data['mcc_code'], 'widget_for' => 'rmrd', 'shift' => $shift]); ?>
                                                            <div class="<?= $col_class ?> dash_grid_block_desc text-center href_link_underline"><a href="<?= $url; ?>" ><span class="dash_grid_block_desc_title"><span><?= $data['total_dcs'] ?></span></span></a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div id ="recovery_grid" class="col-sm-6" class="table table-striped">
                                <div id="dash_collapse_grid">
                                    <div class="col-sm-12">
                                        <div class="table-responsive dashboard_collection_grid_tbl">
                                            <table class="table overflow_hidden table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="custom_grid_header">#</th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'MCC') ?></th>
                                                        <?php if (\Yii::$app->session->get('hasBMC') == 1) { ?><th class="custom_grid_header"><?= Yii::t('app', 'BMC') ?></th><?php } ?>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'Society') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'LYSD QTY') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'LD QTY') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'Qty') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'Avg. FAT') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'Avg. SNF') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'Avg. Rate') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'Fat Solid') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'SnF Solid') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'Eff. RTPL') ?></th>
                                                        <th class="custom_grid_header"><?= Yii::t('app', 'Amount') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $totalQuantity = 0;
                                                    $totalFatSolid = 0;
                                                    $totalSnfSolid = 0;
                                                    $totalAmount = 0;
                                                    $totalEffRtpl = 0;
                                                    if (!empty($output)) {
                                                        $i = 0;
                                                        foreach ($output as $data) {
                                                            $totalQuantityStyle = ($data['ld_quantity'] > $data['total_quantity']) ? 'style="background-color: #ff000099;"' : 'style="background-color: #008000c4;"';
                                                            $totalQuantity += $data['total_quantity'];
                                                            $totalFatSolid += $data['fat_solid'];
                                                            $totalSnfSolid += $data['snf_solid'];
                                                            $totalAmount += $data['total_amount'];
                                                            $totalEffRtpl = ($totalAmount / (($totalFatSolid) + (($totalSnfSolid) * 2 / 3))) / 8;
                                                            ?>
                                                            <tr>
                                                                <td class="custom_grid_normal"><?= ++$i; ?></td>
                                                                <?php
                                                                // $tbl_plant_model = new TblMccPlant();
                                                                // $tbl_plant_model->mcc_plant_code = $data['mcc_code'];
                                                                ?>
                                                                <td class="grid_left_align custom_grid_normal"><?= $data['mcc_name'] ?></td>
                                                                <?php
                                                                if (\Yii::$app->session->get('hasBMC') == 1) {
                                                                    $url = Url::to(['site/get-rmrd-bmcs', 'date' => $date, 'union_code' => $union, 'mcc_code' => $data['mcc_code'], 'widget_for' => 'rmrd', 'shift' => $shift]);
                                                                    ?>
                                                                    <td class="number_align custom_grid_normal href_link_underline"><a href="<?= $url ?>" ><?= $data['total_bmc'] ?></a></td>
                                                                <?php } ?>
                                                                <?php $url = Url::to(['site/get-rmrd-dcs', 'date' => $date, 'union_code' => $union, 'mcc_code' => $data['mcc_code'], 'widget_for' => 'rmrd', 'shift' => $shift]); ?>
                                                                <td class="number_align custom_grid_normal href_link_underline"><a href="<?= $url ?>" ><?= $data['total_dcs'] ?></a></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['lysd_quantity'] ?></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['ld_quantity'] ?></td>
                                                                <td class="number_align custom_grid_normal color_fff" <?= $totalQuantityStyle ?>><?= $data['total_quantity'] ?></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['avgFAT'] ?></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['avgSNF'] ?></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['avgRate'] ?></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['fat_solid'] ?></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['snf_solid'] ?></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['eff_rtpl'] ?></td>
                                                                <td class="number_align custom_grid_normal" ><?= $data['total_amount'] ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr><td colspan="10">No Data Available.</td></tr>
                                                    <?php } if (!empty($output)) { ?>
                                                        <tr>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong><?= $totalQuantity ?></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong><?= $totalFatSolid ?></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong><?= $totalSnfSolid ?></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong><?= number_format($totalEffRtpl, 2) ?></strong></td>
                                                            <td class="number_align custom_grid_normal"><strong><?= $totalAmount ?></strong></td>
                                                            <td colspan="7"></td>
                                                        </tr>
                                                    <?php } ?>
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
</div>

<?php
$script = "  
var i =0;
$('.gread_header_icon').click(function(){
    $('#dash_collapse_grid').toggle();

    if(i==0){
        $('.gread_header_icon i ').removeClass('fa fa-list').addClass('fa fa-th-large');
        i=1;
    }
    else{
        $('.gread_header_icon i ').removeClass('fa fa-th-large').addClass('fa fa-list');
        i=0;
    }
});
";
$this->registerJs($script, View::POS_READY, 'union-wise-data');
?>