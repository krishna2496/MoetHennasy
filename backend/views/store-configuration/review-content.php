<?php

use common\helpers\CommonHelper;
?>
<div class="modal-body">
    <div class="modal-content" >
        <div >
        <form class="product-edit">
            <ul class="products-list product-list-in-box" id="review">
                <?php
//                echo '<pre>';
//                print_r($questions);
//                exit;

                foreach ($questions as $key => $value) {
                    ?>


                    <li class="item">
                        
                        <div class="product-info">
                            <div class="row">
                                <div class="col-md-8" style="float:left"><?= $value['question']; ?></div>
                           
                                <div class="col-md-4" ><input  name="answer" type="checkbox" class="toggle"  data-toggle="toggle" data-on="YES" data-off="NO" ans="<?= $value['id'] ?>"></div>
                           </div>
                        </div>
                    </li>

                <?php }
                ?>




            </ul>
            <div id="rating">
            
                
                    <input type="text" id="ratings-rating" value=''/>
                
               
        </div>
            <button type="button" class="btn btn-default pull-left reset-btn" data-dismiss="modal">Reset</button>
            <button type="button" class="btn btn-primary ok-btn" id="submitQuestion">Ok</button>
        </form>
        </div>
        
    </div>
</div>

