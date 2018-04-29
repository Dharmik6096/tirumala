<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblLocalMilkSaleRate */

$this->title = Yii::t('app',Yii::$app->label->title('view', 'local milk sale rate'));
?>
<div class="tbl-local-milk-sale-rate-view">

    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="panel-subheading padding-0">
                <div class="table-responsive">

                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view'],
                    'attributes' => [
                        'local_sale_rate_code',
                        'rate',
//                        [
//                            'attribute' => 'sale_grade',
//                            'format' => 'html',
//                            'value' => $model->sale_grade==1?'Low':'High'
//                        ],
                        [
                            'attribute' => 'wef_date',
                            'format' => 'html',
                            'value' => Yii::$app->controls->view_date($model->wef_date)
                        ],
                        'milkType.animal_type_name',
                        'dcsCode.dcs_name',
                        'subCenterCode.sub_center_name',
                        [
                            'attribute' => 'is_active',
                            'label' => 'Status',
                            'format' => 'html',
                            'value' => GeneralFunctions::getRecordStatus($model->is_active)
                        ],
                    ],
                ]) ?>
                    
                </div>
                
        </div>
            
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>                
</div>
