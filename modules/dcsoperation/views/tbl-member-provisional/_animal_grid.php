<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'animal_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->animalTypeCode, 'animal_type_name');
        }, 'filter' => false],
        ['attribute' => 'heifers_count', 'filter' => false],
        ['attribute' => 'milch_animal_count', 'filter' => false],
        ['attribute' => 'dry_animal_count', 'filter' => false],
        ['attribute' => 'total_animal', 'filter' => false],
        ['attribute' => 'daily_milk_production', 'filter' => false],
];

$grid_option = [
    'id' => 'member-animal-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($animalDataProvider, $animalMemberModel, $grid_option);
?>
<?php

$script = '$(".kv-panel-before").hide();';
$this->registerJs($script, View::POS_END, 'member-animal-list');
