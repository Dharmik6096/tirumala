<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'animal_type_code', 'value' => 'animalTypeCode.animal_type_name', 'filter' => false],
        ['attribute' => 'heifers_count', 'filter' => false],
        ['attribute' => 'milch_animal_count', 'filter' => false],
        ['attribute' => 'total_animal', 'filter' => false],
        ['attribute' => 'daily_milk_production', 'filter' => false],
        ['attribute' => 'heifers_count', 'filter' => false]
];

$grid_option = [
    'id' => 'member-animal-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($animalDataProvider, $animalMemberModel, $grid_option);
?>
