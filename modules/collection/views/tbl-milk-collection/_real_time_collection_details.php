<div class="row">
    <!--    <div class="col-md-12">
            <div class="heading text-center">Online MCC Collection Live</div>
        </div>-->
    <div class="col-md-6">
        <div class="weight-data">
            <div class="heading text-center">MCC Weight Data</div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>VLC Code</th>
                        <th>Route Code</th>
                        <th>Sample No</th>
                        <th>Dock Number</th>
                        <th>Qty</th>
                        <th>Milk Quality Type</th>
                        <th>Milk Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($mcc_weight_data)) {
                        $i = 0;
                        foreach($mcc_weight_data as $key => $weight) {
                            $class = '';
                            if ($i < 5) {
                                $class = 'highlight';
                            }
                            $i++;
                            ?>
                            <tr class="<?= $class; ?>">
                                <td><?= $weight['dcs_code']; ?></td>                                
                                <td><?= $weight['route_code']; ?></td>                                
                                <td><?= $weight['sample_no']; ?></td>                                
                                <td><?= $weight['doc_no']; ?></td>                                
                                <td><?= $weight['qty']; ?></td>                                
                                <td><?= $weight['milk_quality_type']; ?></td>                                
                                <td><?= $weight['milk_type']; ?></td>                              
                            </tr>
                        <?php }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7">Record not found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">        
        <div class="quality-data">
            <div class="heading text-center">MCC Quality Data</div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <!--<th>Route Code</th>-->
                        <th>Sample No</th>
                        <th>Dock Number</th>
                        <th>FAT</th>
                        <th>SNF</th>
                        <th>CLR</th>
                        <th>PR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($mcc_quality_data)) {
                        $i = 0;
                        foreach ($mcc_quality_data as $key => $quality) {
//                        for ($i = 0; $i < 10; $i++) {
                            $class = '';
                            if ($i < 5) {
                                $class = 'highlight';
                            }
                            $i++;
                            ?>
                            <tr class="<?= $class; ?>">
                                <!--<td><?php // $quality['route_code']; ?></td>-->
                                <td><?= $quality['sample_no']; ?></td>
                                <td><?= $quality['doc_no']; ?></td>
                                <td><?= $quality['fat']; ?></td>
                                <td><?= $quality['snf']; ?></td>
                                <td><?= $quality['clr']; ?></td>
                                <td><?= $quality['protein']; ?></td>                                 
                            </tr>
                        <?php }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7">Record not found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

