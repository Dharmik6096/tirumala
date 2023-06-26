<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
?>

<?php
if (!empty($output)) {
    foreach ($output as $data) {
        ?>
        <tr>
            <td><?= $data['union_name'] ?></td>
            <td><?= $data['bmc_name'] ?></td>
            <td><?= $data['dcs_name'] ?></td>
            <td><?= $data['prev_count'] ?></td>
            <td><?= $data['current_count'] ?></td>
        </tr>
        <?php
    }
} else {
    ?>
    <tr><td colspan="7">No Data Available.</td></tr>
<?php } ?>