<div class="modal-dialog">
    <div class="modal-content" >        
        <form class="product-edit">
	<div class="modal-header">                  
		<h4 class="modal-title">Feedback</h4>
	</div>
	<div class="modal-body">
		<p>Please take a few moments to answer these questions below, before confirming your configuration:</p>
		<ul class="products-list product-list-in-box" id="review">
			<?php
			foreach ($questions as $key => $value) {
				$checked = '';
				if(!empty($feedBackResponse)) {
					$checked = ($feedBackResponse[$value['id']] == 1)?'checked':'';
				}
				?>
			 <li class="item">
				<div class="product-info">
					<div class="row">						
						<div class="col-md-8"><?= $value['question']; ?></div>
						<div class="col-md-4" ><input  name="answer" type="checkbox"  <?= $checked ?> class="toggle"  data-toggle="toggle" data-on="YES" data-off="NO" ans="<?= $value['id'] ?>"></div>
					</div>
				</div>
			</li>
			<?php }?>
		</ul>
		<div id="rating">
			<input type="text" id="ratings-rating" value="<?= $storeRating ?>">
		</div>
	</div>
	<div class="modal-footer">		
		<button type="button" class="btn btn-primary ok-btn" id="submitQuestion">Submit Feedback</button>
	</div>
        </form>
    </div>
</div>