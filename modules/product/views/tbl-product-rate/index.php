<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Product Sale Rate'));
$this->params['menu'][] = Yii::$app->controls->add('Product Sale Rate');
$this->params['menu'][] = Yii::$app->controls->custombutton('Bulk Delete Applicability', 'delete-bulk-applicability', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
if (Yii::$app->session->get('eiplCode') == 'GYAN') {
    $this->params['menu'][] = Yii::$app->controls->import('product_sale_rate_gyan', $this);
} else {
    $this->params['menu'][] = Yii::$app->controls->import('product_sale_rate_bulk', $this);
}

$this->params['menu'][] = Yii::$app->controls->import('salerateapplicability-bulk', $this, Yii::t('app', 'Applicability Import'), [], 'product_sale_rate_applicability');
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>
