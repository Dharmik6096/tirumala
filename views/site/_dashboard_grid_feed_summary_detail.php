<?php

use app\components\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Feed Summary Dashboard'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
        <button onclick="exportThisWithParameter('recovery_grid', '<?= $this->title ?>')" type="button" class="headerIcon btn btn-danger apply-shortcut btn-block right_30" ><i class="fa fa-file-excel-o"></i></button> 
        <button id="addButtonsss" class="btn btn-primary plus-button headerIcon"><i class="fa fa-search"></i></button>
    </div>
    <div class="panel-body overflow_visible">
        <div class="modal fade" id="feedSummaryModel" tabindex="-1" role="dialog" aria-labelledby="feedSummaryModelLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedSummaryModelLabel"><?= Yii::t('app', 'Feed Summary Dashboard'); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body inline_block">
                        <?php
                        $form = ActiveForm::begin([
                                    'action' => Url::to(['feed-summary-dashboard-details']),
                                    'id' => 'opening_form'
                        ]);
                        ?>
                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union', 'Union'); ?>
                        </div>  
                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->dropdown('state_code', $model, $form, '', Yii::t('app', 'State'), false, '', false, FALSE, FALSE, true); ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->depend_dropdown('region_code', $model, $form, 'dashboard-state_code', 'form-group col-sm-12', 'Region Name', 'region_code', false, 0, [], TRUE, '', false, FALSE, TRUE, false, TRUE); ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->depend_dropdown('area_code', $model, $form, 'dashboard-region_code', 'form-group col-sm-12', 'Area Name', 'area_code', false, 0, [], TRUE, '', false, FALSE, TRUE, false, TRUE); ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->area_bmc($model, $form, 'dashboard-area_code', 'area_bmc_code', Yii::t('app', 'BMC'), TRUE, FALSE, FALSE, FALSE, TRUE); ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Yii::$app->controls->date($model, $form, 'month_year', '', true, false, false, TRUE, false, '', TRUE); ?>
                        </div>
                        <div class="col-sm-2 dashboard_modal_footer pt5">
                            <?= Yii::$app->controls->custombutton('Apply', 'feed-summary-dashboard-details', false, 'openingBalance btn-login'); ?>
                            <?= Yii::$app->controls->reset(); ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 farmer_rmrd_block">
            <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
                <p class="dash_block_header"><?= Yii::t('app', 'Opening Balance') ?></p>
                <h4 class="dash_block_value block_value" id="opening_balance"><?= $blocks_data[0][0]['opening_balance'] ?></h4>
            </div>
            <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
                <p class="dash_block_header"><?= Yii::t('app', 'Received') ?></p>
                <h4 class="dash_block_value block_value" id="received"><?= $blocks_data[0][0]['received'] ?></h4>
            </div>
            <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
                <p class="dash_block_header"><?= Yii::t('app', 'Inventory Transfer') ?></p>
                <h4 class="dash_block_value block_value" id="inventory_transfer"><?= $blocks_data[0][0]['inventory_transfer'] ?></h4>
            </div>
            <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
                <p class="dash_block_header"><?= Yii::t('app', 'Sale') ?></p>
                <h4 class="dash_block_value block_value" id="sale"><?= $blocks_data[0][0]['sale'] ?></h4>
            </div>
            <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
                <p class="dash_block_header"><?= Yii::t('app', 'Sale Return') ?></p>
                <h4 class="dash_block_value block_value" id="sale_return"><?= $blocks_data[0][0]['sale_return'] ?></h4>
            </div>
            <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
                <p class="dash_block_header"><?= Yii::t('app', 'Balance Qty') ?></p>
                <h4 class="dash_block_value block_value" id="balance_qty"><?= $blocks_data[0][0]['balance_qty'] ?></h4>
            </div>
        </div>
        <div id="recovery_grid" class="col-sm-6">
            <div id="dash_collapse_grid">
                <div class="col-sm-12">
                    <div class="table-responsive height_grid_f dashboard_collection_grid_tbl">
                        <table class="table overflow_hidden table-striped">
                            <thead>
                                <tr>
                                    <th class="custom_grid_header">#</th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Bmc Name') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Bmc Ref Code') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Product Code') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Product Name') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Opening Balance') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Received') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Inventory Transfer') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Sale') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Sale Return') ?></th>
                                    <th class="custom_grid_header"><?= Yii::t('app', 'Balance Qty') ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($output)) {
                                    $i = 0;
                                    foreach ($output as $data) {
                                        ?>
                                        <tr>
                                            <td class="custom_grid_normal"><?= ++$i; ?></td>

                                            <td class="grid_left_align custom_grid_normal"><?= $data['bmc_name'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['ref_code'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['product_code'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['product_name'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['opening_balance'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['received'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['inventory_transfer'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['sale'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['sale_return'] ?></td>
                                            <td class="grid_left_align custom_grid_normal"><?= $data['balance_qty'] ?></td>

                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
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

<?php
$script = <<<JS
$('#addButtonsss').on('click', function() {
    $('#feedSummaryModel').modal('show');
});

$(document).on('click', '.openingBalance', function(e) {
    e.preventDefault();
    var form = $('#opening_form');
    var data = form.serialize();
    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: data,
        dataType: 'json',
        success: function(data) {
            if (data.status === 'success') {
                for (var key in data.res) {
                    if (data.res[key] == null) {
                        data.res[key] = 0;
                    }
                }
                $('#opening_balance').text(data.res.opening_balance);
                $('#received').text(data.res.received);
                $('#inventory_transfer').text(data.res.inventory_transfer);
                $('#sale').text(data.res.sale);
                $('#sale_return').text(data.res.sale_return);
                $('#balance_qty').text(data.res.balance_qty);

                var rows = '';
                if (data.output && data.output.length > 0) {
                    for (var i = 0; i < data.output.length; i++) {
                        rows += '<tr>' +
                            '<td class="custom_grid_normal">' + (i + 1) + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].bmc_name || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].ref_code || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].product_code || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].product_name || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].opening_balance || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].received || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].inventory_transfer || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].sale || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].sale_return || '') + '</td>' +
                            '<td class="grid_left_align custom_grid_normal">' + (data.output[i].balance_qty || '') + '</td>' +
                            '</tr>';
                    }
                } else {
                    rows = '<tr><td colspan="3" class="text-center">No data found</td></tr>';
                }
                $('#recovery_grid tbody').html(rows);
                $('#feedSummaryModel').modal('hide');
            } else {
                alert('Failed to fetch data');
            }
        },
        error: function() {
            alert('Error occurred while fetching data');
        }
    });
});
$(document).on("click", "button[type='reset']", function() {
    $("#dashboard-state_code").val('').trigger("change");
});
JS;

$this->registerJs($script, \yii\web\View::POS_READY, 'opening_balance_a');
?>