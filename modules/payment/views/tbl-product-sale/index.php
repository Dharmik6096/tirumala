
<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Product Sale'));
$this->params['menu'][] = Yii::$app->controls->add('Product Sale', 'create-product-sale');
$this->params['menu'][] = Yii::$app->controls->add('Product Sale to Member', 'create-product-sale-to-member');
$this->params['menu'][] = Yii::$app->controls->add('Product Sale On Cash', 'create-product-sale-cash');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete Product Sale', 'delete-product-sale', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete Product Sale To Member', 'delete-product-sale-to-member', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
$productSaleDeleteApproval = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'product_sale_delete_approval', 'PORTAL');
if ($productSaleDeleteApproval == 1) {
    $this->params['menu'][] = Yii::$app->controls->custombutton('Delete Product Sale(Approval)', 'delete-product-sale-approval', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
    $this->params['menu'][] = Yii::$app->controls->custombutton('Delete Product Sale To Member(Approval)', 'delete-product-sale-to-member-approval', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
}
$this->params['menu'][] = Yii::$app->controls->import('productsale-bulk', $this, Yii::t('app', 'Product Sale Import'));
$this->params['menu'][] = Yii::$app->controls->import('productsalemember-bulk', $this, Yii::t('app', 'Product Sale Member Import'), [], 'productsale_member');
?>
<div class="tbl-product-sale-index">
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