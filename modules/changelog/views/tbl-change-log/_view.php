<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
$desc = explode('--', $model->description);
if(empty($desc[1]))
{
    $desc[1]=$model->description;
}
$editLink=  GhostHtml::a('<i class="fa fa-pencil-alt"></i>', Url::to(['update','id'=>$model->log_code]),['class'=>'ml15']);
?>
<div class="list-group-item unordered-li mt10">
    <h5 class="list-group-item-heading text-danger"><?= date('d-m-Y H:i:s', strtotime($model->created_at)).' '.$editLink; ?></h5>
    <ul class="text-muted">
        <?php foreach(array_slice($desc,1) as $itm) { ?>
            <li><?= $itm; ?></li>
        <?php } ?>
    </ul>
</div>