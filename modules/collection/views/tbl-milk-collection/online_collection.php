<?php

use yii\web\View;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Online Collections'));
$infoArray = [];
$latLongArray = [];
if (!empty($onlineData)) {
    foreach ($onlineData as $data) {
        $infoWindow = [];
        $info = '<div class=\"map_info_content\">';
        $info .= '<p class=\"map_marker_content\"><span class=\"marker_header\">' . Yii::t('app', 'Member Code') . ':</span> ' . $data['member_code'] . '</p>';
        $info .= '<p class=\"map_marker_content\"><span class=\"marker_header\">' . Yii::t('app', 'Member Name') . ':</span> ' . $data['member_name'] . '</p>';
        $info .= '<p class=\"map_marker_content\"><span class=\"marker_header\">' . Yii::t('app', 'Date') . ':</span> ' . $data['date'] . '</p>';
//        $info .= '<p class=\"map_marker_content\"><span class=\"marker_header\">' . Yii::t('app', 'Shift') . ':</span> ' . $data['shift'] . '</p>';
        $info .= '<p class=\"map_marker_content\"><span class=\"marker_header\">' . Yii::t('app', 'Quantity') . ':</span> ' . $data['Qty'] . '</p>';
        $info .= '</div>';
        $infoWindow[] = $info;
        $infoArray[] = $infoWindow;
        if (!empty($data['lat']) && !empty($data['long'])) {
            $array = [];
            $array[] = $info;
            $array[] = $data['lat'];
            $array[] = $data['long'];
            $latLongArray[] = $array;
        }
    }
}


if (empty($latLongArray)) {
    $latLongArray[] = ['India', 20.5937, 78.9629];
}
$infoArray = json_encode($infoArray);
$latLongArray = json_encode($latLongArray);
$asset_path = Url::to(['/themes/pcdf/assets/']);
?>

<link rel="stylesheet" href="<?= $asset_path . '/css/' ?>leaflet.css" />
<link rel="stylesheet" href="<?= $asset_path . '/css/' ?>Control.FullScreen.css" />
<script src="<?= $asset_path . '/js/' ?>leaflet.js"></script>
<script src="<?= $asset_path . '/js/' ?>Control.FullScreen.js"></script>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= Yii::$app->controls->cancel('', 'index', null, true); ?>
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <div id="map"></div>
                </div> 
            </div>
        </div>
    </div>
</div>



<?php
$script = "
var asset_path = '" . $asset_path . "';
var locations = '" . $latLongArray . "';
var locations=$.parseJSON(locations);
var gmap = L.map('map').setView([locations[0][1], locations[0][2]], 6);

var LeafIcon = L.Icon.extend({
    options: {
       iconSize:     [25, 41],
       shadowSize:   [40, 40],
       iconAnchor:   [0, 0],
       shadowAnchor: [0, 0],
       popupAnchor:  [12, 0]
    }
});
var greenIcon = new LeafIcon({
    iconUrl: asset_path + '/images/marker-icon.png',
    shadowUrl: asset_path + '/images/marker-shadow.png'
})
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: '',
    id: 'mapbox.streets'
}).addTo(gmap);
$.each(locations, function(key, val) {
    L.marker([val[1],val[2]]).addTo(gmap)
            .bindPopup(val[0]);
});
//    google.maps.event.addDomListener(window, 'load', initMap);
";
$this->registerJs($script, View::POS_READY, 'live_tracking_map');
?>
