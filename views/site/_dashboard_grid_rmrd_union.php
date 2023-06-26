<?php

use app\modules\organisation\models\TblUnions;
use app\modules\usermanagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
use Symfony\Component\Console\Input\Input;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$class_cols = 'col-sm-3';

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Unions'));
$union = !empty($output[0]['union_code']) ? $output[0]['union_code'] : '';
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $breadcrum_title . $this->title; ?>
        <button type="button" class="headerIcon gread_header_icon btn btn-danger apply-shortcut btn-block" data-toggle="collapse" data-target=".grid_card"><i class="fa fa-list"></i></button>
        <span class="right_align_date"><?= Yii::$app->controls->view_date($date) ?></span>
    </div>
    <div class="panel-body hide-grid-export">
        <div id="plant-list" class="grid-content">
            <div id="plant-list">
                <div id="w12" class="grid-view hide-resize" >
                    <div class="panel panel-default">

                        <div class="col-sm-6 farmer_rmrd_block">
                            <?php
                            echo $this->render('_dashboard_grid_rmrd_block', ['date' => $date, 'class_cols' => $class_cols, 'blocks_data' => $blocks_data, 'union' => $union]);
                            ?>
                        </div>

                        <div class="col-sm-6 ">
                            <!-- <button>Toggle between hide() and show()</button> -->
                            <div class="col-sm-12">
                                <div class="grid_card collapse">
                                    <?php
                                    if (!empty($output)) {
                                        foreach ($output as $data) {
                                            // $tbl_union_model = new TblUnions();
                                            // $tbl_union_model->union_code = $data['union_code'];
                                            ?>
                                            <div class="div_grid_block padding_left_0 padding_right_0 padding_top_0 dashboardWidgetDetailPortion col-sm-3">
                                                <div class="div_grid_block_content">
                                                    <!-- <p class="dash_grid_block_header"><?= Yii::t('app', 'Union') ?></p> -->
                                                    <h4 class="dash_grid_block_header"><?= $data['union_name'] ?> - <?= $data['union_code'] ?></h4>
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
                                                            <div class="col-sm-4 dash_grid_block_desc text-center"><span class="dash_grid_block_ans"><?= Yii::t('app', 'MCC') ?></span></div>
                                                            <div class="col-sm-4 dash_grid_block_desc text-center"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Society') ?></span></div>
                                                            <?php $url = Url::to(['site/get-rmrd-mccs', 'date' => $date, 'union_code' => $data['union_code'], 'widget_for' => 'rmrd']); ?>
                                                            <div class="col-sm-4 dash_grid_block_desc text-center href_link_underline"><a href="<?= $url; ?>" class='href_link' ><span class="dash_grid_block_desc_title"><span><?= $data['total_mcc'] ?></span></span></a></div>
                                                            <?php $url = Url::to(['site/get-rmrd-dcs', 'date' => $date, 'union_code' => $data['union_code'], 'widget_for' => 'rmrd']); ?>
                                                            <div class="col-sm-4 dash_grid_block_desc text-center href_link_underline"><a href="<?= $url; ?>" class='href_link' ><span class="dash_grid_block_desc_title"><span><?= $data['total_dcs'] ?></span></span></a></div>
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
                                        <table class="table overflow_hidden table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="custom_grid_header">#</th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Union') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'MCC') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Society') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Qty') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Avg. FAT') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Avg. SNF') ?></th>
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
                                                            // $tbl_union_model = new TblUnions();
                                                            // $tbl_union_model->union_code = $data['union_code'];
                                                            // $union_name =  !empty(Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name'))? Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name') : 'N/A';
                                                            ?>
                                                            <td class="grid_left_align custom_grid_normal" title="<?= $data['union_name'] ?>"><?= $data['union_short_name'] ?> - <?= $data['union_code'] ?></td>
                                                            <?php $url = Url::to(['site/get-rmrd-mccs', 'date' => $date, 'union_code' => $data['union_code']]); ?>
                                                            <td class="number_align custom_grid_normal href_link_underline"><a href="<?= $url; ?>" ><?= $data['total_mcc'] ?></a></td>
                                                            <?php $url = Url::to(['site/get-rmrd-dcs', 'date' => $date, 'union_code' => $data['union_code']]); ?>
                                                            <td class="number_align custom_grid_normal href_link_underline"><a href="<?= $url; ?>" ><?= $data['total_dcs'] ?></a></td>
                                                            <td class="number_align custom_grid_normal "><?= $data['total_quantity'] ?></td>
                                                            <td class="number_align custom_grid_normal "><?= $data['avgFAT'] ?></td>
                                                            <td class="number_align custom_grid_normal "><?= $data['avgSNF'] ?></td>
                                                            <td class="number_align custom_grid_normal "><?= $data['avgRate'] ?></td>
                                                            <td class="number_align custom_grid_normal "><?= $data['total_amount'] ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr><td colspan="10">No Data Available.</td></tr>
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
";
$this->registerJs($script, View::POS_READY, 'union-wise-data');
?>