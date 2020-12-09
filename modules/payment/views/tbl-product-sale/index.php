<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Product Sale'));
$this->params['menu'][] = Yii::$app->controls->add('Product Sale', 'create-product-sale');
$this->params['menu'][] = Yii::$app->controls->add('Product Sale to Member', 'create-product-sale-to-member');
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
//                'model' => $model,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>