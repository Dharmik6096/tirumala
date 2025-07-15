<?php

use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;

$class_cols = 'col-sm-3';
$this->title = Yii::t('app', Yii::$app->label->title('list', 'DCS'));

$tbl_union_model = new TblUnions();
$tbl_union_model->union_code = $union;

$tbl_plant_model = new TblMccPlant();
$tbl_plant_model->mcc_plant_code = $mcc;

$union_url = Url::to(['site/get-unions', 'date' => $date]);
$mcc_url = Url::to(['site/get-mccs', 'date' => $date, 'union_code' => $union]);

$union_name = !empty(Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name')) ? Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name') : 'N/A';
$mcc_name = !empty(Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name')) ? Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name') : 'N/A';
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $breadcrum_title . $this->title; ?>
        <button type="button" class="headerIcon gread_header_icon btn btn-danger apply-shortcut btn-block" data-toggle="collapse" data-target=".grid_card"><i class="fa fa-list"></i></button>
        <span class="right_align_shift"><?= '(' . Yii::$app->general->getShiftName($shift) . ')' ?></span>
        <span class="right_align_date"><?= Yii::$app->controls->view_date($date) ?></span>
    </div>
    <div class="panel-body hide-grid-export overflow_visible">
        <div id="plant-list" class="grid-content">
            <div id="plant-list">
                <div id="w12" class="grid-view hide-resize" >
                    <div class="panel panel-default">
                        <div class="col-sm-6 farmer_rmrd_block">
                            <?php
                            echo $this->render('_dashboard_grid_block', ['date' => $date, 'class_cols' => $class_cols, 'blocks_data' => $blocks_data, 'union' => $union, 'shift' => $shift]);
                            ?>
                        </div>

                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <div class="grid_card collapse">
                                    <?php
                                    if (!empty($output)) {
                                        foreach ($output as $data) {
                                            ?>
                                            <div class="div_grid_block padding_left_0 padding_right_0 padding_top_0 dashboardWidgetDetailPortion col-sm-3">
                                                <div class="div_grid_block_content">
                                                    <!-- <p class="dash_grid_block_header"><?= Yii::t('app', 'Union') ?></p> -->
                                                    <h4 class="dash_grid_block_header"><?= $data['bmc_name'] ?></h4>
                                                    <h4 class="dash_grid_block_header"><?= $data['dcs_name'] ?></h4>
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
                                                            <div class="col-sm-12 dash_grid_block_desc text-center"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Farmer') ?></span></div>
                                                            <?php $url = Url::to(['site/get-farmers', 'date' => $date, 'union_code' => $union, 'mcc_code' => $mcc, 'bmc_code' => $bmc, 'dcs_code' => $data['dcs_code'], 'shift' => $shift]); ?>
                                                            <div class="col-sm-12 dash_grid_block_desc text-center href_link_underline"><a href="<?= $url; ?>" ><span class="dash_grid_block_desc_title"><span><?= $data['total_farmers'] ?></span></span></a></div>
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

                            <div id="dash_collapse_grid">
                                <div class="col-sm-12">
                                    <div class="table-responsive dashboard_collection_grid_tbl">
                                        <table id="recovery_grid" class="table overflow_hidden table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="custom_grid_header">#</th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'BMC') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Society') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Active Farmer') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Farmer') ?></th>
                                                    <th class="custom_grid_header active_div" id="qtyHeader">
                                                        <?= Yii::t('app', 'Qty') ?> <span id="qtySortIcon"></span>
                                                    </th>
                                                    <th class="custom_grid_header active_div" id="avgFatHeader">
                                                        <?= Yii::t('app', 'Avg. FAT') ?> <span id="avgFatSortIcon"></span>
                                                    </th>
                                                    <th class="custom_grid_header active_div" id="avgSnfHeader">
                                                        <?= Yii::t('app', 'Avg. SNF') ?> <span id="avgSnfSortIcon"></span>
                                                    </th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Avg. Rate') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Amount') ?></th>
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
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['dcs_name'] ?></td>
                                                            <?php $url = Url::to(['site/get-farmers', 'date' => $date, 'union_code' => $union, 'mcc_code' => $mcc, 'bmc_code' => $bmc, 'dcs_code' => $data['dcs_code'], 'shift' => $shift]); ?>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['active_farmers_count'] ?></td>
                                                            <td class="number_align custom_grid_normal href_link_underline"><a href="<?= $url ?>" ><?= $data['total_farmers'] ?></a></td>
                                                            <td class="number_align custom_grid_normal" ><?= $data['total_quantity'] ?></td>
                                                            <td class="number_align custom_grid_normal" ><?= $data['avgFAT'] ?></td>
                                                            <td class="number_align custom_grid_normal" ><?= $data['avgSNF'] ?></td>
                                                            <td class="number_align custom_grid_normal" ><?= $data['avgRate'] ?></td>
                                                            <td class="number_align custom_grid_normal" ><?= $data['total_amount'] ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr><td colspan="8">No Data Available.</td></tr>
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
$(document).ready(function() {
    let sortOrder = 'asc';
    function resetSortIcons(exclude) {
        if (exclude !== 'qty') $('#qtySortIcon').text('');
        if (exclude !== 'avgFat') $('#avgFatSortIcon').text('');
        if (exclude !== 'avgSnf') $('#avgSnfSortIcon').text('');
    }
    $('#qtyHeader').click(function() {
        sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
        $('#qtySortIcon').text(sortOrder === 'asc' ? '↑' : '↓');
        resetSortIcons('qty');
        var rows = $('#recovery_grid tbody tr').get();
        rows.sort(function(a, b) {
            var fatA = parseFloat($(a).find('td').eq(4).text()); // Qty column index is 4
            var fatB = parseFloat($(b).find('td').eq(4).text());
            if (isNaN(fatA)) fatA = 0;
            if (isNaN(fatB)) fatB = 0;
            return (sortOrder === 'asc') ? fatA - fatB : fatB - fatA;
        });
        $.each(rows, function(index, row) {
            $('#recovery_grid tbody').append(row);
        });
    });
    $('#avgFatHeader').click(function() {
        sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
        $('#avgFatSortIcon').text(sortOrder === 'asc' ? '↑' : '↓');
        resetSortIcons('avgFat');
        var rows = $('#recovery_grid tbody tr').get();
        rows.sort(function(a, b) {
            var fatA = parseFloat($(a).find('td').eq(5).text()); // Avg. FAT column index is 5
            var fatB = parseFloat($(b).find('td').eq(5).text());
            if (isNaN(fatA)) fatA = 0;
            if (isNaN(fatB)) fatB = 0;
            return (sortOrder === 'asc') ? fatA - fatB : fatB - fatA;
        });
        $.each(rows, function(index, row) {
            $('#recovery_grid tbody').append(row);
        });
    });
    $('#avgSnfHeader').click(function() {
        sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
        $('#avgSnfSortIcon').text(sortOrder === 'asc' ? '↑' : '↓');
        resetSortIcons('avgSnf');
        var rows = $('#recovery_grid tbody tr').get();
        rows.sort(function(a, b) {
            var fatA = parseFloat($(a).find('td').eq(6).text()); // Avg. SNF column index is 6
            var fatB = parseFloat($(b).find('td').eq(6).text());
            if (isNaN(fatA)) fatA = 0;
            if (isNaN(fatB)) fatB = 0;
            return (sortOrder === 'asc') ? fatA - fatB : fatB - fatA;
        });
        $.each(rows, function(index, row) {
            $('#recovery_grid tbody').append(row);
        });
    });
});
";
$this->registerJs($script, View::POS_READY, 'union-wise-data');
?>