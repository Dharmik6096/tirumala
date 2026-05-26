<?php
use yii\helpers\Url;

// Determine if the status is Empty or Filled to set appropriate thresholds
$status = isset($selected_key_label) ? $selected_key_label : 'Empty Tanker';

$config = [
        'Empty'      => ['> 24 Hr', '> 12 Hr', '> 6 Hr', '> 3 Hr', '< 3 Hr'],
        'CC Waiting' => ['> 4 Hr', '> 3 Hr', '> 2 Hr', '> 1 Hr', '< 1 Hr'],
        'Load'       => ['> 24 Hr', '> 12 Hr', '> 6 Hr', '> 3 Hr', '< 3 Hr'],
        'Round Trip' => ['> 48 Hr', '> 24 Hr', '> 12 Hr', '> 6 Hr', '< 6 Hr'],
        'Default'    => ['> 24 Hr', '> 12 Hr', '> 6 Hr', '> 3 Hr', '< 3 Hr']
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

<?php
$full_title = addslashes(isset($widget_title) ? $widget_title : Yii::t('app', 'Intransit Hours Analysis'));
?>

<div class="intransit-data-wrapper" data-new-title="<?= $full_title ?>">
    <div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
        <tr class="bg-info text-white"> <!-- Changed color to bg-info to visually distinguish from Plant tab -->
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
                    <td class="font-weight-bold">
                        <?= $data['Plant_Name'] ?? ($data['plant_name'] ?? 'N/A') ?>
                    </td>

                    <?php foreach ($thresholds as $db_key => $label): ?>
                        <td class="text-center">
                            <?php
                            // Supports both CamelCase and lowercase keys depending on SP output
                            $val = isset($data[$db_key]) ? $data[$db_key] : 0;
                            echo $is_percent ? number_format($val, 1) . '%' : $val;
                            ?>
                        </td>
                    <?php endforeach; ?>

                    <td class="text-center bg-light">
                        <strong><?= isset($data['Total']) ? $data['Total'] : (isset($data['total']) ? $data['total'] : 0) ?></strong>
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
</div>
