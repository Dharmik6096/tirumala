<div class="col-sm-6">
    <div class="milk-collection">
        <div class="table-responsive dashboard_tbl">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="4" class="dashboardWidgetHeader">Monthly Milk Collection</th> 
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Union') ?></th>
                        <th class="dashboardWidgetDetailPortion">Villages</th>
                        <th class="dashboardWidgetDetailPortion">No of Pourers</th>
                        <th class="dashboardWidgetDetailPortion">Monthly Milk Collection(ltr)</th>
                    </tr>
                </thead>
                <?php
                if (!empty($monthly_milk_collection)) {
                    foreach ($monthly_milk_collection as $milk_collection) {
                        ?>
                        <tr>
                            <td><?= $milk_collection['union_name'] ?></td>
                            <td><?= $milk_collection['dcs_name'] ?></td>
                            <td><?= $milk_collection['Member_Count'] ?></td>
                            <td><?= $milk_collection['Qty'] ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr><td colspan="4">Data not available.</td></tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>