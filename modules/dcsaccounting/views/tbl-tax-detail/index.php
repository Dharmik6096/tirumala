<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Tax Detail'));
$this->params['menu'][] = Yii::$app->controls->add('Tax Detail', ['/dcsaccounting/tbl-tax-detail/create', 'id' => $record->tax_code], true);
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-arrow-left"></i> ' . Yii::t('app', 'Back To Tax List'), '/dcsaccounting/tbl-tax/index', true);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'taxModel' => $taxModel, 'data' => $data, 'searchModel' => $record
        ])
        ?>
    </div>
</div>
<?= $this->render('test_tax', ['taxes' => $taxes, 'selected' => $record->tax_code]) ?>
<?php
$script = "
    
            $(document).on('click','.test-tax',function(e){
                    $('#testTax').modal('toggle');
            });
";
$this->registerJs($script, View::POS_END, 'tax-detail-test-tax');
?>