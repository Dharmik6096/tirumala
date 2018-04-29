<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
?>
<a href="#" class="list-group-item">
    <h5 class="list-group-item-heading"><span class="badge"><?= $model->status ?></span> | <?= Yii::$app->controls->view_date($model->date) ?> | <?= $model->issue_type ?></h5>
    <p class="text-muted"><?= $model->contact_person ?></p>
    <p class="list-group-item-text"><?= $model->remarks ?></p>
</a>