<?php

use yii\web\View;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

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
            $array[] = $data['member_code'];
            $latLongArray[] = $array;
        }
    }
}


if (empty($latLongArray)) {
    $latLongArray[] = ['India', 20.5937, 78.9629, ''];
}
$infoArray = json_encode($infoArray);
$latLongArray = json_encode($latLongArray);
$asset_path = Yii::$app->general->base64url_decode(Url::to(['/themes/pcdf/assets/']));
$mapIcon = $this->theme->getUrl('/assets/images/map_marker.png');
$googleMapKey = Yii::$app->params['google_map_api_key'];
//AIzaSyAMTTxu_z6hetI1B83CmW30vGv6dibQWLU
?>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=<?= $googleMapKey ?>&callback=initMap&libraries=&v=weekly"
    defer
></script>
<!--<link rel="stylesheet" href="<?= '/themes/pcdf/assets/css/' ?>leaflet.css" />
<link rel="stylesheet" href="<?= '/themes/pcdf/assets/css/' ?>Control.FullScreen.css" />
<script src="<?= '/themes/pcdf/assets/js/' ?>leaflet.js"></script>
<script src="<?= '/themes/pcdf/assets/js/' ?>Control.FullScreen.js"></script>-->
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= Yii::$app->controls->cancel('', 'index', null, true); ?>
            <?= $this->title; ?>    
            <?= GhostHtml::a('<i class="fa fa-search"></i>', '#', ['class' => 'mis_report_modal_toggle mr-85 headerIcon btn btn-danger apply-shortcut btn-block', 'shortcut_key' => 'ctrl+alt+c']); ?>           
            <!--            <div class="headerIcon searchBtnReport text-right ">
                            <div class="btn-group btn btn-default mis_report_modal_toggle"><i class="fa fa-search"></i></div>
                        </div>-->
        </div>
        <div class="clearfix"></div>
        <div class="panel-body">

            <div class="modal modal-default fade" id="mis_report_search_filter" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?php echo $this->title; ?></h4>
                        </div>
                        <div class="">
                            <?php
                            $form = ActiveForm::begin(['options' => [
                                            'id' => 'report-form',
                                            'field-class' => 'form-group col-sm-6'
                                        ],
                                        'method' => 'get',
                                        'validateOnBlur' => FALSE,
                                        'validateOnEnter' => TRUE,
                                        'validateOnChange' => FALSE,
                                        'enableClientValidation' => true,
                                        'validateOnSubmit' => true,
                            ]);
                            ?>    
                            <div class="row margin_0">

                                <div class="modal-body">

                                    <div class="col-sm-6">
                                        <?php
                                        echo Yii::$app->controls->date($model, $form, 'search_date', 'form-group col-sm-6 padding-left-5 padding-right-5', false);
                                        ?>
                                    </div>  
                                    <!--<div class="clearfix"></div>-->
                                    <div class="col-sm-3">
                                        <?=
                                        $form->field($model, 'from_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                                            'mask' => '99:99',])
                                        ?> 
                                    </div>
                                    <div class="col-sm-3">
                                        <?=
                                        $form->field($model, 'to_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                                            'mask' => '99:99',])
                                        ?> 
                                    </div>
                                    <div class="modal-footer mt10 col-sm-12">
                                        <?php
                                        if (true) {
                                            echo GhostHtml::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'html', 'value' => 'html', 'id' => 'html']);
                                        }
                                        ?>
                                        <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div id="map"></div>
                </div> 
            </div>
        </div>
    </div>
</div>



<?php
//$script = "
//var asset_path = '/themes/pcdf/assets/';
//var locations = '" . $latLongArray . "';
//var locations=$.parseJSON(locations);
//var gmap = L.map('map').setView([locations[0][1], locations[0][2]], 12);
//
//var LeafIcon = L.Icon.extend({
//    options: {
//       iconSize:     [25, 41],
//       shadowSize:   [40, 40],
//       iconAnchor:   [0, 0],
//       shadowAnchor: [0, 0],
//       popupAnchor:  [12, 0]
//    }
//});
//var greenIcon = new LeafIcon({
//    iconUrl: asset_path + '/images/marker-icon.png',
//    shadowUrl: asset_path + '/images/marker-shadow.png'
//})
//L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
//    minZoom: 8,
//    maxZoom: 40,
//    attribution: '',
//    id: 'mapbox.streets'
//}).addTo(gmap);
//$.each(locations, function(key, val) {
//    L.marker([val[1],val[2]]).addTo(gmap)
//            .bindPopup(val[0]);
//});
////    google.maps.event.addDomListener(window, 'load', initMap);
//";

$script = "
    $('.mis_report_modal_toggle').on('click', function(){
        $('#mis_report_search_filter').modal('toggle');
    });
    var locations = '" . $latLongArray . "';
    var locations=$.parseJSON(locations);
//    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(locations[0][1], locations[0][2]),
          zoom: 15
        });
        var infoWindow = new google.maps.InfoWindow;
        
        $.each(locations, function(key, val) {
            var point = new google.maps.LatLng(
                  parseFloat(val[1]),
                  parseFloat(val[2]));
            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            var name = 'test';
            strong.textContent = name
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));
            
            var address = 'addresss';
            var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);

            var marker = new google.maps.Marker({
                map: map,
                position: point,
                icon: '" . $mapIcon . "',
                label:''
              });
//              val[3]
//              console.log(infowincontent);
//              console.log(val[0]);
              marker.addListener('click', function() {
                infoWindow.setContent(val[0]);
                infoWindow.open(map, marker);
              });

        });
        
//    }

//    var map = new google.maps.Map(document.getElementById('map'), {
//        zoom: 13,
//        center: {lat: 26.4500688, lng: 80.3331902},
//        mapTypeId: 'terrain',
//         minZoom: 13
//    });
//
//    var flightPlanCoordinates = [
//        {lat: 26.4500688,lng: 80.3331902},
//        {lat: 26.4502754,lng: 80.3362687},
//        {lat: 26.4489887,lng: 80.3417592},
//        {lat: 26.4565144,lng: 80.3471883},
//    ];
//    var flightPath = new google.maps.Polyline({
//        path: flightPlanCoordinates,
//        geodesic: true,
//        strokeColor: '#FF0000',
//        strokeOpacity: 1.0,
//        strokeWeight: 2
//    });
//
//    flightPath.setMap(map);
";

if ($defaultToggle) {
    $script .= "
        $(document).ready(function () {
            $('#mis_report_search_filter').modal('toggle');
        });
    ";
}
$this->registerJs($script, View::POS_READY, 'live_tracking_map');
?>
