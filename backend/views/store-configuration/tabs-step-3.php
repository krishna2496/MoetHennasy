<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

//use kartik\switchinput\SwitchInput;
$rackProducts = isset($_SESSION['config']['rackProducts']) ? $_SESSION['config']['rackProducts'] : '';
$ratio = isset($_SESSION['config']['ratio']) ? $_SESSION['config']['ratio'] : '1';
?>

<div class="col-sm-5 pull-right" id="tab-step-3">
    <!-- Frame Filter section -->
    <div class="frame-filter-section">
        <div class="box filter-collapse-panle">
            <!-- collapsed-box -->
            <div class="box-header with-border">
                <h3 class="box-title">Display 3 <i class="fa fa-info-circle"></i></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down fa-3x"></i></button>
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
                                <ul class="brand-list-box">
                                    <li>
                                        <a href="#" title="Brand-Image">
                                            <img src="images/br-logo1.png" alt="brand-image" class="brand-images">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Brand-Image">
                                            <img src="images/br-logo2.png" alt="brand-image" class="brand-images">
                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Brand-Image">
                                            <!-- <img src="images/br-logo1.png" alt="brand-image" class="brand-images"> -->
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Brand-Image">
                                            <!-- <img src="images/br-logo2.png" alt="brand-image" class="brand-images"> -->
                                        </a>
                                    </li>
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
                                <div class="box box-default product-list collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">SHELF 1</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-target = "#demo"><i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body collapse">
                                     
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="direction-text">From left to right</p>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="view-btn">
                                                    <a href="#grid-section" title="grid-view" class="grid-btn"><img src="images/grid-btn.png" alt="grid-view"></a>
                                                    <a href="#list-section" title="list-view" class="list-btn"><img src="images/list-gray-btn.png" alt="list-view"></a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="grid-itmes " id="grid-section">
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="brand-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                            <div class="product-tooltip">
                                                                <h5>DOM PERIGNON VINTAGE 2006</h5>
                                                                <p>Product Type: Eg.NECTAR</p>
                                                                <p>Market Share: 1-10</p>
                                                                <p>WSP: NUM</p>
                                                                <p>Category: CHAMPAGNE</p>
                                                                <p>Top shelf: YES</p>
                                                            </div>
                                                        </a>

                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">

                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/btl-box.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                            <div class="product-tooltip lft">
                                                                <h5>DOM PERIGNON VINTAGE 2006</h5>
                                                                <p>Product Type: Eg.NECTAR</p>
                                                                <p>Market Share: 1-10</p>
                                                                <p>WSP: NUM</p>
                                                                <p>Category: CHAMPAGNE</p>
                                                                <p>Top shelf: YES</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="brand-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="list-items" id="list-section">
                                                    <div id="list-table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table id="list-table" class="table table-striped product-table dataTable no-footer" role="grid">
                                                                    <thead>
                                                                        <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1">Products</th><th class="sorting_disabled" rowspan="1" colspan="1">Product Types</th><th class="sorting_disabled" rowspan="1" colspan="1">Market share</th><th class="sorting_disabled" rowspan="1" colspan="1">WPS</th><th class="sorting_disabled" rowspan="1" colspan="1">Product Category</th><th class="sorting_disabled" rowspan="1" colspan="1">Top shelf</th></tr>
                                                                    </thead>
                                                                    <tbody>






                                                                        <tr role="row" class="odd">
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
                                                                        </tr><tr role="row" class="even">
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
                                                                        </tr><tr role="row" class="odd">
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
                                                                        </tr><tr role="row" class="even">
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
                                                                        </tr><tr role="row" class="odd">
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
                                                                        </tr></tbody></table></div></div><div class="row"><div class="col-sm-5"></div><div class="col-sm-7"></div></div></div>
                                                </div>
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                                <div class="box box-default product-list collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">SHELF 2</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="grid-itmes with-tool-tip" id="grid-section2">
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="brand-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="brand-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="list-items" id="list-section2">
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

                                                        </tbody></table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box box-default product-list collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">SHELF 3</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="direction-text">From left to right</p>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="view-btn">
                                                    <a href="#grid-section3" title="grid-view" class="grid-btn"><img src="images/grid-btn.png" alt="grid-view"></a>
                                                    <a href="#list-section3" title="list-view" class="list-btn"><img src="images/list-gray-btn.png" alt="list-view"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="grid-itmes" id="grid-section3">
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="brand-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="brand-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="list-items" id="list-section3">
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

                                                        </tbody></table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box box-default product-list collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">SHELF 4</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="direction-text">From left to right</p>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="view-btn">
                                                    <a href="#grid-section4" title="grid-view" class="grid-btn"><img src="images/grid-btn.png" alt="grid-view"></a>
                                                    <a href="#list-section4" title="list-view" class="list-btn"><img src="images/list-gray-btn.png" alt="list-view"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="grid-itmes" id="grid-section4">
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="brand-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="brand-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="bottle-image" class="product-image" data-toggle="modal" data-target="#modal-default">
                                                            <img src="images/bottle1.png" alt="bottle-image" class="btl-img">
                                                            <img src="images/right-icon.png" alt="Selected" class="brand-selected">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="list-items" id="list-section4">
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

                                                        </tbody></table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <script type="text/javascript">
                    var rackProducts = '<?php echo $rackProducts; ?>';
                    var ratio = '<?php echo $ratio; ?>';

                    if (rackProducts != '')
                    {
                        rackProducts = JSON.parse(rackProducts);
                        $.each(rackProducts, function (i, item) {
                            $.each(item, function (k, titem) {
                //                console.log(titem.id);
                //var height = titem.height*5.5+"px";
                                var width = titem.width * ratio + "px";

                                var data = '<img src="' + titem.image + '" style="width:' + width + '">';
                                $("#canvas-container-" + i).append(data);
                            });
                        });
                    }
                </script>
                                <?php Pjax::end(); ?>
                            </div>

                        </div>
                    </div>
                    <div class="submit-fl">
                        <button class="btn reset-btn">Reset</button>
                        <button class="btn" >OK</button>
                    </div>
                </form>


               
                
            </div>
        </div>
    </div>	
</div>