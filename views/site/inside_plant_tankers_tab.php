<?php
use yii\helpers\Url;

$status = isset($selected_key_label) ? $selected_key_label : 'Waiting Tankers';

$config = [
    'Tanker at Cleaning'               => ['> 4 Hr', '> 3 Hr', '> 2 Hr', '> 1 Hr', '< 1 Hr'],
    'Tanker at Quality'                => ['> 3 Hr', '> 2 Hr', '> 1 Hr', '> 0.5 Hr', '< 0.5 Hr'],
    'Cleaned not Assign trip Vehicles' => ['> 18 Hr', '> 12 Hr', '> 6 Hr', '> 3 Hr', '< 3 Hr'],
    'Trip Assigned Tankers'            => ['> 3 Hr', '> 2 Hr', '> 1 Hr', '> 0.3 Hr', '< 0.3 Hr'],
    'Total In Side'                    => ['> 48 Hr', '> 12 Hr', '> 6 Hr', '> 3 Hr', '< 3 Hr'],
    'Default'                          => ['> 24 Hr', '> 12 Hr', '> 6 Hr', '> 3 Hr', '< 3 Hr']
];

$hours = isset($config[$status]) ? $config[$status] : $config['Default'];
$thresholds = [
    'Very_Poor' => $hours[0],
    'Poor'      => $hours[1],
    'Average'   => $hours[2],
    'Good'      => $hours[3],
    'Very_Good' => $hours[4],
];
$is_percent = (isset($representation_type) && $representation_type == '2');
?>
<script>
    if ($('#inside_plant_title').length) {
        $('#inside_plant_title').html('<?= addslashes(isset($widget_title) ? $widget_title : Yii::t('app', 'Inside Plant Tankers')) ?>');
    }
</script>

<div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
        <tr class="bg-primary text-white">
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Plant') ?></th>
            <?php foreach ($thresholds as $label): ?>
                <th class="text-center"><?= Yii::t('app', $label) ?></th>
            <?php endforeach; ?>
            <th class="text-center"><?= Yii::t('app', 'Total') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($output)): ?>
            <?php foreach ($output as $data): ?>
                <tr>
                    <td class="font-weight-bold"><?= $data['Plant_Name'] ?? 'N/A' ?></td>

                    <?php foreach ($thresholds as $db_key => $label): ?>
                        <td class="text-center">
                            <?php
                            $val = $data[$db_key] ?? 0;
                            echo $is_percent ? $val . '%' : $val;
                            ?>
                        </td>
                    <?php endforeach; ?>

                    <td class="text-center bg-light">
                        <strong><?= $data['Total'] ?? 0 ?></strong>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center"><?= Yii::t('app', 'No Data Available.') ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
