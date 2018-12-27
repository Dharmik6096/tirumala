<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsconfiguration\models\TblMilkCollectionConfigDefault */
/* @var $form yii\widgets\ActiveForm */
?>
<?php

$items = [
    [
        'label' => Yii::t('app', 'Milk Collection'),
        'content' => "",
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['/configuration/tbl-milk-collection-config/milk-collection'])],
        'active' => true
    ],
    [
        'label' => Yii::t('app', 'Share Management'),
        'content' => "",
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['/configuration/tbl-dcs-general-config/share-management'])]
    ],
    [
        'label' => Yii::t('app', 'Product Sale/Purchase'),
        'content' => "",
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['/configuration/tbl-dcs-general-config/product-sale-purchase'])]
    ],
    [
        'label' => Yii::t('app', 'Election Config'),
        'content' => "",
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['/configuration/tbl-dcs-general-config/election-config'])]
    ],
    [
        'label' => Yii::t('app', 'Backup'),
        'content' => "",
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['/configuration/tbl-dcs-general-config/backup'])]
    ],
    [
        'label' => Yii::t('app', 'Dispatch and Receipt Parm.'),
        'content' => "",
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['/configuration/tbl-dcs-general-config/dispatch-and-receipt'])]
    ],
   
];

echo "<div class='panel panel-main'>";
echo "<div class='panel-body'>";
echo TabsX::widget([
    'items' => $items,
    'position' => TabsX::POS_ABOVE,
    'encodeLabels' => false
]);
echo "</div>";
echo "</div>";

if (isset($_GET['tab'])) {
    $tab_id = $_GET['tab'];
    $script = '
    $("li").removeClass("active");
    $("a").each(function(){
    console.log("'.$tab_id.'");
        if($(this).attr("href") == "#' . $tab_id . '"){
            $(this).click();
        }
    });';
} else {
    $script = '
    $("document").ready(function() {
    setTimeout(function() {
    $(".tabs-krajee").find("li.active a").click();
    },10);
    });';
}

$this->registerJs($script, \yii\web\View::POS_READY);
?>