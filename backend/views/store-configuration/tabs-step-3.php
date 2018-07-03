<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;


//use kartik\switchinput\SwitchInput;
$rackProducts = isset($_SESSION['config']['rackProducts']) ? json_encode($_SESSION['config']['rackProducts']) : '';

$products = isset($_SESSION['config']['products']) ? $_SESSION['config']['products'] :'';
$shelvesData = isset($_SESSION['config']['shelvesProducts']) ? json_decode($_SESSION['config']['shelvesProducts'], true) : '';
//echo '<pre>';
//print_r($_SESSION['config']['shelvesProducts']);exit;
?>

<div class="col-sm-5 pull-right" id="tab-step-3">
    <!-- Frame Filter section -->
    <div class="frame-filter-section">
        <div class="box filter-collapse-panle">
            <!-- collapsed-box -->
            <div class="box-header with-border">
                <h3 class="box-title">Display 3 <i class="fa fa-info-circle"></i></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-up fa-3x"></i></button>
                </div>
                <!-- /.box-tools -->
            </div>
            <div class="box-body">

                <form class="frame-filt-form">
                    <div class="frame-chose-option">
                        <p class="auto-config">MH REF 356 - 15/06/2018 - Automatic configuration</p>
                        <div class="box box-default shelfs-store">
                            <div class="box-header with-border">
                                <h3 class="box-title">BRANDS CONFIGURATION </h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-up"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
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
                                <?php Pjax::begin(['id' => 'productsData', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>
                                <?php
                                if (!empty($shelvesData)) {
                                    foreach ($shelvesData as $key => $value) {
                                        ?>
                                        <div class="box box-default product-list collapsed-box">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">SHELF <?= $key+1 ?></h3>
                                                <div class="box-tools pull-right">
                                                    <button class="btn btn-box-tool" type="button" data-toggle="collapse" data-target="#collapseExample<?= $key?>" aria-expanded="false" aria-controls="collapseExample">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="collapse" id="collapseExample<?= $key?>">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="direction-text">From left to right</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="view-btn">
                                                            <a href="#grid-section2" title="grid-view" class="grid-btn"><img src="images/grid-btn.png" alt="grid-view"></a>
                                                            <a href="#list-section2" title="list-view" class="list-btn"><img src="images/list-gray-btn.png" alt="list-view"></a>
                                                        </div>
                                                    </div>
                                                </div>   
                                                <?php
                                                $ids = explode(',', $value['productIds']);
                                                if (!empty($ids)) { ?>
                                                 <div class="row">
                                                            <div class="col-md-12">
                                                                <ul class="grid-itmes with-tool-tip" id="grid-section2">
                                                <?php    foreach ($ids as $key1 => $value1) {
                                                    $url = CommonHelper::getPath('admin_url').'store-configuration/modal-content/'.$products[$value1]['id'];
//                                                        echo '<pre>';
//                                                        print_r($products);exit;
                                                        ?>
                                                        <!--$products[$value]['id'];-->

                                                       
                                                                    <li>
                                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-defaults" data-href="<?= $url; ?>" data-shelves="<?= $key; ?>" data-key="<?= $key1; ?>">
                                                                          
                                                                            <img src="<?= CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $products[$value1]['image']); ?>" alt="Selected"   class="btl-img">
                                                                            <img src="<?= CommonHelper::getImage(UPLOAD_PATH_IMAGES . 'right-icon.png'); ?>" alt="Selected" class="brand-selected display<?= $products[$value1]['image']; ?>" >
                                                                        </a>
                                                                    </li>
                                                               
<!--                                                                <div class="list-items" id="list-section2">
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
                                                                            <tr>
                                                                                <td>
                                                                                    <a href="#" title="Edit-Modal" class="media" data-toggle="modal" data-target="#modal-2">
                                                                                        <div class="media-left">
                                                                                            <div class="list-product">
                                                                                                <img src="images/bottle1.png" class="media-object">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="media-body">
                                                                                            <h4 class="media-heading">Dom perignon vintage 2006</h4>
                                                                                        </div>
                                                                                    </a>
                                                                                </td>
                                                                                <td>Eg.nectar</td>
                                                                                <td>1-10</td>
                                                                                <td>Num</td>
                                                                                <td>Campagne</td>
                                                                                <td>Yes</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>-->
                                                          
                                                    <?php
                                                    } ?>
                                                     </ul>
                                                             </div>



                                                        </div>
                                              <?php  }
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                                ?>
                                <script type="text/javascript">
                                    var rackProducts = '<?php echo $rackProducts; ?>';
                                    var ratio = '<?php echo $ratio; ?>';

                                    if (rackProducts != '')
                                    {
                                        rackProducts = JSON.parse(rackProducts);
                                        $.each(rackProducts, function (i, item) {
                                             
                                            $.each(item, function (k, titem) {
                                              
                                                var width = titem.width * ratio + "px";

                                                var data = '<img src="' + titem.image + '" style="width:' + width + '" id='+k+'>';
                                                $("#canvas-container-" + i).append(data);
                                            });
                                        });
                                    }
                                </script>
<?php Pjax::end(); ?>  


                            </div>
                        </div>
                        <div class="submit-fl">
                            <button class="btn reset-btn">Reset</button>
                            <button class="btn" >OK</button>
                        </div>
                </form>



               
            </div>
                    <div class="modal fade edit-modal" id="modal-defaults" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
              
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
                </form>
        </div>
       
    </div>	
</div>