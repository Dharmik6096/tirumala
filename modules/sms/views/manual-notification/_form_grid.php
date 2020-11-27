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
                'checkboxOptions' => function($model) {
            return ['class' => 'checkbox', 'value' => $model['checkbox_id']];
        }];
        } else {
            $attr_arr = [];
            if (in_array($att, ['mobile_no', 'email'])) {
                $attr_arr['value'] = function($model) use ($att) {
                    return !empty($model[$att]) ? (Yii::$app->general->decryptData($model[$att]) !== FALSE ? Yii::$app->general->decryptData($model[$att]) : $model[$att]) : '';
                };
            }
            $str = ucwords(str_replace('_', ' ', $att));
            $attr_arr['label'] = Yii::t('app', $str);
            $attr_arr['attribute'] = $att;
            $attr_arr['filter'] = false;
            $attr_arr['format'] = 'raw';
            $attr[] = $attr_arr;
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
<br/>
<div class="col-md-12" >    
    <?php
    if (!empty($result) && is_array($result)) {
        echo Html::button(Yii::t('app', 'SEND ALERT'), ['class' => 'btn btn-primary save-alert']);
    }
    ?>
</div>
<?php ActiveForm::end(); ?>
