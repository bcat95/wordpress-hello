<?php
$module_name = "pages";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $pages->get($_REQUEST['id']);
	if(!$get){
		header("location:".$base_url."/admin/".$module_name);
		die();
	}
}
?>


      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pages</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/pages" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div>
	      <form action="/admin/pages/action" method="post" novalidate enctype="multipart/form-data">
				 
				    
					  <div class="row">
					    <div class="col-md-5">
				        <div class="form-floating mb-3">
				          <input name="name" type="text" class="form-control" id="floatingInputName" placeholder="Name" value="<?php if(isset($edit) && $edit){echo ($get->name ?? '');} ?>" required>
				          <label for="floatingInputName">Name</label>
				        </div>	   			    	
					    </div>

					    <div class="col-md-5">
				        <div class="form-floating mb-3">
				          <input name="slug" type="text" class="form-control" id="floatingInputSlug" placeholder="Slug" value="<?php if(isset($edit) && $edit){echo ($get->slug ?? '');} ?>" required>
				          <label for="floatingInputSlug">Slug</label>
				        </div>	   			    	
					    </div>

							<div class="col-md-2 align-middle d-flex">
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

					    <div class="col-md-12">
				        <div class="form-floating mb-3">
				          <input name="meta_title" type="text" class="form-control" id="floatingInputMetaTitle" placeholder="Meta Title" value="<?php if(isset($edit) && $edit){echo ($get->meta_title ?? '');} ?>" required>
				          <label for="floatingInputMetaTitle">Meta Title</label>
				        </div>	   			    	
					    </div>	

					    <div class="col-md-12">
				        <div class="form-floating mb-3">
				          <input name="meta_description" type="text" class="form-control" id="floatingInputMetaDescription" placeholder="Meta Description" value="<?php if(isset($edit) && $edit){echo ($get->meta_description ?? '');} ?>" required>
				          <label for="floatingInputMetaDescription">Meta Description</label>
				        </div>	   			    	
					    </div>							

							<div class="col-md-12">
	              <div class="form-group">
	                <textarea id="editor" name="content"><?php echo isset($get->content) ? $get->content : ''; ?></textarea>
	              </div>								
							</div>

					   </div>	    



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
			      <i class="bi bi-exclamation-octagon"></i> Attention: Please check all mandatory fields.
			    </div>
			    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			  </div>
			</div>			

<?php
require_once("../../inc/footer.php");
?>