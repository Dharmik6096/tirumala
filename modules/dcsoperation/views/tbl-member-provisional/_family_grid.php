<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'family_member_name', 'value' => 'family_member_name', 'filter' => false],
        ['attribute' => 'local_family_member_name', 'value' => 'local_family_member_name', 'filter' => false, 'visible' => false],
        ['attribute' => 'relationship_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->relationship, 'relationship');
        }, 'filter' => false],
        ['attribute' => 'dob', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->dob);
        }, 'filter' => false],
        ['attribute' => 'gender_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->genderCode, 'gender');
        }, 'filter' => false],
        ['attribute' => 'is_nominee', 'value' => function($model) {
            return ($model->is_nominee == 1) ? 'Yes' : 'No';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'member-family-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($fDataProvider, $familyMemberModel, $grid_option);
?>
