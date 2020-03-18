<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\usermanagement\models\TblEiplAppWidgetMapping */

$this->title = Yii::t('app', 'Create Tbl Eipl App Widget Mapping');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Eipl App Widget Mappings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-eipl-app-widget-mapping-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
