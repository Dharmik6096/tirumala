<table class="route-table">
    <tbody>
        <tr><th>Trip Code</th><td><?= $trip['trip_code'] ?></td></tr>
        <tr><th>Date</th><td><?= $trip['transaction_date'] ?></td></tr>
        <tr><th>Driver</th><td><?= $trip['driver_name'] ?></td></tr>
        <tr><th>Mobile No</th><td><?= $trip['mobile_no'] ?></td></tr>
        <tr><th>Status</th><td><span><?= $trip['status'] ?></span></td></tr>
    </tbody>
</table>
<div class="panel-heading">Route Details</div>
<?php if (!empty($trip['routes'])): ?>
    <table class="route-table">
        <thead>
            <tr>
                <th>Source</th>
                <th>Source Ref Code</th>
                <th>Dest.</th>
                <th>Dest. Ref Code</th>
                <th>Arrival</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($trip['routes'] as $model): 
                $sourceStr = $model->source_org_code;
                if (!empty($model->source_org_type)) {
                    $responseSrc = Yii::$app->general->getColumnName($model->source_org_type);
                    if (!empty($responseSrc['rel'])) {
                        $data = $model->{$responseSrc['rel'] . 'Source'};
                        if (!empty($data)) {
                            $sourceStr = $data->{$responseSrc['name']} . '-' . strtoupper($model->source_org_type);
                        }
                    }
                }

                $sourceRefStr = $model->source_org_code;
                if (!empty($model->source_org_type)) {
                    $responseSrc = Yii::$app->general->getColumnName($model->source_org_type);
                    if (!empty($responseSrc['rel'])) {
                        $data = $model->{$responseSrc['rel'] . 'Source'};
                        if (!empty($data)) {
                            $sourceRefStr = $data->{$responseSrc['ref_code']};
                        }
                    }
                }
                
                $destStr = $model->destination_code;
                if (!empty($model->destination_type)) {
                    $responseDest = Yii::$app->general->getColumnName($model->destination_type);
                    if (!empty($responseDest['rel'])) {
                        $data = $model->{$responseDest['rel'] . 'Dest'};
                        if (!empty($data)) {
                            $destStr = $data->{$responseDest['name']} . '-' . strtoupper($model->destination_type);
                        }
                    }
                }

                $destRefStr = $model->destination_code;
                if (!empty($model->destination_type)) {
                    $responseDest = Yii::$app->general->getColumnName($model->destination_type);
                    if (!empty($responseDest['rel'])) {
                        $data = $model->{$responseDest['rel'] . 'Dest'};
                        if (!empty($data)) {
                            $destRefStr = $data->{$responseDest['ref_code']};
                        }
                    }
                }
                
                $arrivalStr = empty($model->arrival_time) ? 'Not Yet Arrived' : Yii::$app->controls->view_datetime($model->arrival_time);
            ?>
            <tr>
                <td><?= $sourceStr ?></td>
                <td><?= $sourceRefStr ?></td>
                <td><?= $destStr ?></td>
                <td><?= $destRefStr ?></td>
                <td><?= $arrivalStr ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-muted text-center mt-2">No routes found</p>
<?php endif; ?>
