<?php

use app\modules\organisation\models\TblDcs;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'DCS')).' of '.$mcc_name;
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
                <div class="milk-collection  h450">
                    <div class="table-responsive dashboard_collection_grid_tbl">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= Yii::t('app', 'Society') ?></th>
                                    <th><?= Yii::t('app', 'Farmer') ?></th>
                                    <th><?= Yii::t('app', 'Qty') ?></th>
                                    <th><?= Yii::t('app', 'Avg. FAT/SNF') ?></th>
                                    <th><?= Yii::t('app', 'Avg/Rate') ?></th>
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
                                            $tbl_dcs_model = new TblDcs();
                                            $tbl_dcs_model->dcs_code = $data['dcs_code'];
                                        ?>
                                        <td class="grid_left_align"><?= Yii::$app->general->getforeignkey($tbl_dcs_model->tblDcs, 'dcs_name') ?></td>
                                        <?php $url = Url::to(['site/get-farmers', 'date' => $date, 'union_code' => $union, 'mcc_code' => $mcc, 'bmc_code' => $bmc, 'dcs_code' => $data['dcs_code']]);?>
                                        <td><a href="<?= $url ?>"><?= $data['total_farmers'] ?></a></td>
                                        <td><?= $data['total_quantity'] ?></td>
                                        <td><?= $data['avgFAT'] ?></td>
                                        <td><?= $data['avgRate'] ?></td>
                                        <td><?= $data['total_amount'] ?></td>
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