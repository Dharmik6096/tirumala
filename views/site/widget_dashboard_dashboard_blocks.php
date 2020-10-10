<div class="col-sm-12">
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'No. of Societies') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h4 id="no_of_societies"><?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count'] : 0 ?></h4>
                        <p><b>M:</b> <span id="no_of_societies_M"><?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count_M'] : 0 ?></span> | <b>E:</b> <span id="no_of_societies_E"><?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count_E'] : 0 ?></span></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">                                
                    <div class="tbl-cell">
                        <p>No. of Pourers</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h4 id="no_of_pourers"><?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Total_Member'] : 0 ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'Collection vs Installed') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <p><h4 id="collection_vs_installed"><?= (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count'] : 0) . '/' . (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Install_Count'] : 0) ?></h4></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'Collection vs Dispatch') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <p><h4 id="collection_vs_dispatch"><?= (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count'] : 0) . '/' . (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_DisQty_total'] : 0) ?></h4></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'Dispatch vs Receipt') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <p><h3 id="dispatch_vs_receipt_block"><?= (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_DisQty_total'] : 0) . '/' . (!empty($dashboard_blocks) ? $dashboard_blocks[0]['bmc_dcs_Count'] : 0) ?></h3></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total Milk Collection(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3 id="total_milk_collection_ltr"><?= !empty($dashboard_blocks) && !empty($dashboard_blocks[0]['UnionQty']) ? '<span title=\'Quantity\'>' . $dashboard_blocks[0]['UnionQty'] . '</span>/<span title=\'Avg. FAT\'>' . $dashboard_blocks[0]['union_avg_fat'] . '</span>/<span title=\'Avg. SNF\'>' . $dashboard_blocks[0]['union_avg_snf'] . '</span>' : 0 ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total Milk Dispatch(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3 id="total_milk_dispatch_ltr"><?= !empty($dashboard_blocks) && !empty($dashboard_blocks[0]['UnionDisQty']) ? '<span title=\'Quantity\'>' . $dashboard_blocks[0]['UnionDisQty'] . '</span>/<span title=\'Avg. FAT\'>' . $dashboard_blocks[0]['union_dis_avg_fat'] . '</span>/<span title=\'Avg. SNF\'>' . $dashboard_blocks[0]['union_dis_avg_snf'] . '</span>' : 0 ?></h3>
                        <p><b>M:</b> <span id="total_milk_dispatch_M"><?= !empty($dashboard_blocks) && !empty($dashboard_blocks[0]['Dcs_DisQty_M']) ? $dashboard_blocks[0]['Dcs_DisQty_M'] : 0 ?></span> | <b>E:</b><span id="total_milk_dispatch_E"> <?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_DisQty_E'] : 0 ?></span></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total BMC Collection(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h4 id="total_bmc_collection_ltr"><?= !empty($dashboard_blocks) && !empty($dashboard_blocks[0]['BmcQty']) ? '<span title=\'Quantity\'>' . $dashboard_blocks[0]['BmcQty'] . '</span>/<span title=\'Avg. FAT\'>' . $dashboard_blocks[0]['bmc_avg_fat'] . '</span>/<span title=\'Avg. SNF\'>' . $dashboard_blocks[0]['bmc_avg_snf'] . '</span>' : 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>