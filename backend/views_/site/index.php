
<?php
use yii\helpers\Html;
use common\helpers\CommonHelper;
use yii\helpers\Url;
$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box">
    <div  class="box-header">
<div class="site-index box-header">
    <h1>
        Welcome to the MH Dashboard
    </h1>
</div>
    <div class="row">
        <div class="col-md-12">
        <div class="col-md-3">
            <select id="storeId" class="form-control select2" onchange="filterMarkers();">
                <option value="">Select Store</option>
            <?php if(!empty($storeList)) {
                foreach ($storeList as $key=>$value){
                ?>
                <option value=<?= $key ?>><?= $value ?></option>
            <?php }} ?>
            </select>
        </div>

        <div class="col-md-3">

            <select id="marketId" class="form-control select2" onchange="filterMarkers();">
                <option value="">Select Market</option>
                <?php if(!empty($storeList)) {
                foreach ($marketList as $key=>$value){
                ?>
                <option value=<?= $key ?>><?= $value ?></option>
            <?php }} ?>
            </select>               
        </div>
        
         <div class="col-md-3">

            <select id="roleId" class="form-control select2" onchange="filterMarkers();">
                <option value="">Select Role</option>
                <?php if(!empty($roleList)) {
                foreach ($roleList as $key=>$value){
                ?>
                <option value=<?= $key ?>><?= $value ?></option>
            <?php }} ?>
            </select>               
        </div>
        </div>
    </div>
    </div>
    <div class="box-body">
<div class="row">
    <div class="col-md-12 col-xs-12 col-lg-12">
  <div id="map-canvas"  style="width: 100%; height: 700px"></div>
    </div>
</div>
    </div>
</div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= API_KEY ?>&callback=initialize"> </script>
<script>
var storeUrl = '<?= Url::to(['stores/view/']); ?>';
var isUpdate= 1;
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
var bounds = new google.maps.LatLngBounds();
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    for (i = 0; i < markers1.length; i++) {
        addMarker(markers1[i]);
    }
    var infowindow = new google.maps.InfoWindow({
    content: '',

    
});
map.fitBounds(bounds);
   
function addMarker(marker) {
   
    var storeId = marker[4];
    var marketId = marker[5];
    var roleId =marker[6];
    var title = marker[1];
    var url = storeUrl+'/'+marker[4];
    var pos = new google.maps.LatLng(marker[2], marker[3]);
    var content = '<div style="min-width:220px"><div style="float:left;clear:both;width:90px;height:90px">'+marker[10]+'</div><div>'+marker[1]+'<br><b>Address: </b>'+marker[7]+'<br><b>Phone: </b>('+marker[8]+')'+marker[9]+'</div></div>';
    var image = {
    url: 'http://localhost/moet_hennessy_app/uploads/map/winebar3.png',
  
    size: new google.maps.Size(1500, 1500),
  
    origin: new google.maps.Point(0, 0),
  
    anchor: new google.maps.Point(0, 32)
    };

    marker1 = new google.maps.Marker({
        title: title,
        position: pos,
        url:url,
        storeId: storeId,
        marketId: marketId,
        roleId: roleId,
        map: map
    });
   
    gmarkers1.push(marker1);
    
//    console.log(marker1);
    
   google.maps.event.addListener(marker1, 'click', (function (marker1) {
        return function () {
             window.open(marker1.url, "_blank");
    }
    })(marker1));
    
    google.maps.event.addListener(marker1, 'mouseover', (function (marker1, content) {
        return function () {
            infowindow.setContent(content);
            infowindow.open(map, marker1);
//            map.setCenter(marker1.getPosition());
    }
    })(marker1, content));
    
    bounds.extend(marker1.position)
}
filterMarkers = function () {
    infowindow.close();
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
    map.fitBounds(bounds);
}
}
</script>

