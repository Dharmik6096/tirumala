
<?php

use yii\helpers\Html;
?>
<table class="table table-bordered" id="purchase_detail_table">
    <thead>
        <tr>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Product Remaining Stock</th>         
            <th>Product Total Stock</th>         
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($stock_detail as $data) { ?>
            <tr id="<?= $data['product_code'] ?>">
                <td><?= $data['product_code'] ?></td>
                <td><?= $data['product_name'] ?></td>
                <td class="remaining_stock"><?= $data['total_stock'] ?></td>       
                <td class="total_stock"><?= $data['total_stock'] ?></td>       
            </tr>
        <?php
        }
        $stock_detail_json = json_encode($stock_detail);
        echo Html::hiddenInput('stockdetail', $stock_detail_json, ['id' => 'stockdetail']);
        ?>

    </tbody>
</table>
