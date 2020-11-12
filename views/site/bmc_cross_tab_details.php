<div class="modal fade in" id="crossTabDetailsModal" role="dialog">
    <div class="modal-dialog w750">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title" id="modal-title">Data for <?= $bmc ?></h4>
            </div>
            <div class="modal-body" id="modal-body">
                <div class="milk-collection  h450">
                    <div class="table-responsive dashboard_tbl">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'DCS Code') ?></th>
                                    <th><?= Yii::t('app', 'DCS Name') ?></th>
                                    <th><?= Yii::t('app', 'Date') ?></th>
                                    <th><?= Yii::t('app', 'Sample Count') ?></th>
                                    <th><?= Yii::t('app', 'Qty') ?></th>
                                    <th><?= Yii::t('app', 'Avg FAT') ?></th>
                                    <th><?= Yii::t('app', 'Avg SNF') ?></th>
                                    <th><?= Yii::t('app', 'Amount') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($output)) {
                                    foreach ($output as $data) {
                                        ?>
                                        <tr>
                                            <td><?= $data['dcs_code']; ?></td>
                                            <td><?= $data['dcs_name']; ?></td>
                                            <td><?= $data['dtdate']; ?></td>
                                            <td><?= $data['SampleCount']; ?></td>
                                            <td><?= $data['Qty']; ?></td>
                                            <td><?= $data['AvgFAT']; ?></td>
                                            <td><?= $data['AvgSNF']; ?></td>
                                            <td><?= $data['Amount']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr><td colspan="8">No Data Available.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>