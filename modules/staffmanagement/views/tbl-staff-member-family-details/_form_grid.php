<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

?>
<?php

$attribute = [
    ['attribute' => 'family_member_name', 'value' => 'family_member_name', 'filter' => false],
    ['attribute' => 'local_family_member_name', 'value' => 'local_family_member_name', 'filter' => false, 'visible' => false],
    ['attribute' => 'relation_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->relationship, 'relationship');
        }, 'filter' => false],
    ['attribute' => 'birth_date', 'value' => 'birth_date', 'filter' => false],
    ['attribute' => 'gender_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->genderCode, 'gender');
        }, 'filter' => false],
    ['attribute' => 'is_nominee', 'value' => function($model) {
            return $model->is_nominee == 1 ? 'Yes' : 'No';
        }, 'filter' => false],
];
if ($isaction) {
    $grid_option = [
        'id' => 'member-family-details-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'actions' => [
            'edit' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->staff_family_details_code, 'data-name' => $model->staff_family_details_code, 'title' => Yii::t('app', 'Edit')];
                return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/staffmanagement/tbl-staff-member-family-details/update-family'], $options);
            },
        ]
    ];
} else {
    $grid_option = [
        'id' => 'member-family-details-grid',
        'attributes' => $attribute,
        'active_column' => false,
    ];
}

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
