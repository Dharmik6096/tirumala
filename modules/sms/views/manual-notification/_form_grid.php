<?php

use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
$form = ActiveForm::begin(['options' => [
                'id' => 'sms-list-save',
                'validateOnBlur' => TRUE,
                'validateOnEnter' => TRUE,
                'validateOnChange' => TRUE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
            ],
        ]);
?>   

<?php
if (!empty($result) && !is_array($result)) {
    echo "<b><p class='text-center mt-50'>" . $result . "</p></b>";
} else if (!empty($result)) {
    $attr = [];
    foreach ($result[0] as $att => $value) {
        if ($att == 'checkbox_id') {
            $attr[] = ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model) use ($value) {

            return ['class' => 'checkbox', 'value' => $value];
        }];
        } else {
            $format = 'raw';
            $str = ucwords(str_replace('_', ' ', $att));
            $attr[] = ['attribute' => $att, 'label' => Yii::t('app', $str), 'format' => $format, 'filter' => false];
        }
    }
    $grid_option = [
        'id' => 'manual-sms-form-list',
        'attributes' => $attr,
        'active_column' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $model, $grid_option);
}
?> 
<div class="clearfix"></div>
<div class="col-md-12" >    
    <?php
    if (!empty($result) && is_array($result)) {
        echo Html::button(Yii::t('app', 'SEND ALERT'), ['class' => 'btn btn-primary save-alert']);
    }
    ?>
</div>
<?php ActiveForm::end(); ?>
