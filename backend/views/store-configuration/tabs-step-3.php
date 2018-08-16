<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

//use kartik\switchinput\SwitchInput;
$submitUrl = "store-configuration/save-config-data";
?>
<script>
var removeData = new Array();

</script>
<div class="col-sm-5 pull-right" id="tab-step-3" style="z-index:5">
    <!-- Frame Filter section -->
    <div class="frame-filter-section">
        <div class="box filter-collapse-panle">
            <!-- collapsed-box -->
            <div class="box-header with-border">
                <h3 class="box-title">Display <i class="fa fa-info-circle"></i></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-up fa-3x"></i></button>
                </div>
                <!-- /.box-tools -->
            </div>

            <div class="box-body">
                <?php
                $form = ActiveForm::begin(['action' => [$submitUrl], 'id' => 'step_3', 'method' => 'post', 'class' => 'frame-filt-form']);
                ?>

                <input type="hidden" name="thumb_image" id="thumb_image" value=""/>
                <input type="hidden" name="brand" id="brand" value=""/>
                <input type="hidden" name="config_id" id="config_id" value="<?= $configId ?>"/>
                <div class="frame-chose-option">
                    <p class="auto-config"><span id="displayName"></span>- Automatic configuration</p>
                    <div class="box box-default shelfs-store">
                        <div class="box-header with-border">
                            <h3 class="box-title">BRANDS CONFIGURATION </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-up"></i></button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->                            
                        <div class="box-body" style="padding-top: 0px; padding-bottom: 0px;">
                            <?php Pjax::begin(['id' => 'productsBrand', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>
                            <ul class="brand-list-box" style="list-style: none !important;">
                                <?php
                                if (isset($_SESSION['config']['brands_data']) && (!empty($_SESSION['config']['brands_data']))) {
                                    foreach ($_SESSION['config']['brands_data'] as $key => $value) {
                                        ?>
                                        <li>
                                            <a title="<?= $value['name'] ?>">
                                                <img src="<?= CommonHelper::getImage(UPLOAD_PATH_BRANDS_IMAGES . $value['image']); ?>" alt="brand-image" class="brand-images" id="<?= $value['id'] ?>" onclick="changeBrand(this)">
                                                <img src="<?= CommonHelper::getImage(UPLOAD_PATH_IMAGES . 'right-icon.png'); ?>" alt="Selected" class="brand-selected display<?= $value['id']; ?>"  style="display:none">
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <?php Pjax::end(); ?>
                        </div>
                        <!-- /.box-body -->
                    </div>

                    <div class="box box-default shelfs-store2">
                        <div class="box-header with-border">
                            <h3 class="box-title">PRODUCTS CONFIGURATION</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-up"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <div class="box-body">
                            <?php Pjax::begin(['id' => 'productsData', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]) ?>
                            <?php
                            $rackProducts = isset($_SESSION['config']['rackProducts']) ? json_encode($_SESSION['config']['rackProducts']) : '';
                            $products = isset($_SESSION['config']['products']) ? $_SESSION['config']['products'] : '';
                            $shelvesData = isset($_SESSION['config']['shelvesProducts']) ? json_decode($_SESSION['config']['shelvesProducts'], true) : '';
                            if (!empty($shelvesData)) {
                                foreach ($shelvesData as $key => $value) {
                                    ?>
                                    <div class="box box-default product-list collapsed-box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">SHELF <?= $key + 1 ?></h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" type="button" data-toggle="collapse" data-target="#collapseExample<?= $key ?>" aria-expanded="false" aria-controls="collapseExample">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapseExample<?= $key ?>">
                                            <div class="box-body" style="display: block !important;">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <p class="direction-text">From left to right</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="view-btn">
                                                            <span class="btn er" data-key="<?= $key?>">Delete All</span>
                                                            <a href="#grid-section<?= $key ?>" title="grid-view" class="grid-btn"><img src="<?php echo Yii::$app->request->baseUrl . '/images/grid-btn.png'; ?>" alt="grid-view"></a>
                                                            <a href="#list-section<?= $key ?>" title="list-view" class="list-btn"><img src="<?php echo Yii::$app->request->baseUrl . '/images/list-gray-btn.png'; ?>" alt="list-view"></a>
                                                        </div>
                                                    </div>
                                                </div>   
        <?php
        $ids = explode(',', $value['productIds']);
        if (!empty($ids)) {
            ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <ul class="grid-itmes with-tool-tip" id="grid-section<?= $key ?>">
                                                    <?php
                                                    foreach ($ids as $key1 => $value1) {
                                                        if (!empty($value1)) {
                                                            $url = CommonHelper::getPath('admin_url') . 'store-configuration/modal-content/' . $products[$value1]['id'];
                                                        
                                                            ?>
                                                                <li>
                                                                    <a href="#" class="product-image" data-toggle="modal" data-target="#modal-defaults" data-href="<?= $url; ?>" data-shelves="<?= $key; ?>" data-key="<?= $key1; ?>">
                                                                        <img src="<?= CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $products[$value1]['image']); ?>" alt="Selected"   class="btl-img">
                                                                        <img src="<?= CommonHelper::getImage(UPLOAD_PATH_IMAGES . 'right-icon.png'); ?>" alt="Selected" class="brand-selected display<?= $products[$value1]['image']; ?>">
                                                                        <div class="product-tooltip">
                                                                            <h5><?= $products[$value1]['short_name'] ?></h5>
                                                                            <p>Product Type: <?= $products[$value1]['productType']['title'] ?></p>
                                                                            <p>Market Share: <?= $products[$value1]['market_share']; ?></p>
                                                                            <p>WSP: <?= $products[$value1]['price'] ?></p>
                                                                            <p>Category: <?= $products[$value1]['productCategory'] ?></p>
                                                                            <p>Top shelf: <?= ($products[$value1]['top_shelf'] == 1) ? 'Yes' : 'No' ?></p>
                                                                        </div>
                                                                    </a>
                                                                     <input type="checkbox" shelves-key='<?= $key ?>' item-key='<?= $key1 ?>' id='<?= $key+1; ?>,<?= $key1+1; ?>'>
                                                                </li>
                <?php }
            }
            ?>
                                                            </ul>

                                                            <div class="list-items" id="list-section<?= $key ?>">
                                                                <table id="list-table" class="table  table-striped product-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Products</th>
                                                                            <th>Product Types</th>
                                                                            <th>Market share</th>
                                                                            <th>WPS</th>
                                                                            <th>Product Category</th>
                                                                            <th>Top shelf</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
            <?php
            foreach ($ids as $key1 => $value1) {
                if (!empty($value1)) {
                    $url = CommonHelper::getPath('admin_url') . 'store-configuration/modal-content/' . $products[$value1]['id'];
                    ?>

                                                                    <tr>
                                                                        <td>
                                                                            <a href="#" class="product-image media" data-toggle="modal" data-target="#modal-defaults" data-href="<?= $url; ?>" data-shelves="<?= $key; ?>" data-key="<?= $key1; ?>">
                                                                                <div class="media-left">
                                                                                    <div class="list-product">
                                                                                        <img src="<?= CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $products[$value1]['image']); ?>" class="media-object">
                                                                                    </div>
                                                                                    <input type="checkbox" shelves-key='<?= $key ?>' item-key='<?= $key1 ?>' id='<?= $key+1; ?>,<?= $key1+1; ?>'>
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <h4 class="media-heading"><?= $products[$value1]['short_name'] ?></h4>
                                                                                </div>
                                                                            </a>
                                                                        </td>
                                                                        <td><?= $products[$value1]['productType']['title'] ?></td>
                                                                        <td><?= $products[$value1]['market_share']; ?></td>
                                                                        <td><?= $products[$value1]['price'] ?></td>
                                                                        <td><?= $products[$value1]['productCategory'] ?></td>
                                                                        <td><?= ($products[$value1]['top_shelf'] == 1) ? 'Yes' : 'No' ?></td>
                                                                    </tr>
                <?php }
            } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
        <?php }
        ?>
                                            </div>
                                        </div>
                                    </div>
        <?php
    }
}
?>
                            <script type="text/javascript">
$(".er").on("click",function(){
    if(removeData.length != 0){
        var index = $(this).attr('data-key');
         var value= removeData;
         var data = {index: index,value: value};
            moet.ajax("<?php echo CommonHelper::getPath('admin_url') ?>store-configuration/delete-all", data, 'post').then(function (result) {
               numOfSelves = $("#ex6SliderVal").val();
                for (i = 0; i < numOfSelves; i++) {
                    $("#canvas-container-" + i).empty();
                }
                
                removeData = [];
                $.pjax.reload({container: "#productsData", async: false});
                alert(result.msg);
                return false;
            });
    }else{
        alert("Please select at least one product");
    }
}); 
                             
                                var rackProducts = '<?php echo $rackProducts; ?>';
                                var ratio = '<?php echo $ratio; ?>';
                           
                                if (rackProducts != '')
                                {
                                    rackProducts = JSON.parse(rackProducts);
                                    if (rackProducts == '') {
                                        $(".submitData").attr('disabled', 'true');
                                    }
                                    $.each(rackProducts, function (i, item)
                                    {
                                        bottleLeft = 0;
                                        $.each(item, function (k, titem)
                                        {
                                            var height = titem.height * ratio + "px";
                                            var width = titem.width * ratio + "px";
                                            var data = '<img src="' + titem.image + '" style="width:' + width + '; height:' + height + ';position: absolute; bottom:5px; left:' + bottleLeft + 'px;" id=' + k + '>';
                                            $("#canvas-container-" + i).append(data);
                                            bottleLeft = bottleLeft + (titem.width * ratio);
                                        });
                                    });
                                }

        $('input').on('ifChecked', function (event) {
                removeData.push($(this).attr("item-key"));
        });
        
        $('input').on('ifUnchecked', function (event) {
                popedValue = removeData.indexOf( $(this).attr('item-key'));
                removeData.splice(popedValue, 1);
        });
       
      
       
                            </script>
                            <?php Pjax::end(); ?>  
                        </div>
                    </div>
                    <div class="submit-fl">                            
                       
                        <button class="btn btn-save next submitData" >Save</button>
                    </div>
                </div>
                <div class="modal fade edit-modal" id="modal-defaults" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content product-content">

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                
                <?php
                ActiveForm::end();
                ?>
            </div>       
        </div> 
    </div>
</div>
 
<script type="text/javascript">
    var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
    
    $(document).ready(function ()
    {
        $('.edit-modal').on('show.bs.modal', function (event)
        {
            var dataURL = $(event.relatedTarget).attr('data-href');
            var dataKey = $(event.relatedTarget).attr('data-key');
            var dataShelves = $(event.relatedTarget).attr('data-shelves');

            $('.product-content').load(dataURL, function ()
            {
                $('input[type="checkbox"]').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%'
                });
                $("#getProducts").attr("disabled", true);
                $("#products").attr("disabled", true);
                $('#remove').on('ifChecked', function () {

                    $('input[name="permissionscheck"]').filter('[value="edit"]').iCheck('uncheck');
                    $("#getProducts").attr("disabled", true);
                    $("#products").attr("disabled", true);
                });
                $('#edit').on('ifChecked', function () {
                    $('input[name="permissionscheck"]').filter('[value="remove"]').iCheck('uncheck');
                    $("#getProducts").removeAttr('disabled');
                    $("#products").removeAttr('disabled');
                });
                
                $('#edit').on('ifUnchecked', function () {
                    $("#getProducts").attr("disabled", true);
                    $("#products").attr("disabled", true);
                });

                $('#getProducts').on('change', function () {
                    var id = $(this).val();
                    var str = "<option value>Select Products</option>";
                    var data = {id: id};
                    moet.ajax("<?php echo CommonHelper::getPath('admin_url') ?>store-configuration/get-products", data, 'post').then(function (result) {
                        if (result.status.success == 1) {
                            if (result.data.catalogues.length > 0) {
                                $.each(result.data.catalogues, function (key, value) {
                                    str += "<option value=" + value.id + ">" + value.short_name + "</option>";
                                });
                            }
                        }
                        $('#products').html(str);
                    }, function (result) {
                        alert('Fail');
                    });

                });

                $('#changeData').on('click', function (e)
                {
                    e.stopImmediatePropagation();

                    var remove = $('#remove').is(':checked');
                    var edit = $('#edit').is(':checked');

                    if(remove === false && edit === false){
                        alert("Please Select one option");
                        return false;
                    }
                    
                    var product = $("#products").val();
                    var ratio = '<?php echo $ratio; ?>';
                    var data = {remove: remove, edit: edit, product: product, dataKey: dataKey, dataShelves: dataShelves};
                    moet.ajax("<?php echo CommonHelper::getPath('admin_url') ?>store-configuration/edit-products", data, 'post').then(function (result) {

                        numOfSelves = $("#ex6SliderVal").val();
                        if (result.flag == 1) {
                            if ((result.action == 'edit')) {

                                for (i = 0; i < numOfSelves; i++) {
                                    $("#canvas-container-" + i).empty();
                                }
                                $.pjax.reload({container: "#productsData", async: false});
                            }
                            if (result.action == 'remove') {
                                console.log(result);
                                for (i = 0; i < numOfSelves; i++) {
                                    $("#canvas-container-" + i).empty();
                                }
                                $.pjax.reload({container: "#productsData", async: false});
                             
                            }
                            $('.edit-modal').modal('hide');
                            alert(result.msg);
                        } else {

                            alert(result.msg);
                        }

                    }, function (result) {
                        alert('Fail');
                    });
                });
                moet.hideLoader();
            });
            setTimeout(function () {
                $('.modal-backdrop').css('z-index', 0);
            }, 10);
        });
       
    });
    
</script>