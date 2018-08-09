<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblDcsPurchaseRate */

$this->title = Yii::t('app', 'Purchase Rates') . ' (' . Yii::t('app', 'BMC') . ')' . Yii::t('app', ' Detail View');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rates'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">       
        <?= Yii::$app->controls->cancel($model); ?> 
        <?= Html::encode($this->title) ?>
    </div>
    <?php
//    var_dump($model->unionCode);die;
    ?>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?=
                DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-bordered detail-view'],
                    'attributes' => [
                        [
                            'attribute' => 'wef_date',
                            'format' => 'html',
                            'value' => Yii::$app->controls->view_date($model->wef_date)
                        ],
                        'shiftId.shift',
                        [
                            'attribute' => 'shift_applicability',
                            'format' => 'html',
                            'value' => Yii::$app->general->getforeignkey($model->shiftApplicability, 'shift'),
                        ],
                        [
                            'attribute' => 'rate_gen_method_code',
                            'value' => Yii::$app->general->getforeignkey($model->rateMethod, 'method'),
                        ],
                        [
                            'attribute' => 'union_code',
                            'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                        ],
                        'description',
                        [
                            'attribute' => 'is_active',
                            'label' => Yii::t('app', 'Status'),
                            'format' => 'html',
                            'value' => GeneralFunctions::getRecordStatus($model->is_active)
                        ],
                    ],
                ])
                ?>
            </div>
        </div>

        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Purchase Rate') . ' (' . Yii::t('app', 'BMC') . ') ' . Yii::t('app', 'Transactions') ?></h5></div>
        <div class="form-grid">
            <?php echo $this->render('_manual_rate_grid', ['dataProvider' => $purchaseBasedModel->search(Yii::$app->request->queryParams), 'searchModel' => $purchaseBasedModel]); ?>
        </div>
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Purchase Rate') . ' (' . Yii::t('app', 'BMC') . ') ' . Yii::t('app', 'Applicability') ?></h5></div>
        <div class="form-grid">
            <?php echo $this->render('_grid_applicability', ['dataProvider' => $appdataProvider, 'searchModel' => $appsearchModel]); ?>
        </div>
    </div>
</div>