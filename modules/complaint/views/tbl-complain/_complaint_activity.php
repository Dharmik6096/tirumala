<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$statusList = Yii::$app->dropdown->getRecords('activity_type')['data'];
?>
<a href="#" class="list-group-item">
    <h5 class="list-group-item-heading"><span class="badge"><?= in_array($model->activity_type, array_keys($statusList)) ? $statusList[$model->activity_type] : ''; ?></span> | <?= Yii::$app->controls->view_date($model->created_at) ?> | <?= isset($model->complainActivity->asset) ? $model->complainActivity->asset->asset_name : '' ?> | <?= Yii::$app->general->getmultiforeignkey($model->complainActivity, ['complainProblem'], 'problem_desc') ? Yii::$app->general->getmultiforeignkey($model->complainActivity, ['complainProblem'], 'problem_desc') : '' ?></h5>
    <p class="text-muted"><?= $model->getAttributeLabel('contact_person') ?>:  <?= $model->complainActivity->contact_person ?></p>
    <p class="text-muted"><?= $model->getAttributeLabel('assign_to') ?>:  <?= !empty($model->contactDetailsCodes) ? $model->contactDetailsCodes->name . '(' . $model->contactDetailsCodes->mobile_no . ')' : 'N/A' ?></p>
    <p class="list-group-item-text"><?= $model->getAttributeLabel('remarks') ?>: <?= $model->remarks ?></p>
</a>