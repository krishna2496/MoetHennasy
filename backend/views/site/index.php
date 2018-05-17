<?php
use yii\helpers\Html;
use common\helpers\CommonHelper;
$this->title = 'Dashboard';
?>
<div class="site-index">
    <h3 class="box-title">
        <?= Html::encode($this->title) ?>
    </h3>
</div>
    <div class="row">

        <div class="col-md-2">
            <select id="storeId" class="form-control" onchange="filterMarkers();">
                <option value="">Select Store</option>
            <?php if(!empty($storeList)) {
                foreach ($storeList as $key=>$value){
                ?>
                <option value=<?= $key ?>><?= $value ?></option>
            <?php }} ?>
            </select>
        </div>

        <div class="col-md-2">

            <select id="marketId" class="form-control" onchange="filterMarkers();">
                <option value="">Select Market</option>
                <?php if(!empty($storeList)) {
                foreach ($marketList as $key=>$value){
                ?>
                <option value=<?= $key ?>><?= $value ?></option>
            <?php }} ?>
            </select>               
        </div>
        
         <div class="col-md-2">

            <select id="roleId" class="form-control" onchange="filterMarkers();">
                <option value="">Select Role</option>
                <?php if(!empty($roleList)) {
                foreach ($roleList as $key=>$value){
                ?>
                <option value=<?= $key ?>><?= $value ?></option>
            <?php }} ?>
            </select>               
        </div>

    </div>

<div class="row content-header">
  <div id="map-canvas"  style="width: 600px; height: 400px;"></div>
</div>
    
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= API_KEY ?>&callback=initialize"> </script>
<script>
    
var gmarkers1 = [];
var markers1 = [];
markers1 =<?php echo $store; ?>;

function initialize() {
    var center = new google.maps.LatLng(25.4357808, -2.991315699999973);
    var mapOptions = {
        zoom: 1,
        center: center,
        mapTypeId: google.maps.MapTypeId.TERRAIN   
    };

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    for (i = 0; i < markers1.length; i++) {
        addMarker(markers1[i]);
    }
    var infowindow = new google.maps.InfoWindow({
    content: ''
});
   
function addMarker(marker) {
    var storeId = marker[4];
    var marketId = marker[5];
    var roleId =marker[6];
    var title = marker[1];
    var pos = new google.maps.LatLng(marker[2], marker[3]);
    var content = marker[1]+',<br>'+marker[7];
var image = {
    url: 'http://localhost/moet_hennessy_app/uploads/map/winebar3.png',
  
    size: new google.maps.Size(1500, 1500),
  
    origin: new google.maps.Point(0, 0),
  
    anchor: new google.maps.Point(0, 32)
  };

    marker1 = new google.maps.Marker({
        title: title,
        position: pos,
        icon: image,
        storeId: storeId,
        marketId: marketId,
        roleId: roleId,
        map: map
    });
    gmarkers1.push(marker1);
    google.maps.event.addListener(marker1, 'click', (function (marker1, content) {
        return function () {
            infowindow.setContent(content);
            infowindow.open(map, marker1);
            map.panTo(this.getPosition());
          
        }
    })(marker1, content));
}
filterMarkers = function () {
    var storeId = document.getElementById('storeId').value;
    var marketId = document.getElementById('marketId').value;
    var roleId = document.getElementById('roleId').value;
   
    for (i = 0; i < markers1.length; i++) {
        marker = gmarkers1[i];
     
        if ( (marker.storeId == storeId || storeId.length === 0) && (marker.marketId == marketId || marketId.length === 0) && ((marker.roleId == roleId || roleId.length === 0))) {
            marker.setVisible(true);
         
        }
        else {
            marker.setVisible(false);
        }
    }
}
}
</script>

