<?php

use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Current Location of All Users'));
$latLongArray = [];
$infoArray = [];

if (!empty($onlineData)) {
    foreach ($onlineData as $data) {
        $datetime = $data['tracking_datetime'];
        $date = date('Y-m-d', strtotime($datetime));
        $time = date('H:i', strtotime($datetime));
        if (!empty($data['lat']) && !empty($data['long'])) {
            $info = '<div class="map_info_content">';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'User Code') . ':</span> ' . $data['user_code'] . '</p>';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'User Name') . ':</span> ' . $data['user_name'] . '</p>';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Date') . ':</span> ' . $date . '</p>';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Time') . ':</span> ' . $time . '</p>';
            $info .= '</div>';

            $latLongArray[] = [
                'info' => $info,
                'lat' => $data['lat'],
                'long' => $data['long'],
            ];
        }
    }
}

if (empty($latLongArray)) {
    $latLongArray[] = [
        'info' => 'Default Location',
        'lat' => 23.051489132364928,
        'long' => 72.49427197180005,
    ];
}

$latLongArray = json_encode($latLongArray);
$mapIcon = $this->theme->getUrl('/assets/images/marker-icon.png');
$completeMapIcon = $this->theme->getUrl('/assets/images/complete-marker-icon.png');
$googleMapKey = Yii::$app->params['google_map_api_key'];
?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= Html::encode($googleMapKey) ?>&libraries=&v=weekly" defer></script>
<div class="panel panel-default panel-grid panel-main tbl-complain-view hide-grid-settings">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider, 'allUser' => TRUE]); ?>
        </div>
        <div class="clearfix"></div>
        <?php echo $this->render('_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        <div class="row">
            <div class="panel-heading">
                <?= Yii::t('app', 'Current Location of Above Users'); ?>
            </div>
            <div class="col-sm-12">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<<JS
    var locations = $latLongArray;
    var bounds = new google.maps.LatLngBounds();
    var map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(locations[0].lat, locations[0].long),
        zoom: 15
    });
    var infoWindow = new google.maps.InfoWindow();
    
    locations.forEach(function(location) {
        var markerPosition = { lat: parseFloat(location.lat), lng: parseFloat(location.long) };
        var marker = new google.maps.Marker({
            map: map,
            position: markerPosition,
            icon: "$mapIcon",
        });

        bounds.extend(markerPosition);

        marker.addListener('click', function() {
            infoWindow.setContent(location.info);
            infoWindow.open(map, marker);
        });
    });

    if (locations.length > 1) {
        map.fitBounds(bounds);
    }
JS;

$this->registerJs($script, View::POS_READY, 'user-tracking-movement');
?>