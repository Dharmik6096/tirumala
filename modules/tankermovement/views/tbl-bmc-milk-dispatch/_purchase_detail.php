<?php

use yii\helpers\Html;
?>
<table class="table table-bordered" id="purchase_detail_tabel">
    <thead>
        <tr>
            <th>Type</th>
            <th>Milk Type</th>
            <th>Quality Type</th>
            <th>Silo No.</th>
            <th>Purchase Qty</th>
            <th>Opening Balance</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $data) { ?>
            <tr>
                <td><?= $data['coll_type'] ?></td>
                <td><?= $data['animal_type_name'] ?></td>
                <td><?= $data['milk_quality_type_name'] ?></td>
                <td><?= $data['silo_no'] ?></td>
                <td class="purchase_qty"><?= $data['purchase_qty'] ?></td>
                <td class="previous_qty"><?= $data['previous_qty'] ?></td>
            </tr>
        <?php } ?>
        <?php
        $stock_detail_json = json_encode($stock_detail);
        echo Html::hiddenInput('stockdetail', $stock_detail_json, ['id' => 'stockdetail']);
        ?>
    </tbody>
</table>