<?php

use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\web\View;

$visualTabs = [
    'Member Details' => ['approval-member-detail'],
    'Address & Adhar Details' => ['approval-address-detail', 'approval-adhar-detail'],
    'Family Details' => ['approval-family-detail'],
    'Animal & Commitment Details' => ['approval-animal-detail', 'approval-commitment-detail'],
    'Share Details' => ['approval-share-detail'],
    'Bank Details' => ['approval-bank-detail', 'approval-fee-detail', 'approval-mismatch-detail'],
];


$items = [];
foreach ($visualTabs as $label => $actions) {
    $hasAccess = false;
    $targetAction = null;
    foreach ($actions as $action) {
        if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/' . $action)) {
            $hasAccess = true;
            if (!$targetAction) {
                $targetAction = $action;
            }
        }
    }

    if ($hasAccess) {
        $items[] = [
            'label' => Yii::t('app', $label),
            'url' => Url::to(['/dcsoperation/tbl-member-provisional/' . $targetAction, 'id' => $processModel->process_approval_code]),
            'active' => in_array($currentStep, $actions),
        ];
    }
}
?>
<div class="row">
    <div class="col-md-12 tab-header-line">
        <?php
        echo Tabs::widget([
            'items' => $items,
            'options' => ['class' => 'mb15 nav-pills'],
        ]);
        ?>
    </div>
</div>
<?php
$script = "
$(document).on('click', '.re-route', function() {
    var remarks = $('#reroute-form [name*=\"remarks\"]').val();
    $('#reroute_remarks').val(remarks);
    $('#reroute_remarks').closest('form').find('.set_operation').val('reroute');
    $('#reroute_remarks').closest('form').submit();
});
";
$this->registerJs($script, View::POS_END, 'approval-tabs-reroute');
?>
