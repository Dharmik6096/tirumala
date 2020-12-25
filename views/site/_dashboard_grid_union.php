<?php

use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
use Symfony\Component\Console\Input\Input;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$class_cols = 'col-sm-3';

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Unions'));
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
    
                <div class="col-sm-6 ">
                <!-- <button>Toggle between hide() and show()</button> -->
                <div class="col-sm-12">
                    <div class="grid_card">
                        <div class="carousel-inner">
                            <?php for ($i=1; $i <=9 ; $i++) { 
                                $active = '';
                                if ($i==1){
                                    $active = 'active';
                                }
                                ?>
                                <?php
                                if ($i % 6==0){
                                    ?></div><?php }
                                if ($i % 6==0 || $i == 1){?>
                                    <div class="carousel-item <?=$active?>">
                                <?php
                                    }
                                ?>
                                <div class="div_grid_block padding_left_0 padding_right_0 padding_top_0 dashboardWidgetDetailPortion col-sm-3">
                                    <div class="div_grid_block_content">
                                        <!-- <p class="dash_grid_block_header"><?= Yii::t('app', 'Union') ?></p> -->
                                        <h4 class="dash_grid_block_header">Namaste India Pvt Ltd</h4>
                                        <!-- <h4 class="dash_block_value block_value" id="farmer_rmrd_block_mcc"></h4> -->
                                    </div>
                                    <div class="row ">
                                        <div class="col-sm-12 padding_left_0 padding_right_0 border_top_1">
                                            <div class="col-sm-4 dash_grid_block_desc text_center border_right_1 pt_4"><span class="dash_grid_block_ans">Quantity</span></div>
                                            <div class="col-sm-4 dash_grid_block_desc text-center border_right_1 pt_4"><span class="dash_grid_block_ans">Avg. FAT/SNF</span></div>
                                            <div class="col-sm-4 dash_grid_block_desc text-center pt_4 border_top_1"><span class="dash_grid_block_ans">Avg. Rate</span></div>
                                            <div class="col-sm-4 dash_grid_block_desc text-center border_right_1">
                                                <div class="width_grid_dash">
                                                    <span class="dash_grid_block_desc_title">
                                                        15
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 dash_grid_block_desc text-center border_right_1">
                                                <div class="width_grid_dash">
                                                    <span class="dash_grid_block_desc_title">
                                                        15
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 dash_grid_block_desc text-center ">
                                                <div class="width_grid_dash">
                                                    <span class="dash_grid_block_desc_title">
                                                        15
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 padding_left_0 padding_right_0 border_bottom_1">
                                            <div class="col-sm-4 padding_left_0 padding_right_0">
                                                <div class="col-sm-12 dash_grid_block_desc text-center border_right_1 pt_4 border_top_1"><span class="dash_grid_block_ans">Amount</span></div>
                                                <div class="col-sm-12 dash_grid_block_desc text-center border_right_1"><span class="dash_grid_block_desc_title"><span>15</span></span></div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="col-sm-4 dash_grid_block_desc text-center pt_4"><span class="dash_grid_block_ans">MCC</span></div>
                                                <div class="col-sm-4 dash_grid_block_desc text-center pt_4"><span class="dash_grid_block_ans">Society</span></div>
                                                <div class="col-sm-4 dash_grid_block_desc text-center pt_4"><span class="dash_grid_block_ans">Farmer</span></div>
                                                <div class="col-sm-4 dash_grid_block_desc text-center "><span class="dash_grid_block_desc_title"><span>10.10</span></span></div>
                                                <div class="col-sm-4 dash_grid_block_desc text-center "><span class="dash_grid_block_desc_title"><span>12</span></span></div>
                                                <div class="col-sm-4 dash_grid_block_desc text-center "><span class="dash_grid_block_desc_title"><span>12</span></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 dash_grid_block_link"><span><?= Yii::t('app', 'MCC') ?></span><br><div class="margin-top-5 padding_l_15"><div class="dash_grid_link_count"><span>1</span></div></div></div>
                                        <div class="col-sm-4 dash_grid_block_link"><span><?= Yii::t('app', 'Society') ?></span><br><div class="margin-top-5 padding_l_15"><div class="dash_grid_link_count"><span>1</span></div></div></div>
                                        <div class="col-sm-4 dash_grid_block_link"><span><?= Yii::t('app', 'Farmer') ?></span><br><div class="margin-top-5 padding_l_15"><div class="dash_grid_link_count"><span>3000</span></div></div></div>
                                    </div>
                                </div>
                                <?php
                                } ?>
                        </div>
                    </div>
                    </div>

                    <div class="col-sm-12">
                    <div class="table-responsive dashboard_collection_grid_tbl">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= Yii::t('app', 'Union') ?></th>
                                    <th><?= Yii::t('app', 'MCC') ?></th>
                                    <th><?= Yii::t('app', 'Society') ?></th>
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
                                            $tbl_union_model = new TblUnions();
                                            $tbl_union_model->union_code = $data['union_code'];
                                        ?>
                                        <td class="grid_left_align"><?= Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name') ?></td>
                                        <?php $url = Url::to(['site/get-mccs', 'date' => $date, 'union_code' => $data['union_code']]);?>
                                        <td><a href="<?= $url; ?>"><?= $data['total_mcc'] ?></a></td>
                                        <?php $url = Url::to(['site/get-dcs', 'date' => $date, 'union_code' => $data['union_code']]);?>
                                        <td><a href="<?= $url; ?>"><?= $data['total_dcs'] ?></a></td>
                                        <?php $url = Url::to(['site/get-farmers', 'date' => $date, 'union_code' => $data['union_code']]);?>
                                        <td><a href="<?= $url; ?>"><?= $data['total_farmers'] ?></a></td>
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

<?php
$script = "  
$('button').click(function(){
    $('.dashboard_collection_grid_tbl').toggle();
});
";
$this->registerJs($script, View::POS_READY, 'union-wise-data');
?>