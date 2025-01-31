<h5 class="panel-heading mb15"><?= Yii::t('app', 'Dispatch Summary') ?></h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Challan No.</th>
            <th>From Date</th>
            <th>From Shift</th>   
            <th>To Date</th>
            <th>To Shift</th>                   
            <th>Vehicle No.</th> 
            <th>Dispatch Qty.</th>  
            <th>Fat.</th>  
            <th>Snf.</th>  
            <th>In Time</th>                   
            <th>Out Time</th>                   
            <th>Action</th>                   
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($existData as $data) {
            $data = (object) $data;
            ?>
            <tr>
                <td><?= $data->challan_no ?></td>
                <td><?= Yii::$app->controls->view_date($data->from_date) ?></td>
                <td><?= $data->from_shift ?></td>  
                <td><?= Yii::$app->controls->view_date($data->to_date) ?></td>                
                <td><?= $data->to_shift ?></td>                  
                <td><?= $data->parsing_no ?></td> 
                <td><?= $data->gross_weight ?></td>
                <td><?= $data->fat ?></td> 
                <td><?= $data->snf ?></td> 
                <td><?= Yii::$app->controls->view_time($data->vehicle_in_time) ?></td>                
                <td><?= Yii::$app->controls->view_time($data->vehicle_out_time) ?></td>     
                <td>
                    <a href="<?= yii\helpers\Url::to(['/tankermovement/tbl-bmc-milk-dispatch/view', 'id' => $data->bmc_milk_dispatch_code]) ?>" 
                       class="text-primary" 
                       data-toggle="tooltip" 
                       data-placement="top" 
                       title="<?= Yii::t('app', 'View') ?>" target="_blank"> <i class="fa fa-eye"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>