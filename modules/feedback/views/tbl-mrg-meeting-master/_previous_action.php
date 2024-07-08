<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'VCG_M_MOM_id', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->vCGMMonId, 'concern');
    }, 'label' => Yii::t('app', 'Concern'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'VCG_M_feedback_id', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->vCGMFeedbackId, 'feedback_desc');
    }, 'label' => Yii::t('app', 'Feedback'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'description', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'type', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'isclose', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'remarks', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'vcg-meeting-previous-action-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($previousDataProvider, $previousSearchModel, $grid_option);
    ?>
</div>
