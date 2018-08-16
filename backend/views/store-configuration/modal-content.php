<?php 
use common\helpers\CommonHelper;
$dropdown_selected_id_edit = $_SESSION['config']['products'][$id]['brand']['id'];
?>

<div class="modal-body">
                   <div class="modal-content">
                        <form class="product-edit">
                            <div class="img-circle modal-image">
                                <input type="hidden" value="<?= $_SESSION['config']['products'][$id]['id'] ?>" id="editId" name="editId">
                                <img src="<?= CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $_SESSION['config']['products'][$id]['image']); ?>" alt="bottle-img">
                            </div>
                            <h4><?= isset($_SESSION['config']['products'][$id]['short_name']) ? $_SESSION['config']['products'][$id]['short_name'] : ''?></h4>
                            <p>Product Type :<?= isset($_SESSION['config']['products'][$id]['productType']['title']) ? $_SESSION['config']['products'][$id]['productType']['title'] : '' ?></p>
                            <p>Cat: <?= isset($_SESSION['config']['products'][$id]['productCategory']) ? $_SESSION['config']['products'][$id]['productCategory'] : '' ?></p>
                            <p>WSP per unit: <?= isset($_SESSION['config']['products'][$id]['price']) ? $_SESSION['config']['products'][$id]['price'] : '' ?></p>
                            <div class="checkbox-list">
                                <ul class="brand-list list-unstyled">
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input class="role" name="permissionscheck" value="remove" type="checkbox" id="remove">REMOVE
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input class="role" name="permissionscheck" value="edit" type="checkbox" id="edit">EDIT
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group">
                                <label>Brand</label>
                              
                                <select class="form-control" id="getProducts">
                                    <option disabled="disabled" selected="selected">Select Brand</option>
                                      <?php
                                      foreach ($_SESSION['config']['brands_data'] as $key=>$value){
                                          if($value['id'] == $dropdown_selected_id_edit){?>
                                             <option value="<?= $value['id'];?>" selected="selected"><?= $value['name'];?></option> 
                                          <?php }
                                          else{
                                      ?>
                                     <option value="<?= $value['id'];?>"><?= $value['name'];?></option>
                                      <?php }} ?>
                                </select>
                                <label>Product</label>
                                <select class="form-control" id="products">
                                   
                                </select>
                            </div>
                            <button type="button" class="btn btn-default pull-left reset-btn" data-dismiss="modal">Reset</button>
                            <button type="button" class="btn btn-primary ok-btn" id="changeData">Save</button>
                        </form>
                    </div>
                </div>

