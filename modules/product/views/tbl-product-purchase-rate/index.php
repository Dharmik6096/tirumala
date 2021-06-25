<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Product Purchase Rate'));
$this->params['menu'][] = Yii::$app->controls->add('Product Purchase Rate');
$this->params['menu'][] = Yii::$app->controls->custombutton('Bulk Delete Applicability', 'delete-bulk-applicability', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
$this->params['menu'][] = Yii::$app->controls->import('product_purchase_rate_bulk', $this);
$this->params['menu'][] = Yii::$app->controls->import('purchaserateapplicability-bulk', $this,Yii::t('app', 'Applicability Import'),[], 'product_purchase_rate_applicability');

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
