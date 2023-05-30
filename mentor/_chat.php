<?php 
// Initializing variables and requiring necessary files
$no_footer = true;
$header_min = true;
$loadAI = true;
$use_bootstrap_icons = true;
$mobile_bg = true;
require_once("inc/includes.php");


// Redirect to 404 page if the slug is not set or if the AI does not exist
if (!isset($_REQUEST['slug']) || !($AI = $prompts->getBySlug($_REQUEST['slug']))) {
    header("location:/404");
    exit();
}

// Check user status if logged in
if($isLogged && !$getCustomer->status){
    redirect($base_url.'/panel', $lang['customer_disable_message'], 'error');
}

// Fetch necessary AI details
$AI_ID = $AI->id;
$getListAI = $prompts->getListFront();
$getPromptsOutput = $prompts_output->getListFront();
$getPromptsOutputCount = $getPromptsOutput->rowCount();
$getCategories = $prompts_categories->getListByIdPrompt($AI->id);

// Fetch AI tone details
$getPromptsTone = $prompts_tone->getListFront();
$getPromptsToneCount = $getPromptsTone->rowCount();

// Fetch AI writing details
$getPromptsWriting = $prompts_writing->getListFront();
$getPromptsWritingCount = $getPromptsWriting->rowCount();

// Handling chat request
if (isset($_GET['chat'])) {
    $getTargetThread = $_GET['chat'];
    $checkAIThread = $messages->getByThread($getTargetThread)->Fetch();
  
    if(isset($checkAIThread->id) && $checkAIThread->id) {
        if (!($checkAIThread->id_prompt == $AI->id && $checkAIThread->id_customer == @$_SESSION['id_customer'])) {
            header("location:".$base_url."/");
            exit();
        }
    } else {
        header("location:".$base_url."/");
        exit();
    }
  
    $_SESSION['threads'][$AI->id] = $getTargetThread;
}
define('META_TITLE', $seoConfig['chat_meta_title']." ".$AI->name);
define('META_DESCRIPTION', $seoConfig['chat_meta_description']." ".$AI->name);
// Include chat session script
require_once("inc/header.php");
require_once("modules/customer/chat-session.php");
?>

  <?php if($AI->display_description){?>
  <div class="modal fade" tabindex="-1" id="modalDefault">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?php echo $AI->name; ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $lang['button_close_modal']; ?>"></button>
        </div>
        <div class="modal-body">
          <?php echo $AI->description; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $lang['button_close_modal']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php if(!$isLogged){?>
  <div class="modal fade" tabindex="-1" id="modalDemo">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?php echo $lang['create_account_to_continue_title']; ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $lang['button_close_modal']; ?>"></button>
        </div>
        <div class="modal-body">
          <?php echo $lang['create_account_to_continue_text']; ?>
        </div>
        <div class="modal-footer">
          <a href="<?php echo $base_url; ?>/sign-up" class="btn btn-success"><?php echo $lang['sign_up']; ?></a>
        </div>
      </div>
    </div>
  </div>  
  <?php } ?>

  <?php
  if (isset($_SESSION['action']) && !empty($_SESSION['action'])) {
    if ($_SESSION['action'] === 'success') {
      echo '<div class="container pt-lg-3"><div class="row"><div class="alert alert-success"><i class="bi bi-check2-circle"></i> ' . $_SESSION['action_message'] . '</div></div></div>';
    } else {
      echo '<div class="container pt-lg-3"><div class="row"><div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div></div></div>';
    }
  }
  ?>

  <section id="chat-background">
    <div class="container pt-lg-2 pb-lg-4">
      <div class="row chat-background">

        <?php if($AI->display_contacts_user_list){?>
        <div class="col-lg-3 col-md-3 col-sm12 p-0 col-contacts-border" style="display:none;">
         
         <div class="ai-contacts-top">
           <strong><?php echo $lang['chat_call_action1']; ?></strong>
           <span><?php echo $lang['chat_call_action2']; ?></span>
         </div>

         <div class="ai-contacts-scroll">
            <?php 
            $itemCount = 0;
            $getListAI = reorderArrayById($getListAI, $AI->id);
            foreach ($getListAI as $showListAI) {?>
              <a href="<?php echo $showListAI->slug; ?>">
                <div class="ai-contacts-item <?php if($itemCount == 0) echo 'ai-contacts-item-active';?>">
                  <div class="ai-contacts-image"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $showListAI->image;?>" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'" alt="<?php echo $showListAI->name;?>" title="<?php echo $showListAI->name;?>"></div>
                  <div class="ai-contacts-info">
                    <div class="ai-contacts-name"><?php echo $showListAI->name;?></div>
                    <div class="ai-contacts-job" alt="<?php echo $showListAI->expert;?>"><?php echo $showListAI->expert;?></div>
                  </div>
                </div>
              </a>
            <?php  $itemCount++; } ?>
         </div>

        </div>
        <?php } ?>

        <div class="col p-0 col-main-chat">
         
         <div class="ai-chat-top">
          <div class="row align-items-center">
            <div class="col-md-7 col-lg-8 col-7">
              <div class="wrapper-ai-chat-top">
                <div class="ai-chat-top-image"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $AI->image;?>" alt="image" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'"></div>
                <div class="ai-chat-top-info">
                  <div class="ai-chat-top-name"><h4><?php echo $AI->name;?> <span class="online-bullet"></span></h4></div>
                  <div class="ai-chat-top-job"><?php echo $AI->expert;?></div>
                    <div class="ai-categories">
                    <?php 
                    foreach ($getCategories as $showCategories) {
                      $categoriesName = $categories->get($showCategories->id_category);
                      echo "<a class='badge bg-dark badge-categories' href='$base_url/ai-team/$categoriesName->slug'>$categoriesName->name</a>";
                    }
                    ?>                      
                    </div>                  
                </div>
              </div>
            </div>
            <div class="col-md-5 col-lg-4 col-5">

              <div class="icons-options">
                <div class="dropdown-center">
                  <?php if($AI->display_contacts_user_list){?>
                  <img class="toggle_employees_list" src="<?php echo $base_url; ?>/img/icon-user-list.svg" alt="<?php echo $lang['btn_employees_list']; ?>" title="<?php echo $lang['btn_employees_list']; ?>">
                  <?php } ?>
                  <?php if($AI->display_description){?>
                  <img class="about_modal" src="<?php echo $base_url; ?>/img/icon-about.svg" alt="<?php echo $lang['btn_about']; ?>" title="<?php echo $lang['btn_about']; ?>" data-bs-toggle="modal" data-bs-target="#modalDefault">
                  <?php } ?>
                  <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?php echo $base_url; ?>/img/icon-config.svg" alt="<?php echo $lang['btn_config']; ?>" title="<?php echo $lang['btn_config']; ?>">
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li id="new-chat"><a class="dropdown-item" href="<?php echo $base_url; ?>/new-chat/<?php echo $AI->slug; ?>"><span><i class="bi bi-plus-circle"></i> <?php echo $lang['button_new_chat']; ?></span></a></li>
                    <li id="close-chat" class="d-block d-lg-none"><a class="dropdown-item" href="<?php echo $base_url;?>/ai-team"><i class="bi bi-x-lg"></i><?php echo $lang['button_close']; ?></span></a></li>
                    <li><a class="dropdown-item download-txt" href="<?php echo $base_url."/download-chat/".$AI->slug."/".@$_SESSION['threads'][$AI->id]; ?>?format=txt"><span><i class="bi bi-filetype-txt"></i> <?php echo $lang['button_download_chat']; ?></span></a></li>
                    <li>  <a class="dropdown-item download-pdf" href="<?php echo $base_url."/download-chat/".$AI->slug."/".@$_SESSION['threads'][$AI->id]; ?>?format=pdf"><span><i class="bi bi-filetype-pdf"></i> <?php echo $lang['button_download_chat_pdf']; ?></span></a></li>
                    <li>  <a class="dropdown-item download-pdf" href="<?php echo $base_url."/download-chat/".$AI->slug."/".@$_SESSION['threads'][$AI->id]; ?>?format=docx"><span><i class="bi bi-filetype-docx"></i> <?php echo $lang['button_download_chat_docx']; ?></span></a></li>
                  </ul>
                </div>

              </div>
            </div>
          </div>
         </div>

        <div class="ia-chat-content">
          <div class="row">
            <div class="cell">
              <div class="chat-frame">
                  <?php
                  function displayMessage($role, $name, $content, $datetime, $image = null, $display_avatar = false, $use_google_voice = false, $extra_class = '') {
                  global $lang;
                  ?>
                      <div class="conversation-thread <?php echo $role == 'assistant' ? 'thread-ai' : 'thread-user'; ?> <?php echo $extra_class; ?>">
                          <?php if ($display_avatar && $role == 'assistant') { ?>
                              <div class="user-image"><img onerror="this.src='<?php echo @$base_url;?>/img/no-image.svg'" src="<?php echo @$base_url;?>/public_uploads/<?php echo $image; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>"></div>
                          <?php } ?>
                          <div class="message-container">
                              <div class="message-info">
                                  <button class="copy-text" onclick="copyText(this)"><img src="<?php echo @$base_url;?>/img/copy.svg"> <span class="label-copy-code"><?php echo $lang['copy_text1']; ?></span></button>
                                  <?php if ($use_google_voice) { ?>
                                      <div class="chat-audio"><img data-play="false" src="<?php echo @$base_url;?>/img/btn_tts_play.svg"></div>
                                  <?php } ?>
                                  <div class="user-name"><h5><?php echo $name; ?></h5></div>
                                  <div class="message-text"><div class="chat-response"><?php echo $content; ?></div></div>
                                  <div class="date-chat"><img src="<?php echo @$base_url;?>/img/icon-clock.svg"> <?php echo $datetime; ?></div>
                              </div>
                          </div>
                      </div>
                      <?php
                  }
                  ?>

                  <div id="overflow-chat">

                      <?php if (empty($_SESSION['history'][$AI_ID])) : ?>
                        
                          <?php displayMessage('assistant', $AI->name, $AI->welcome_message, date("d/m/Y, H:i:s"), $AI->image, $AI->display_avatar, $AI->use_google_voice); ?>

                      <?php else : ?>

                      <?php
                          $counter = 0;
                          foreach ($_SESSION['history'][$AI_ID] as $message) :
                            if ($message['role'] != "system") {
                              @$name = $message['role'] == 'assistant' ? $message['name'] : 'You';
                              @$content = $message['role'] == 'assistant' ? $message['content'] : removeCustomInput($message['content']);
                              @$extra_class = $counter > 1 ? 'conversation-thread-flow' : '';
                              displayMessage($message['role'], $AI->name, $content, $message['datetime'], $AI->image, $AI->display_avatar, $AI->use_google_voice, $extra_class);
                            }
                            $counter++;
                          
                          endforeach;
                      ?>

                      <?php endif; ?>

                  </div>
              </div>

              <div class="message-area-bottom">

                <!--start-widget--options--input-->
                <div class="col col-options-input">
                <div class="btn-options-input"><div class="arrow-up"></div></div>

                  <?php if($getPromptsOutputCount > 0){?>
                  <div class="form-floating form-f-chat" id="display_chat_language_output">
                    <select class="form-select" id="selectLanguage">
                      <option value=""><?php echo $lang['label_default']; ?></option>
                      <?php foreach ($getPromptsOutput as $show_prompts_output) {?>
                      <option value="<?php echo $show_prompts_output->value; ?>"><?php echo $show_prompts_output->name; ?></option>
                      <?php } ?>
                    </select> 
                    <label for="selectLanguage"><?php echo $lang['label_display_chat_language_output']; ?></label>
                  </div>
                  <?php } ?>

                  <?php if($getPromptsToneCount > 0){?>
                  <div class="form-floating form-f-chat" id="display_chat_tone">
                    <div class="form-floating form-f-chat">
                      <select class="form-select" id="selectTone">
                      <option value=""><?php echo $lang['label_default']; ?></option>
                      <?php foreach ($getPromptsTone as $show_prompts_tone) {?>
                      <option value="<?php echo $show_prompts_tone->value; ?>"><?php echo $show_prompts_tone->name; ?></option>
                      <?php } ?>                      
                      </select>
                      <label for="selectTone"><?php echo $lang['label_display_chat_tone']; ?></label>
                    </div>
                  </div>
                  <?php } ?>

                  <?php if($getPromptsWritingCount > 0){?>
                  <div class="form-floating form-f-chat" id="display_chat_writing_style">
                    <select class="form-select" id="selectWritingStyle">
                      <option value=""><?php echo $lang['label_default']; ?></option>
                      <?php foreach ($getPromptsWriting as $show_prompts_writing) {?>
                      <option value="<?php echo $show_prompts_writing->value; ?>"><?php echo $show_prompts_writing->name; ?></option>
                      <?php } ?>                      
                    </select>
                    <label for="selectWritingStyle"><?php echo $lang['label_display_chat_writing_style']; ?></label>
                  </div>                  
                  <?php } ?>
                </div>                
                <!--end-widget--options--input-->
              

              <div class="chat-input">
                <?php 
                if(!$isLogged && $_SESSION['message_count'] > $config->free_number_chats){?>
                  <div class="col-12">
                    <div class="alert alert-warning">
                      <h5><?php echo $lang['create_account_to_continue_title']; ?></h5>
                      <p><?php echo $lang['create_account_to_continue_text']; ?></p>
                      <div class="d-flex">
                        <a class="nav-link btn btn-sign-up" href="<?php echo $base_url; ?>/sign-up"><i class="bi bi-box-arrow-in-right fs-5"></i> <?php echo $lang['sign_up']; ?></a>
                        <a class="nav-link btn btn-sign-in ms-3" href="<?php echo $base_url; ?>/sign-in"><i class="bi bi-person-circle fs-5"></i> <?php echo $lang['sign_in']; ?></a>
                      </div>
                    </div>
                  </div>
                <?php }else {?>

                <span class="character-typing">
                  <div><b class='wait'><?php echo $lang['wait']; ?></b> <span></span>  <b class='is_typing'><?php echo $lang['is_typing']; ?></b></div>
                </span>
                <textarea name="chat" id="chat" placeholder="<?php echo $lang['input_placeholder']; ?>" maxlength="200"></textarea>
                <?php if($AI->display_mic){?>
                <img src="<?php echo $base_url; ?>/img/mic-start.svg" id="microphone-button">
                <?php } ?>
                <button class="submit btn-send-chat btn btn-primary" tabindex="0"><span><?php echo $lang['button_send']; ?></span> <img src="<?php echo $base_url; ?>/img/icon-send.svg"></button>
                <button class="submit btn-cancel-chat btn btn-primary" tabindex="0" style="display:none"><img src="<?php echo $base_url; ?>/img/btn_stop.svg"> <span class="stop-chat-label"><?php echo $lang['button_cancel']; ?></span></button>
                <?php } ?>
              </div>

              </div>              

            </div>
          </div>          
        </div>         

        </div>
      </div>
    </div> 
  </section>

  <style type="text/css">
    .chat-response{
      font-size: <?php echo $config->chat_font_size; ?>;
    }
  </style>

<?php 
require_once("inc/footer.php");
?>