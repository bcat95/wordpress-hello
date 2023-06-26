<?php
$module_name = "prompts";
$use_save_absolute = true;
$use_select2 = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
$getCategories = $categories->getList();
$categoriesArray = array();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $prompts->get($_REQUEST['id']);
	$getCategoriesByIdPrompt = $prompts_categories->getListByIdPrompt($get->id);
	foreach ($getCategoriesByIdPrompt as $showCategories) {
	    $categoriesArray[] = $showCategories->id_category; // você deve adicionar o id_category ao array
	}
	if(!$get){
		header("location:".$base_url."/admin/".$module_name);
		die();
	}
}
?>

<!-- Modal -->
<div class="modal modal-xl fade" id="modalModels" tabindex="-1" aria-labelledby="modalModelsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalModelsLabel">Description of models</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" width="160">Model</th>
                    <th scope="col">Description</th>
                    <th scope="col">Max tokens</th>
                    <th scope="col">Training data</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>gpt-3.5-turbo</td>
                    <td>Most capable GPT-3.5 model and optimized for chat at 1/10th the cost of <code>text-davinci-003</code>. Will be updated with our latest model iteration 2 weeks after it is released.</td>
                    <td>4,096 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr>

                <tr>
                    <td>gpt-3.5-turbo-0613</td>
                    <td>Snapshot of gpt-3.5-turbo from June 13th 2023 with function calling data. Unlike gpt-3.5-turbo, this model will not receive updates, and will be deprecated 3 months after a new version is released.	</td>
                    <td>4,096 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr>

								<tr>
                    <td>gpt-3.5-turbo-16k</td>
                    <td>Same capabilities as the standard gpt-3.5-turbo model but with 4 times the context.</td>
                    <td>16,384 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr> 

								<tr>
                    <td>gpt-3.5-turbo-0613</td>
                    <td>Snapshot of gpt-3.5-turbo from June 13th 2023 with function calling data. Unlike gpt-3.5-turbo, this model will not receive updates, and will be deprecated 3 months after a new version is released.	</td>
                    <td>4,096 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr> 

								<tr>
                    <td>gpt-3.5-turbo-16k-0613</td>
                    <td>Snapshot of gpt-3.5-turbo-16k from June 13th 2023. Unlike gpt-3.5-turbo-16k, this model will not receive updates, and will be deprecated 3 months after a new version is released.</td>
                    <td>16,384 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr> 

								<tr>
                    <td>gpt-4</td>
                    <td>More capable than any GPT-3.5 model, able to do more complex tasks, and optimized for chat. Will be updated with our latest model iteration 2 weeks after it is released.	</td>
                    <td>8,192 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr>

								<tr>
                    <td>gpt-4-0613</td>
                    <td>Snapshot of gpt-4 from June 13th 2023 with function calling data. Unlike gpt-4, this model will not receive updates, and will be deprecated 3 months after a new version is released.</td>
                    <td>8,192 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr>

								<tr>
                    <td>gpt-4-32k</td>
                    <td>Same capabilities as the base gpt-4 mode but with 4x the context length. Will be updated with our latest model iteration.	</td>
                    <td>32,768 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr>

								<tr>
                    <td>gpt-4-32k-0613</td>
                    <td>Snapshot of gpt-4-32 from June 13th 2023. Unlike gpt-4-32k, this model will not receive updates, and will be deprecated 3 months after a new version is released.</td>
                    <td>32,768 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr> 								
                <tr>
                    <td>text-davinci-003</td>
                    <td>Can do any language task with better quality, longer output, and consistent instruction-following than the curie, babbage, or ada models. Also supports some additional features such as inserting text.</td>
                    <td>4,097 tokens</td>
                    <td>Up to Sep 2021</td>
                </tr>                
                
            </tbody>
        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Prompts</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/prompts" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div>
	      <form action="/admin/prompts/action" method="post" novalidate enctype="multipart/form-data">
				 <fieldset class="border rounded-2 p-3 mb-4">
				    <legend><h5>Basic information</h5></legend>

			  <div class="row">
			    <div class="col-md-4 col-lg-3">

				    <div class="mb-3">
					    <div class="wrapper-image-preview-form">
					    			<input name="image" type="file" class="form-control" id="image" accept="image/*" onchange="loadPreviewImage(event, 'imagePreview')">
					         <img class="img-fluid" id="imagePreview" src="<?php echo !empty($get->image) ? $base_url . '/public_uploads/' . $get->image : '#'; ?>" onerror="this.src='<?php echo $base_url; ?>/admin/img/placeholder.jpg'">
					    </div>
				    </div>	

			    </div>
			    <div class="col-md-8 col-lg-9">

					  <div class="row">

							<div class="col-md-12">
							    <div class="form-check form-switch mb-3 custom-switch">
							        <input class="form-check-input" type="checkbox" id="floatingDisplayStatus" 
							            <?php if (!isset($edit) || ($edit && ($get->status ?? 0) == 1)) { echo 'checked'; } ?>
							            onchange="updateSwitchValue('floatingDisplayStatus', 'hiddenDisplayStatus')">
							        <input type="hidden" name="status" id="hiddenDisplayStatus" 
							            value="<?php echo (!isset($edit) || ($edit && $get->status == 1)) ? 1 : 0; ?>">
							        <label class="form-check-label" for="floatingDisplayStatus">Enable/Disabled in website</label>
							    </div>
							</div>

					    <div class="col-md-12">
				        <div class="form-floating mb-3">
				        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Name of the employee that will be displayed."></span>
				          <input name="name" type="text" class="form-control" id="floatingInputName" placeholder="AI Name" value="<?php if(isset($edit) && $edit){echo ($get->name ?? '');} ?>" required>
				          <label for="floatingInputName">AI Name</label>
				        </div>	   			    	
					    </div>					    

					    <div class="col-md-6">
				        <div class="form-floating mb-3">
				        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="In which area is the AI an expert? (Example: Finance, Marketing, etc.)"></span>
				          <input name="expert" type="text" class="form-control" id="floatingExpert" placeholder="Expert" value="<?php if(isset($edit) && $edit){echo ($get->expert ?? '');} ?>" required>
				          <label for="floatingExpert">Expert in</label>
				        </div>		             			    	
					    </div>

					    <div class="col-md-6">
				        <div class="form-floating mb-3">
				        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="The slug is the field that is inserted in the URL to identify a specific page, such as /chat/ai-name, where 'ai-name' is the slug that identifies the corresponding AI page."></span>
				          <input name="slug" type="text" class="form-control" id="floatingInputSlug" placeholder="Expert" value="<?php if(isset($edit) && $edit){echo ($get->slug ?? '');} ?>" required>
				          <label for="floatingInputSlug">Slug (URL)</label>
				        </div>		             			    	
					    </div>

					   </div>

						<div class="col-md-3">
						    <div class="form-check form-switch mb-3 custom-switch">
						        <input class="form-check-input" type="checkbox" id="floatingDisplayDescription" <?php if (isset($edit) && $edit && ($get->display_description ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayDescription', 'hiddenDisplayDescription')">
						        <input type="hidden" name="display_description" id="hiddenDisplayDescription" value="<?php echo isset($edit) && $edit ? $get->display_description : 0; ?>">
						        <label class="form-check-label" for="floatingDisplayDescription">Show description?</label>
						    </div>
						</div>

					  <div class="form-floating mb-3">
					  	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="First-person description introducing the AI (This data is displayed in a top icon in the chat conversation)"></span>
					    <textarea name="description" class="form-control text-area-custom-h" id="floatingDescription" placeholder="Description"><?php if(isset($edit) && $edit){echo ($get->description ?? '');} ?></textarea>
					    <label for="floatingDescription">Description</label>
					  </div>

						<div class="col-md-3">
						    <div class="form-check form-switch mb-3 custom-switch">
						        <input class="form-check-input" type="checkbox" id="floatingDisplayWelcomeMessage" <?php if (isset($edit) && $edit && ($get->display_welcome_message ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayWelcomeMessage', 'hiddenDisplayWelcomeMessage')">
						        <input type="hidden" name="display_welcome_message" id="hiddenDisplayWelcomeMessage" value="<?php echo isset($edit) && $edit ? $get->display_welcome_message : 0; ?>">
						        <label class="form-check-label" for="floatingDisplayWelcomeMessage">Display welcome message?</label>
						    </div>
						</div>

					  <div class="form-floating mb-3">
					  	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Welcome message, it is the first message that the AI will send when the user opens their chat."></span>
					    <textarea name="welcome_message" class="form-control text-area-custom-h" id="flexCheckDefault" placeholder="Description"><?php if(isset($edit) && $edit){echo ($get->welcome_message ?? '');} ?></textarea>
					    <label for="floatingWelcomeMessage">Welcome Message</label>
					  </div>

			    </div>		    

				 </fieldset>

				 <fieldset class="border rounded-2 p-3 mb-4">
				    <legend><h5><span data-feather="filter"></span> Categories</h5></legend>

							<div class="form-floating mb-3">
								<select class="form-select select2" id="multiple-select-field" name="categories[]" data-placeholder="Categories" multiple>
								    <?php foreach ($getCategories as $showCategories) {?>
								        <option 
								            value="<?php echo $showCategories->id; ?>" 
								            <?php echo in_array($showCategories->id, $categoriesArray) ? 'selected' : ''; ?>
								        >
								            <?php echo $showCategories->name; ?><?php echo $showCategories->status == '0' ? ' (Disabled)' : ''; ?>
								        </option>
								    <?php } ?>
								</select>
			        </div>	
 
				  </fieldset>

				 <fieldset class="border rounded-2 p-3 mb-4">
				    <legend><h5><span data-feather="cpu"></span> AI training</h5></legend>
		        <div class="form-floating mb-3">
		        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This is the most important field. Here, you should elaborate a prompt for the AI, defining who it will be, how it should behave, and respond to users."></span>
		        	<textarea name="prompt" class="form-control text-area-custom-h" id="floatingPrompt" placeholder="Prompt" required><?php if(isset($edit) && $edit){echo ($get->prompt ?? '');} ?></textarea>
		          <label for="floatingPrompt">Prompt</label>
		        </div>				    
				  </fieldset>

					<fieldset class="border rounded-2 p-3 mb-4">
					  <legend><h5><span data-feather="box"></span> Parameters</h5></legend>
					  <div class="row">
							<div class="col-md-4">
							    <div class="form-floating mb-3">
											<select name="temperature" class="form-control" id="floatingTemperature" required>
											    <?php
											    $temperatures = [1, 0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1];
											    $selectedTemperature = isset($edit) && $edit ? ($get->temperature ?? 1) : 1;

											    foreach ($temperatures as $temp) {
											        $selected = $temp == $selectedTemperature ? 'selected' : '';
											        echo "<option value='$temp' $selected>$temp</option>";
											    }
											    ?>
											</select>
							        <label for="floatingTemperature">Temperature</label>
							    </div>
							</div>


					    <div class="col-md-4">
					      <div class="form-floating mb-3">
										<select name="frequency_penalty" class="form-control" id="floatingFrequencyPenalty" required>
										    <?php
										    $penalties = [1, 0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1, 0];
										    $selectedPenalty = isset($edit) && $edit ? ($get->frequency_penalty ?? 0) : 0;

										    foreach ($penalties as $penalty) {
										        $selected = $penalty == $selectedPenalty ? 'selected' : '';
										        echo "<option value='$penalty' $selected>$penalty</option>";
										    }
										    ?>
										</select>
					        <label for="floatingFrequencyPenalty">Frequency Penalty</label>
					      </div>
					    </div>

					    <div class="col-md-4">
					      <div class="form-floating mb-3">
									<select name="presence_penalty" class="form-control" id="floatingPresencePenalty" required>
									    <?php
									    $penalties = [1, 0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1, 0];
									    $selectedPenalty = isset($edit) && $edit ? ($get->presence_penalty ?? 0) : 0;

									    foreach ($penalties as $penalty) {
									        $selected = $penalty == $selectedPenalty ? 'selected' : '';
									        echo "<option value='$penalty' $selected>$penalty</option>";
									    }
									    ?>
									</select>					        
					        <label for="floatingPresencePenalty">Presence Penalty</label>
					      </div>
					    </div>

					    <div class="col-md-3">
					      <div class="form-floating mb-3">
					        <input name="chat_minlength" type="number" class="form-control" id="floatingChatMinlength" placeholder="Chat Minlength" value="<?php echo (isset($edit) && $edit) ? ($get->chat_minlength ?? '') : '5'; ?>" required>
					        <label for="floatingChatMinlength">Chat Minlength</label>
					      </div>
					    </div>

					    <div class="col-md-3">
					      <div class="form-floating mb-3">
					        <input name="chat_maxlength" type="number" class="form-control" id="floatingChatMaxlength" placeholder="Chat Maxlength" value="<?php echo (isset($edit) && $edit) ? ($get->chat_maxlength ?? '') : '1000'; ?>" required>
					        <label for="floatingChatMaxlength">Chat Maxlength</label>
					      </div>
					    </div>


							<div class="col-md-3">
								<div class="form-floating mb-3">
								    <select name="API_MODEL" class="form-control" id="floatingAPIModel" required>
								        <optgroup label="GPT-3">
								            <option value="gpt-3.5-turbo" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-3.5-turbo') { echo 'selected'; } ?>>gpt-3.5-turbo</option>
								            <option value="gpt-3.5-turbo-0613" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-3.5-turbo-0613') { echo 'selected'; } ?>>gpt-3.5-turbo-0613</option>
								            <option value="gpt-3.5-turbo-16k" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-3.5-turbo-16k') { echo 'selected'; } ?>>gpt-3.5-turbo-16k</option>
								            <option value="gpt-3.5-turbo-16k-0613" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-3.5-turbo-16k-0613') { echo 'selected'; } ?>>gpt-3.5-turbo-16k-0613</option>
								        </optgroup>
								        <optgroup label="GPT-4">
								            <option value="gpt-4" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-4') { echo 'selected'; } ?>>gpt-4</option>
								            <option value="gpt-4-0613" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-4-0613') { echo 'selected'; } ?>>gpt-4-0613</option>
								            <option value="gpt-4-32k" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-4-32k') { echo 'selected'; } ?>>gpt-4-32k</option>
								            <option value="gpt-4-32k-0613" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-4-32k-0613') { echo 'selected'; } ?>>gpt-4-32k-0613</option>
								        </optgroup>
								        <optgroup label="Text Davinci">
								            <option value="text-davinci-003" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'text-davinci-003') { echo 'selected'; } ?>>text-davinci-003</option>
								        </optgroup>
								    </select>
								    <label for="floatingAPIModel">API Model</label>
								</div>
							</div>			  				    				  				  

							<div class="col-md-3">
								<span data-bs-toggle="modal" data-bs-target="#modalModels" class="btn btn-outline-primary mt-2"><i class="bi bi-info-circle"></i> Model's info</span>
							</div>
						</div>

						</fieldset>			

						<fieldset class="border rounded-2 p-3 mb-4">
					  	<legend><h5><span data-feather="play-circle"></span> Text to Speech - Google</h5></legend>
						  	<div class="row align-middle">
								<div class="col-md-4 align-middle d-flex">
								  <div class="form-check form-switch mb-3 custom-switch">
								    <input class="form-check-input" type="checkbox" name="use_google_voice" id="floatingUseGoogleVoice" value="1" <?php if (isset($edit) && $edit && ($get->use_google_voice ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingUseGoogleVoice', 'hiddenUseGoogleVoice')">
								    <input type="hidden" name="use_google_voice" id="hiddenUseGoogleVoice" value="<?php echo isset($edit) && $edit ? $get->use_google_voice : 0; ?>">
								    <label class="form-check-label" for="floatingUseGoogleVoice">Use Google Speech-to-Text?</label>
								  </div>
								</div>

						    <div class="col-md-4">
								  <div class="form-floating mb-3">
								    <input name="google_voice" type="text" class="form-control" id="floatingGoogleVoice" placeholder="Google Voice" value="<?php if(isset($edit) && $edit){echo ($get->google_voice ?? '');} ?>">
								    <label for="floatingGoogleVoice">Google Voice Name</label>
								  </div>
								</div>					    				  				  
					
						    <div class="col-md-4">
								  <div class="form-floating mb-3">
								    <input name="google_voice_lang_code" type="text" class="form-control" id="floatingGoogleVoiceLangCode" placeholder="Google Voice Lang Code" value="<?php if(isset($edit) && $edit){echo ($get->google_voice_lang_code ?? '');} ?>">
								    <label for="floatingGoogleVoiceLangCode">Google Voice Lang Code</label>
								  </div>		
								</div>					  		
					  	</div>
						</fieldset>	


						<fieldset class="border rounded-2 p-3 mb-4">
					  		<legend><h5><span data-feather="mic"></span> Speechto Text - Google (Using Microphone)</h5></legend>
						  	<div class="row align-middle">


							<div class="col-md-4 align-middle d-flex">
							    <div class="form-check form-switch mb-3 custom-switch">
							        <input class="form-check-input" type="checkbox" id="floatingDisplayMicrophone" <?php if (isset($edit) && $edit && ($get->display_mic ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayMicrophone', 'hiddenDisplayMicrophone')">
							        <input type="hidden" name="display_mic" id="hiddenDisplayMicrophone" value="<?php echo isset($edit) && $edit ? $get->display_mic : 0; ?>">
							        <label class="form-check-label" for="floatingDisplayMicrophone">Use Microphone on input?</label>
							    </div>
							</div>

						    <div class="col-md-4">
								  <div class="form-floating mb-3">
								    <input name="mic_speak_lang" type="text" class="form-control" id="floatingMicSpeakLang" placeholder="Microphone Speak Lang (Code)" value="<?php if(isset($edit) && $edit){echo ($get->mic_speak_lang ?? '');} ?>">
								    <label for="floatingMicSpeakLang">Microphone Speak Lang (Code)</label>
								  </div>
								</div>					    				  				  
									  		
					  	</div>
						</fieldset>	

						<fieldset class="border rounded-2 p-3 mb-4">
					  	<legend><h5><span data-feather="message-circle"></span> Chat options</h5></legend>
						  	<div class="row">

								<div class="col-md-3">
								  <div class="form-check form-switch mb-3 custom-switch">
								    <input class="form-check-input" type="checkbox" id="floatingDisplayAvatar" <?php if ((isset($edit) && $edit && ($get->display_avatar ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayAvatar', 'hiddenDisplayAvatar')">
								    <input type="hidden" name="display_avatar" id="hiddenDisplayAvatar" value="<?php echo isset($edit) && $edit ? $get->display_avatar : 1; ?>">
								    <label class="form-check-label" for="floatingDisplayAvatar">Show avatar in chat?</label>
								  </div>
								</div>

								<div class="col-md-3">
								  <div class="form-check form-switch custom-switch mb-3">
								    <input class="form-check-input" type="checkbox" id="floatingDisplayCopyBtn" <?php if ((isset($edit) && $edit && ($get->display_copy_btn ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayCopyBtn', 'hiddenDisplayCopyBtn')">
								    <input type="hidden" name="display_copy_btn" id="hiddenDisplayCopyBtn" value="<?php echo isset($edit) && $edit ? $get->display_copy_btn : 1; ?>">
								    <label class="form-check-label" for="floatingDisplayCopyBtn">Show copy chat button?</label>
								  </div>
								</div>  

								<div class="col-md-3">
								  <div class="form-check form-switch mb-3 custom-switch">
								    <input class="form-check-input" type="checkbox" id="floatingFilterBadwords" <?php if ((isset($edit) && $edit && ($get->filter_badwords ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingFilterBadwords', 'hiddenFilterBadwords')">
								    <input type="hidden" name="filter_badwords" id="hiddenFilterBadwords" value="<?php echo isset($edit) && $edit ? $get->filter_badwords : 1; ?>">
								    <label class="form-check-label" for="floatingFilterBadwords">Filter bad words?</label>
								  </div>
								</div>


								<div class="col-md-3">
								  <div class="form-check form-switch custom-switch mb-3">
								    <input class="form-check-input" type="checkbox" id="floatingChatContactList" <?php if ((isset($edit) && $edit && ($get->display_contacts_user_list ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingChatContactList', 'hiddenChatContactList')">
								    <input type="hidden" name="display_contacts_user_list" id="hiddenChatContactList" value="<?php echo isset($edit) && $edit ? $get->display_contacts_user_list : 1; ?>">
								    <label class="form-check-label" for="floatingChatContactList">Display Contact List</label>
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