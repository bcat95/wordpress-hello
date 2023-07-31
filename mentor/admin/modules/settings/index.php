<?php
$module_name = "settings";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-gear fs-3'></i> Settings", $module_name, "");
require_once(__DIR__."/../../helpers/message-session.php");
$get = $settings->get(1);
?>

      <div class="modal modal-lg fade" id="modalTestSMTP" tabindex="-1" aria-labelledby="modalTestSMTPLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="modalTestSMTPLabel">Test SMTP sending</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="smtpTestForm">
                <div class="mb-3">
                  <label for="subject" class="form-label">Subject</label>
                  <input type="text" class="form-control" id="subject" name="subject" placeholder="Inform a subject" value="Subject Example - Email Test">
                </div>
                <div class="mb-3">
                  <label for="recipient_name" class="form-label">Recipient's Name</label>
                  <input type="text" class="form-control" id="recipient_name" name="recipient_name" placeholder="Recipient's Name" value="Aigency">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Recipient's Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?php echo isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : ''; ?>">
                </div>
                <div class="mb-3">
                  <label for="content" class="form-label">Content</label>
                  <textarea class="form-control" id="content" name="content" rows="3" placeholder="Enter the content">Hello, this is an email test!</textarea>
                </div>
              </form>

              <div class="alert alert-secondary" role="alert" id="smtp_test_return">
                Click and send e-mail, and then see the response of the request here, check the recipient's email afterwards to verify if the email has arrived.
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-success" id="btn-test-smtp-email" onclick="submitFormTestSMTP()">Send e-mail</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

          </div>

        </div>
      </div>

      <div>
        <form action="/admin/settings/action" method="post" novalidate enctype="multipart/form-data">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-gear"></i> Open AI</h5></legend>
                <div class="row align-middle">

                <?php if($config->demo_mode){?>
                <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?>
                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="openai_api_key" type="text" class="form-control" id="floatingOpenAIApiKey" placeholder="OpenAi Api Key" value="<?php echo $get->openai_api_key; ?>">
                    <label for="floatingOpenAIApiKey">OpenAi Api Key</label>
                  </div>
                </div>                
                <?php } ?>
                
                <a href="https://platform.openai.com/account/api-keys" target="_blank">https://platform.openai.com/account/api-keys</a>
              </div>
            </fieldset>  


            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-stripe"></i> Payment method - Stripe</h5></legend>
                <div class="row align-middle">

                <?php if($config->demo_mode){?>
                <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?>                
                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayStripeActive" <?php if ($get->stripe_payment_active == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayStripeActive', 'hiddenDisplayStripeActive')">
                    <input type="hidden" name="stripe_payment_active" id="hiddenDisplayStripeActive" value="<?php echo $get->stripe_payment_active; ?>">
                    <label class="form-check-label" for="floatingDisplayStripeActive">Use Stripe payment method</label>
                  </div>        
                </div>

                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayStripeTestMode" <?php if ($get->stripe_test_mode == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayStripeTestMode', 'hiddenDisplayStripeTestMode')">
                    <input type="hidden" name="stripe_test_mode" id="hiddenDisplayStripeTestMode" value="<?php echo $get->stripe_test_mode; ?>">
                    <label class="form-check-label" for="floatingDisplayStripeTestMode">Enable stripe test mode</label>
                  </div>        
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="stripe_api_key_test" type="text" class="form-control" id="floatingStripeApiKeyTest" placeholder="Stripe Api Key (Test)" value="<?php echo $get->stripe_api_key_test; ?>">
                    <label for="floatingStripeApiKeyTest">Stripe Api Key (Test)</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="stripe_api_key_production" type="text" class="form-control" id="floatingStripeApiKeyProduction" placeholder="Stripe Api Key (Production)" value="<?php echo $get->stripe_api_key_production; ?>">
                    <label for="floatingStripeApiKeyProduction">Stripe Api Secret Key (Production)</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="stripe_webhook_secret_test" type="text" class="form-control" id="floatingStripeWebhookTest" placeholder="Stripe Webhook Key" value="<?php echo $get->stripe_webhook_secret_test; ?>">
                    <label for="floatingStripeWebhookTest">Stripe Webhook Key (Test)</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="stripe_webhook_secret_production" type="text" class="form-control" id="floatingStripeWebhookProduction" placeholder="Stripe Webhook Key" value="<?php echo $get->stripe_webhook_secret_production; ?>">
                    <label for="floatingStripeWebhookProduction">Stripe Webhook Key (Production)</label>
                  </div>
                </div>
                <?php } ?>
              
              <a href="https://dashboard.stripe.com/apikeys" target="_blank">https://dashboard.stripe.com/apikeys</a>
              <a href="https://dashboard.stripe.com/test/webhooks" target="_blank">https://dashboard.stripe.com/test/webhooks</a>
              <a href="https://stripe.com/docs/testing" target="_blank">https://stripe.com/docs/testing</a>
              </div>
            </fieldset>


            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-bank"></i> Payment method - Bank deposit</h5></legend>
                <div class="row align-middle">


                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayBankDepositeActive" <?php if ($get->bank_deposit_active == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayBankDepositeActive', 'hiddenDisplayBankDepositActive')">
                    <input type="hidden" name="bank_deposit_active" id="hiddenDisplayBankDepositActive" value="<?php echo $get->bank_deposit_active; ?>">
                    <label class="form-check-label" for="floatingDisplayBankDepositeActive">Use bank deposit method</label>
                  </div>        
                </div>

                 <div class="col-md-12">
                  <textarea name="bank_deposit_info" class="form-control text-area-custom-h" id="floatingBankDepositInfo" placeholder="Bank deposit info"><?php echo $get->bank_deposit_info; ?></textarea>
                </div>

      
              </div>
            </fieldset>


            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-shield-check"></i> Google Recaptcha</h5></legend>
                <div class="row align-middle">


                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayUseRecaptcha" <?php if ($get->use_recaptcha == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayUseRecaptcha', 'hiddenDisplayUseRecaptcha')">
                    <input type="hidden" name="use_recaptcha" id="hiddenDisplayUseRecaptcha" value="<?php echo $get->use_recaptcha; ?>">
                    <label class="form-check-label" for="floatingDisplayUseRecaptcha">Use recaptcha</label>
                  </div>        
                </div>

                <?php if($config->demo_mode){?>
                <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?> 
                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="recaptcha_public_key" type="text" class="form-control" id="floatingRecaptchaPublicKey" placeholder="Recaptcha Public Key" value="<?php echo $get->recaptcha_public_key; ?>">
                    <label for="floatingRecaptchaPublicKey">Recaptcha Public Key</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="recaptcha_secret_key" type="text" class="form-control" id="floatingRecaptchaSecretKey" placeholder="Recaptcha Secret Key" value="<?php echo $get->recaptcha_secret_key; ?>">
                    <label for="floatingRecaptchaSecretKey">Recaptcha Secret Key</label>
                  </div>
                </div>
                <?php } ?>
              
              <a href="https://www.google.com/recaptcha/admin/" target="_blank">https://www.google.com/recaptcha/admin/</a>
              </div>
            </fieldset>


            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-bar-chart"></i> Google Analytics</h5></legend>
                <div class="row align-middle">


                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayUseGoogleAnalytics" <?php if ($get->use_google_analytics == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayUseGoogleAnalytics', 'hiddenDisplayUseGoogleAnalytics')">
                    <input type="hidden" name="use_google_analytics" id="hiddenDisplayUseGoogleAnalytics" value="<?php echo $get->use_google_analytics; ?>">
                    <label class="form-check-label" for="floatingDisplayUseGoogleAnalytics">Use Google Analytics</label>
                  </div>        
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <textarea style="height: 150px" name="google_analytics_code" class="form-control" id="floatingGoogleAnalyticsCode" placeholder="Google Analytics Code"><?php echo $get->google_analytics_code; ?></textarea>
                    <label for="floatingGoogleAnalyticsCode">Google Analytics Code</label>
                  </div>
                </div>

             
              </div>
            </fieldset>

           <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-chat"></i> Chat Options</h5></legend>

              <div class="row">

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="chat_font_size" type="text" class="form-control" id="floatingChatFontSize" placeholder="Chat Font Size" value="<?php echo $get->chat_font_size; ?>">
                    <label for="floatingChatFontSize">Chat Font Size</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="max_tokens_gpt" type="number" class="form-control" id="floatingMaxTokensGPT" placeholder="Max tokens GPT Model" value="<?php echo $get->max_tokens_gpt; ?>">
                    <label for="floatingMaxTokensGPT">Max tokens GPT Model</label>
                  </div>
                </div> 

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="max_tokens_davinci" type="number" class="form-control" id="floatingMaxTokensDavinci" placeholder="Max tokens Davinci Model" value="<?php echo $get->max_tokens_davinci; ?>">
                    <label for="floatingMaxTokensDavinci">Max tokens Davinci Model</label>
                  </div>
                </div> 

               </div>

           </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-gift"></i> Creation account credit bonus.</h5></legend>
                <div class="row align-middle">

                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input name="credit_account_bonus" type="number" class="form-control" id="floatingBonusCreditsAccount" placeholder="Creation account credit bonus." value="<?php echo $get->credit_account_bonus; ?>">
                    <label for="floatingBonusCreditsAccount">Creation account credit bonus.</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input name="free_number_chats" type="number" class="form-control" id="floatingFreeNumberChats" placeholder="Number of free chats before login." value="<?php echo $get->free_number_chats; ?>">
                    <label for="floatingFreeNumberChats">Number of free chats before login</label>
                  </div>
                </div>                                  
               
              </div>
            </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-gear"></i> Website Meta Charset.</h5></legend>
                <div class="row align-middle">

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="meta_charset" type="text" class="form-control" id="floatingWebsiteMetaCharSet" placeholder="Meta Charset" value="<?php echo $get->meta_charset; ?>">
                    <label for="floatingWebsiteMetaCharSet">Meta Charset</label>
                  </div>
                </div>                               
               
              </div>
            </fieldset> 

           
           
            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5>DALL·E 2 Configs</h5></legend>
                <div class="row align-middle">

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input name="dalle_generated_img_count" type="number" class="form-control" id="floatingDallEImgCount" placeholder="Number of images" value="<?php echo $get->dalle_generated_img_count; ?>" required>
                      <label for="floatingDallEImgCount">Number of images</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input name="dalle_spend_credits" type="number" class="form-control" id="floatingDallESpent" placeholder="Amount of credits to be spent to generate the image pack" value="<?php echo $get->dalle_spend_credits; ?>" required>
                      <label for="floatingDallESpent">Amount of credits to be spent to generate the image pack</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <select name="dalle_img_size" class="form-control" id="floatingDallEImgSize" required>
                        <?php
                        $dalle_img_size1 = ['256x256', '512x512','1024x1024'];
                        $selectedDalleImgSize = $get->dalle_img_size;

                        foreach ($dalle_img_size1 as $dalle_img_size) {
                            $selected = $dalle_img_size == $selectedDalleImgSize ? 'selected' : '';
                            echo "<option value='$dalle_img_size' $selected>$dalle_img_size</option>";
                        }
                        ?>
                      </select>                 
                      <label for="floatingDallEImgSize">Dall-E Image Size</label>
                    </div>                 
                  </div>

                </div>                                  
               
              </div>
            </fieldset>
         

           <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-chat"></i> Show/hide php errors and notices (just for debug purposes)</h5></legend>

              <div class="row">

                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingShowPHPErrors" <?php if ($get->php_errors == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingShowPHPErrors', 'hiddenPhpErros')">
                    <input type="hidden" name="php_errors" id="hiddenPhpErros" value="<?php echo $get->php_errors; ?>">
                    <label class="form-check-label" for="floatingShowPHPErrors">Show/hide PHP errors</label>
                  </div>
                </div>

               </div>

           </fieldset>          

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-envelope"></i> SMTP Configs</h5></legend>
                <div class="row align-middle">

                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <select name="smtp_auth" class="form-control" id="floatingSMTPAuth" required>
                            <option value="1" <?php if (isset($get->smtp_auth) && $get->smtp_auth == '1') echo 'selected'; ?>>Yes</option>
                            <option value="0" <?php if (isset($get->smtp_auth) && $get->smtp_auth == '0') echo 'selected'; ?>>No</option>
                        </select>                 
                        <label for="floatingSMTPAuth">SMTP Authentication</label>
                    </div>                 
                </div>                   

                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <input name="smtp_charset" type="text" class="form-control" id="floatingSMTPCharset" placeholder="SMTP Charset" value="<?php echo isset($get->smtp_charset) ? $get->smtp_charset : ''; ?>">
                        <label for="floatingSMTPCharset">SMTP Charset</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input name="smtp_port" type="number" class="form-control" id="floatingSMTPPort" placeholder="SMTP Port" value="<?php echo isset($get->smtp_port) ? $get->smtp_port : ''; ?>">
                        <label for="floatingSMTPPort">SMTP Port</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input name="smtp_host" type="text" class="form-control" id="floatingSMTPHost" placeholder="SMTP Host" value="<?php echo isset($get->smtp_host) ? $get->smtp_host : ''; ?>">
                        <label for="floatingSMTPHost">SMTP Host</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input name="smtp_username" type="text" class="form-control" id="floatingSMTPUsername" placeholder="SMTP Username" value="<?php echo isset($get->smtp_username) ? $get->smtp_username : ''; ?>">
                        <label for="floatingSMTPUsername">SMTP Username</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <?php if($config->demo_mode){?>
                    <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the password is not shown in demo mode.</div></div>
                    <?php }else{ ?> 
                    <div class="form-floating mb-3">
                        <input name="smtp_password" type="password" class="form-control" id="floatingSMTPPassword" placeholder="SMTP Password" value="<?php echo isset($get->smtp_password) ? $get->smtp_password : ''; ?>">
                        <label for="floatingSMTPPassword">SMTP Password</label>
                    </div>
                    <?php } ?>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input name="smtp_from" type="email" class="form-control" id="floatingSMTPFrom" placeholder="SMTP From" value="<?php echo isset($get->smtp_from) ? $get->smtp_from : ''; ?>">
                        <label for="floatingSMTPFrom">SMTP From</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input name="smtp_from_name" type="email" class="form-control" id="floatingSMTPFromName" placeholder="SMTP From Name" value="<?php echo isset($get->smtp_from_name) ? $get->smtp_from_name : ''; ?>">
                        <label for="floatingSMTPFromName">SMTP From Name</label>
                    </div>
                </div>

                
                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <select name="smtp_verify_peer" class="form-control" id="floatingSMTPVerifyPeer" required>
                            <option value="1" <?php if (isset($get->smtp_verify_peer) && $get->smtp_verify_peer == '1') echo 'selected'; ?>>True</option>
                            <option value="0" <?php if (isset($get->smtp_verify_peer) && $get->smtp_verify_peer == '0') echo 'selected'; ?>>False</option>
                        </select>
                        <label for="floatingSMTPVerifyPeer">SMTP Verify Peer</label>
                    </div>
                </div>
               
                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <select name="smtp_verify_peer_name" class="form-control" id="floatingSMTPVerifyPeerName" required>
                            <option value="1" <?php if (isset($get->smtp_verify_peer_name) && $get->smtp_verify_peer_name == '1') echo 'selected'; ?>>True</option>
                            <option value="0" <?php if (isset($get->smtp_verify_peer_name) && $get->smtp_verify_peer_name == '0') echo 'selected'; ?>>False</option>
                        </select>
                        <label for="floatingSMTPVerifyPeerName">SMTP Verify Peer Name</label>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <select name="smtp_allow_self_signed" class="form-control" id="floatingSMTPAllowSelfSigned" required>
                            <option value="1" <?php if (isset($get->smtp_allow_self_signed) && $get->smtp_allow_self_signed == '1') echo 'selected'; ?>>True</option>
                            <option value="0" <?php if (isset($get->smtp_allow_self_signed) && $get->smtp_allow_self_signed == '0') echo 'selected'; ?>>False</option>
                        </select>
                        <label for="floatingSMTPAllowSelfSigned">SMTP Allow Self-Signed</label>
                    </div>
                </div>   

                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <select name="smtp_secure" class="form-control" id="floatingSMTPSecure" required>
                            <option value="*" <?php if (isset($get->smtp_secure) && $get->smtp_secure == '') echo 'selected'; ?>>None</option>
                            <option value="tls" <?php if (isset($get->smtp_secure) && $get->smtp_secure == 'tls') echo 'selected'; ?>>TLS</option>
                            <option value="ssl" <?php if (isset($get->smtp_secure) && $get->smtp_secure == 'ssl') echo 'selected'; ?>>SSL</option>
                        </select>
                        <label for="floatingSMTPSecure">SMTPSecure</label>
                    </div>
                </div>                

                <div class="col-12">
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTestSMTP">Test sending email</button>
                </div>

              </div>
            </fieldset>

           <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-envelope"></i> Email Template - Password recovery code</h5></legend>

              <div class="col-md-12">
                  <div class="form-floating mb-3">
                      <input name="recovery_code_subject" type="text" class="form-control" id="floatingSubjectRecoveryPassword" placeholder="Subject" value="<?php echo isset($get->recovery_code_subject) ? $get->recovery_code_subject : ''; ?>">
                      <label for="floatingSubjectRecoveryPassword">Subject</label>
                  </div>
              </div>

              <p>Use the <b>{{code}}</b> tag in the template to display the code.</p>
              <div class="form-group">
                <textarea id="editor" name="email_template_recovery_code"><?php echo isset($get->email_template_recovery_code) ? $get->email_template_recovery_code : ''; ?></textarea>
              </div>
           </fieldset>                             
                       

          <div class="d-grid">
            <button class="btn btn-success text-uppercase fw-bold mb-2 submit-button" type="submit">Save</button>
          </div>

         <input type="hidden" name="id" value="1">
         <input type="hidden" name="action" value="edit">
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