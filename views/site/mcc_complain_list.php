<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
use Symfony\Component\Console\Input\Input;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$class_cols = 'col-sm-4';

$this->title = Yii::t('app', 'Complain List');
$date = date('Y-m-d');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $breadcrum_title ?>
        <button type="button" class="headerIcon gread_header_icon btn btn-danger apply-shortcut btn-block"><i class="fa fa-list"></i></button>
        <span class="right_align_shift"><?= Yii::$app->controls->view_date($date) ?></span>
    </div>
    <div class="panel-body hide-grid-export overflow_visible">
        <div id="plant-list" class="grid-content">
            <div id="plant-list">
                <div id="w12" class="grid-view hide-resize" >
                    <div class="panel panel-default">

                        <div class="col-sm-6 farmer_rmrd_block">
                            <?php
                            echo $this->render('_dashboard_grid_complain_block', ['date' => $date, 'class_cols' => $class_cols, 'blockData' => $blockData]);
                            ?>
                        </div>

                        <div class="col-sm-6 ">
                            <div class="col-sm-12">
                                <div class="grid_card collapse">
                                    <?php
                                    if (!empty($tableData)) {
                                        foreach ($tableData as $data) {
                                            ?>
                                            <div class="div_grid_block padding_left_0 padding_right_0 padding_top_0 dashboardWidgetDetailPortion col-sm-3">
                                                <div class="div_grid_block_content">
                                                    <h4 class="dash_grid_block_header"><?= Html::encode($data['plant_name']) ?></h4>
                                                    <h4 class="dash_grid_block_header"><?= Html::encode($data['mcc_name']) ?></h4>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12 padding_left_0 padding_right_0 border_top_1">
                                                        <div class="col-sm-6 dash_grid_block_desc text_center border_right_1 pt_4"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Total') ?></span></div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center pt_4"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Created') ?></span></div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center border_right_1">
                                                            <span class="dash_grid_block_desc_title"><?= $data['Total_Complain'] ?></span>
                                                        </div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center">
                                                            <span class="dash_grid_block_desc_title"><?= $data['complaint_created'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 padding_left_0 padding_right_0 border_top_1">
                                                        <div class="col-sm-6 dash_grid_block_desc text_center border_right_1 pt_4"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Assigned') ?></span></div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center pt_4"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Inprogress') ?></span></div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center border_right_1">
                                                            <span class="dash_grid_block_desc_title"><?= $data['complaint_assigend'] ?></span>
                                                        </div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center">
                                                            <span class="dash_grid_block_desc_title"><?= $data['complaint_inprogress'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 padding_left_0 padding_right_0 border_bottom_1 border_top_1">
                                                        <div class="col-sm-6 dash_grid_block_desc text_center border_right_1 pt_4"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Resolved') ?></span></div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center pt_4"><span class="dash_grid_block_ans"><?= Yii::t('app', 'Closed') ?></span></div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center border_right_1">
                                                            <span class="dash_grid_block_desc_title"><?= $data['complaint_resolved'] ?></span>
                                                        </div>
                                                        <div class="col-sm-6 dash_grid_block_desc text-center">
                                                            <span class="dash_grid_block_desc_title"><?= $data['complaint_closed'] ?></span>
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
                                        <table class="table overflow_hidden table-striped" style="white-space: nowrap;">
                                            <thead>
                                                <tr>
                                                    <th class="custom_grid_header">#</th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Union') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Plant Name') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'MCC Ref Code') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'MCC Name') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Total Complain') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Created') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Assigned') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Inprogress') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Resolved') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'Closed') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($tableData)) {
                                                    $i = 0;
                                                    foreach ($tableData as $data) {
                                                        $url = Url::to([
                                                            '/complaint/tbl-complain/index', 
                                                            'TblComplainSearch' => [
                                                                'f_union_code' => $data['union_code'], 
                                                                'f_plant_code' => $data['plant_code'], 
                                                                'f_mcc_code' => $data['mcc_plant_code']
                                                            ]
                                                        ]);
                                                        ?>
                                                        <tr>
                                                            <td class="custom_grid_normal"><?= ++$i; ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= Html::encode($data['union_name']) ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= Html::encode($data['plant_name']) ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= Html::encode($data['mcc_ref_code']) ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= Html::encode($data['mcc_name']) ?></td>
                                                            <td class="number_align custom_grid_normal href_link_underline"><a href="<?= $url ?>"><?= Html::encode($data['Total_Complain']) ?></a></td>
                                                            <td class="number_align custom_grid_normal"><?= Html::encode($data['complaint_created']) ?></td>
                                                            <td class="number_align custom_grid_normal"><?= Html::encode($data['complaint_assigend']) ?></td>
                                                            <td class="number_align custom_grid_normal"><?= Html::encode($data['complaint_inprogress']) ?></td>
                                                            <td class="number_align custom_grid_normal"><?= Html::encode($data['complaint_resolved']) ?></td>
                                                            <td class="number_align custom_grid_normal"><?= Html::encode($data['complaint_closed']) ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr><td colspan="100">No Data Available.</td></tr>
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
    $('.grid_card').toggle();

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
