<div class="dashboard_milk_analysis margin_bottom_10">
    <div class="table-responsive overflow_hidden dashboard_tbl dashboard_table_section">
        <div class="dynamic_report_table overflow_auto dynamic_report_margin">
            <table id="custom_report_1" class="fht-table table table-striped dashboardMilkAnalysis_grid">
                <thead class="dashboardWidgetDetailPortion">
                        <tr>
                            <!-- <th rowspan='2'><?php //Yii::t('app', 'MCC') ?></th> -->
                            <th rowspan='2'><?= Yii::t('app', 'BMC') ?></th>
                            <th colspan='9'><?= Yii::t('app', 'CC Collection') ?></th>
                            <th colspan='6'><?= Yii::t('app', 'BMC Receipts') ?></th>
                            <th colspan='6'><?= Yii::t('app', 'Vendor Receipts') ?></th>
                            <th colspan='6'><?= Yii::t('app', 'Total') ?></th>
                            <th colspan='5'><?= Yii::t('app', 'CC Differences') ?></th>
                        </tr>
                        <tr>
                            <th><?= Yii::t('app', 'Qty') ?></th>
                            <th><?= Yii::t('app', 'FAT') ?></th>
                            <th><?= Yii::t('app', 'SNF') ?></th>
                            <th><?= Yii::t('app', 'Rate') ?></th>
                            <th><?= Yii::t('app', 'Amount') ?></th>
                            <th><?= Yii::t('app', 'CC Count') ?></th>
                            <th><?= Yii::t('app', 'Online') ?></th>
                            <th><?= Yii::t('app', 'Pendrive') ?></th>
                            <th><?= Yii::t('app', 'Manual') ?></th>

                            <th><?= Yii::t('app', 'Qty') ?></th>
                            <th><?= Yii::t('app', 'FAT') ?></th>
                            <th><?= Yii::t('app', 'SNF') ?></th>
                            <th><?= Yii::t('app', 'Rate') ?></th>
                            <th><?= Yii::t('app', 'Amount') ?></th>
                            <th><?= Yii::t('app', 'Count') ?></th>

                            <th><?= Yii::t('app', 'Qty') ?></th>
                            <th><?= Yii::t('app', 'FAT') ?></th>
                            <th><?= Yii::t('app', 'SNF') ?></th>
                            <th><?= Yii::t('app', 'Rate') ?></th>
                            <th><?= Yii::t('app', 'Amount') ?></th>
                            <th><?= Yii::t('app', 'Count') ?></th>

                            <th><?= Yii::t('app', 'Qty') ?></th>
                            <th><?= Yii::t('app', 'FAT') ?></th>
                            <th><?= Yii::t('app', 'SNF') ?></th>
                            <th><?= Yii::t('app', 'Rate') ?></th>
                            <th><?= Yii::t('app', 'Amount') ?></th>
                            <th><?= Yii::t('app', 'Count') ?></th>

                            <th><?= Yii::t('app', 'Qty') ?></th>
                            <th><?= Yii::t('app', 'FAT') ?></th>
                            <th><?= Yii::t('app', 'SNF') ?></th>
                            <th><?= Yii::t('app', 'Amount') ?></th>
                            <th><?= Yii::t('app', 'Count') ?></th>
                        </tr>
                    </thead>
                    <tbody class="dashboardMilkAnalysis_tbody">
                        
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div onclick="exportThisWithParameter('custom_report_1', '<?= $this->title ?>')" class="widget_table_search_btn mis_custom_report"><i class="fa fa-file-excel-o"></i></div>