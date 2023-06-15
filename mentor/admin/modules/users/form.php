<?php
$module_name = "users";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $users->get($_REQUEST['id']);
	if(!$get){
		header("location:".$base_url."/admin/users");
		die();
	}
}
function renderPermissionSwitch($permission, $value) {
    $checked = in_array($value, $permission) ? 'checked' : '';
    echo "
    <div class=\"col-auto align-middle d-flex mb-3\">
        <div class=\"form-check form-switch custom-switch\">
            <input class=\"form-check-input\" type=\"checkbox\" id=\"floating$value\" name=\"permission[]\" value=\"$value\" $checked>
            <label class=\"form-check-label\" for=\"floating$value\">$value</label>
        </div>
    </div>
    ";
}

$permission = isset($edit) && $edit && is_array(json_decode($get->permission)) ? json_decode($get->permission) : [];
?>

      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admin users</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/users" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div class="col">
		  <div class="alert alert-light">
	        <h6 class="mb-0"><i class="bi bi-info-circle-fill"></i> Remember: leave the password field empty if you don't want to change it. If you decide to change the password, write it down in a secure place, as it will not be possible to access the system without it.</h6>
	      </div>      	
      </div>

      <div>
	      <form action="/admin/users/action" method="post" novalidate enctype="multipart/form-data">
				 
					  <div class="row">
					    <div class="col-md-3">
				        <div class="form-floating mb-3">
				          <input name="name" type="text" class="form-control" id="floatingInputName" placeholder="Name" value="<?php if(isset($edit) && $edit){echo ($get->name ?? '');} ?>" required>
				          <label for="floatingInputName">Name</label>
				        </div>	   			    	
					    </div>

						  <div class="col-md-3">
				        <div class="form-floating mb-3">
				          <input name="email" type="email" class="form-control" id="floatingInputEmail" placeholder="E-mail" value="<?php if(isset($edit) && $edit){echo ($get->email ?? '');} ?>" required>
				          <label for="floatingInputEmail">E-mail</label>
				        </div>	   			    	
					    </div>

							<div class="col-md-4">
							    <div class="form-floating mb-0 position-relative">
							        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="password" value="<?php echo isset($_SESSION['form_password']) ? $_SESSION['form_password'] : ''; ?>" <?php if(!isset($edit) || !$edit){echo 'required';} ?> minlength="6">
							        <label for="floatingPassword">Password</label>
							        <i class="bi bi-eye-slash toggle-password"></i>
							    </div>
							    <div class="progress password-progress mt-2">
							        <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
							    </div>
							    <span id="password-strength-text" class="form-text"></span>
							    <br>
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
		           <fieldset class="border rounded-2 p-3 mb-4">
		              <legend><h5><i class="bi bi-lock"></i> Module permission:</h5></legend>
		              <p>Check the options that the user will have access permission</p>
		              <div class="row">
						<?php 
							renderPermissionSwitch($permission, "prompts");
							renderPermissionSwitch($permission, "categories");
							renderPermissionSwitch($permission, "customers");
							renderPermissionSwitch($permission, "languages");
							renderPermissionSwitch($permission, "pages");
							renderPermissionSwitch($permission, "theme");
							renderPermissionSwitch($permission, "menus");
							renderPermissionSwitch($permission, "sales");
							renderPermissionSwitch($permission, "credits_packs");
							renderPermissionSwitch($permission, "prompts_output");
							renderPermissionSwitch($permission, "prompts_tone");
							renderPermissionSwitch($permission, "prompts_writing");
							renderPermissionSwitch($permission, "users");
							renderPermissionSwitch($permission, "analytics");
							renderPermissionSwitch($permission, "seo");
							renderPermissionSwitch($permission, "settings");
						?>		              	
		              </div>
		           </fieldset>  								
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
			      <i class="bi bi-exclamation-octagon"></i>Attention: Please check all mandatory fields.
			    </div>
			    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			  </div>
			</div>			

<?php
require_once("../../inc/footer.php");
?>