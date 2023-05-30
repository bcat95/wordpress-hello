<?php
$module_name = "credits_packs";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

function generateDescriptionField($description = null) {
    $valueAttribute = $description ? ' value="' . htmlspecialchars($description) . '"' : '';
    echo '
        <div class="input-group mb-3 descriptionField">
            <input type="text" name="description[]" class="form-control" placeholder="Description"' . $valueAttribute . '>
            <button type="button" class="btn btn-success addDescription"><i class="bi bi-plus-circle"></i> Add new</button>
            <button type="button" class="btn btn-danger removeDescription"><i class="bi bi-trash"></i> Remove</button>
        </div>
    ';
}


if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $credits_packs->get($_REQUEST['id']);
	if(!$get){
		header("location:".$base_url."/admin/".$module_name);
		die();
	}
}
?>


      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Credits Pack</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/credits_packs" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div>
	      <form action="/admin/credits_packs/action" method="post" novalidate enctype="multipart/form-data">
				 <fieldset class="border rounded-2 p-3 mb-4">
				    <legend><h5>Fill in the fields below:</h5></legend>

					  <div class="row">

					    <div class="col-md-3 col-xl-2 col-lg-3">
						    <div class="mb-3">
							    <div class="wrapper-image-preview-form wrapper-image-preview-form-icon">
							    			<input name="image" type="file" class="form-control" id="image" accept="image/*" onchange="loadPreviewImage(event, 'imagePreview')">
							         <img class="img-fluid" id="imagePreview" src="<?php echo !empty($get->image) ? $base_url . '/public_uploads/' . $get->image : '#'; ?>" onerror="this.src='<?php echo $base_url; ?>/admin/img/placeholder-256.jpg'">
							    </div>
						    </div>

								<div class="col-md-12 align-middle d-flex">
								    <div class="form-check form-switch mb-3 custom-switch">
								        <?php 
								            $status = isset($edit) && $edit ? ($get->status ?? 1) : 1;
								            $checked = $status == 1 ? 'checked' : '';
								        ?>
								        <input class="form-check-input" type="checkbox" id="floatingStatus" <?php echo $checked; ?> onchange="updateSwitchValue('floatingStatus', 'hiddenStatus')">
								        <input type="hidden" name="status" id="hiddenStatus" value="<?php echo $status; ?>">
								        <label class="form-check-label" for="floatingStatus">Status</label>
								    </div>
								</div>	

					    </div>

					    <div class="col-md-10">
					    	<div class="row">
					    	
							    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Please enter the name of the package that will be displayed on the pricing page."></span>
						          <input name="name" type="text" class="form-control" id="floatingInputName" placeholder="Name (label)" value="<?php if(isset($edit) && $edit){echo ($get->name ?? '');} ?>" required>
						          <label for="floatingInputName">Name (label)</label>
						        </div>	   			    	
							    </div>

							    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Enter the three letters that represent your currency, for example: USD, BRL, AED."></span>
						          <input name="currency_code" type="text" class="form-control" id="floatingInputCurrencyCode" placeholder="Currency Code" value="<?php if(isset($edit) && $edit){echo ($get->currency_code ?? '');} ?>" required>
						          <label for="floatingInputCurrencyCode">Currency Code</label>
						        </div>	   			    	
							    </div>

							    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="In the amount field, the value must always be expressed in cents. Therefore, if you want to charge 10 dollars, you should enter 1000 in the field."></span>
						          <input name="amount" type="text" class="form-control" id="floatingInputAmount" placeholder="Amount" value="<?php if(isset($edit) && $edit){echo ($get->amount  ?? '');} ?>" required>
						          <label for="floatingInputAmount">Amount</label>
						        </div>	   			    	
							    </div>

							    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="You can write in this field however you prefer, for example: 10$. It's just a label to display in prices."></span>
						          <input name="price" type="text" class="form-control" id="floatingInputPrice" placeholder="Price" value="<?php if(isset($edit) && $edit){echo ($get->price ?? '');} ?>" required>
						          <label for="floatingInputPrice">Price (Label)</label>
						        </div>	   			    	
							    </div>

							    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This field represents the amount of credits that will be assigned to the user's account after the package purchase is completed."></span>
						          <input name="credit" type="text" class="form-control" id="floatingInputCredits" placeholder="Credits" value="<?php if(isset($edit) && $edit){echo ($get->credit ?? '');} ?>" required>
						          <label for="floatingInputCredits">Credits</label>
						        </div>	   			    	
							    </div>

									<div class="col-md-6">
									    <div id="descriptionFields">
									        <?php 
														if(isset($edit) && $edit){
														    $descriptionArray = json_decode($get->description);
														    if (empty($descriptionArray)) {
														        $descriptionArray = array("");
														    }
														    foreach ($descriptionArray as $description) {
														        generateDescriptionField($description);
														    }
														} else {
														    generateDescriptionField();
														}
									        ?>
									    </div>
									</div>		

									<div class="col-md-12">
										<div class="alert alert-light">Please refer to the Stripe documentation for more information:<br>
										<a target="_blank" href="https://stripe.com/docs/currencies">https://stripe.com/docs/currencies</a>
										</div>
									</div>

					    	</div>
					    </div>

					   </div>	    

				 </fieldset>
							         

	        <div class="d-grid">
	          <button class="btn btn-success text-uppercase fw-bold mb-2 submit-button" type="submit">Save</button>
	        </div>

	       <input type="hidden" name="id" value="<?php echo @$edit ? $get->id : ''; ?>">
	       <input type="hidden" name="action" value="<?php echo @$edit ? 'edit' : 'add'; ?>">
	      </form>
      </div>

 
			<div id="formErrorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
			  <div class="d-flex">
			    <div class="toast-body">
			      <i class="bi bi-exclamation-octagon"></i>Attention: Please check all mandatory fields.
			    </div>
			    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			  </div>
			</div>			

<?php
require_once("../../inc/footer.php");
?>