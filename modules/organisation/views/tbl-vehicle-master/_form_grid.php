<?php
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<div class="grid-search">
    <?php
    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions')))>1)
        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php
$attribute = [
    //'vehicle_code',
            ['attribute' => 'union_code', 'value' => 'unionCode.union_name','visible'=>true,'filter'=>false],
            ['attribute' => 'transporter_code', 'value' => 'transporter.transporter_name','visible'=>true,'filter'=>false],
            ['attribute' => 'capacity_code', 'value' => 'capacity.value','visible'=>true,'filter'=>false],
            ['attribute' => 'registration_no','visible'=>false,'filter'=>false],
            ['attribute' => 'applicable_rto','visible'=>false,'filter'=>false],
            ['attribute' => 'driver_name','visible'=>true,'filter'=>false],
            ['attribute' => 'driver_contact_no','visible'=>false,'filter'=>false],
            ['attribute' => 'wef_date','visible'=>false,'filter'=>false],
            // '',
            // '',
            // '',
            // 'driving_license_number',
            // 'transporter_code',
            // 'mapped_route',
            // 'pollution_certificate',
            // 'insurance',
            // 'rc_book_no',
            // 'expiry_date',
            // 'rent',
            // 'average',
];

$grid_option = [
    'id' => 'vehicle-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'vehicle_code,vehicle_code,tbl-vehicle-master/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>