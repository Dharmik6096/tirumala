<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'family_member_name', 'filter' => false],
        ['attribute' => 'local_family_member_name', 'filter' => false],
        ['attribute' => 'relationship_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->relationship, 'relationship');
        }, 'filter' => false],
        ['attribute' => 'dob', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->dob);
        }, 'filter' => false],
        ['attribute' => 'age', 'filter' => false],
        ['attribute' => 'gender_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->genderCode, 'gender');
        }, 'filter' => false],
        ['attribute' => 'is_nominee', 'value' => function($model) {
            return ($model->is_nominee == 1) ? 'Yes' : 'No';
        }, 'filter' => false],
        ['attribute' => 'nominee_address', 'filter' => false],
        ['attribute' => 'local_nominee_address', 'filter' => false],
        ['attribute' => 'guardian_name', 'filter' => false],
        ['attribute' => 'local_guardian_name', 'filter' => false],
        ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'member-family-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($fDataProvider, $familyMemberModel, $grid_option);
?>
<?php

$script = '$(".kv-panel-before").hide();';
$this->registerJs($script, View::POS_END, 'member-family-list');
