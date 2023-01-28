<table class="table table-bordered">
    <thead>
        <tr>
            <th>Type</th>
            <th>Milk Type</th>
            <th>Quality Type</th>
            <th>Silo No.</th>                 
            <th>Purchase Qty</th>
            <th>Balance Qty</th>                   
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $data) { ?>
            <tr>
                <td><?= $data['coll_type'] ?></td>
                <td><?= $data['animal_type_name'] ?></td>
                <td><?= $data['milk_quality_type_name'] ?></td>
                <td><?= $data['silo_no'] ?></td>              
                <td><?= $data['purchase_qty'] ?></td>
                <td><?= $data['previous_qty'] ?></td>                
            </tr>
        <?php } ?>
    </tbody>
</table>