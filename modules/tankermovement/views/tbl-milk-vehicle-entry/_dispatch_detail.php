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
            <th>In Time</th>                   
            <th>Out Time</th>                   
        </tr>
    </thead>
    <tbody>
        <?php foreach ($existData as $data) {
            ?>
            <tr>
                <td><?= $data->challan_no ?></td>
                <td><?= Yii::$app->controls->view_date($data->from_date) ?></td>
                <td><?= Yii::$app->general->getforeignkey($data->fromShiftCode, 'shift') ?></td>  
                <td><?= Yii::$app->controls->view_date($data->to_date) ?></td>                
                <td><?= Yii::$app->general->getforeignkey($data->toShiftCode, 'shift') ?></td>                
                <td><?= Yii::$app->general->getforeignkey($data->vehicleCode, 'parsing_no') ?></td> 
                <td><?=$data->gross_weight?></td>
                <td><?= Yii::$app->controls->view_time($data->vehicle_in_time) ?></td>                
                <td><?= Yii::$app->controls->view_time($data->vehicle_out_time) ?></td>                               
            </tr>
        <?php } ?>
    </tbody>
</table>