<?php 
if (isset($_SESSION['action'])) {
    unset($_SESSION['action']);
}
if (isset($_SESSION['action_message'])) {
    unset($_SESSION['action_message']);
};

if(!isset($no_footer)){?>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <h5 class="h1 text-white"><strong><?php echo $lang['footer_title']; ?></strong></h5>
                    <p><?php echo $lang['footer_resume']; ?></p>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6">
                    <h5><?php echo $lang['footer_title_col1']; ?></h5>
                    <ul class="list-unstyled text-muted">
                        <?php 
                        $getMenu = $menus->getListPosition("Footer Col1");
                        foreach ($getMenu as $showMenu) {?>
                        <li>
                            <a href="<?php echo $showMenu->slug; ?>" <?php echo ($showMenu->target_blank == 1) ? 'target="_blank"' : ''; ?>>
                                <?php echo $showMenu->name; ?>
                            </a>
                        </li>
                        <?php } ?>                        
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5><?php echo $lang['footer_title_col2']; ?></h5>
                    <ul class="list-unstyled text-muted">
                        <?php 
                        $getMenu = $menus->getListPosition("Footer Col2");
                        foreach ($getMenu as $showMenu) {?>
                        <li>
                            <a href="<?php echo $showMenu->slug; ?>" <?php echo ($showMenu->target_blank == 1) ? 'target="_blank"' : ''; ?>>
                                <?php echo $showMenu->name; ?>
                            </a>
                        </li>
                        <?php } ?>                        
                    </ul>
                </div>                
                <div class="col-lg-1 col-md-6 text-end">
                    <img onclick="backToTop();" src="<?php echo $base_url; ?>/img/icon-top.svg" alt="<?php echo $lang['back_to_top']; ?>" title="<?php echo $lang['back_to_top']; ?>">
                </div>
            </div>
        </div>
    </footer>
<?php } ?>


  <!-- JavaScript Libraries -->
  <script src="<?php echo $base_url; ?>/js/jquery-3.6.0.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="<?php echo $base_url; ?>/js/highlight.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/toastr.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/sse.js"></script>
  <script src="<?php echo $base_url; ?>/js/vfs_fonts.js"></script>
  <!-- Main script -->
  <script src="<?php echo $base_url; ?>/js/main.js?v1-8"></script>

  <!-- Conditionally Loaded Scripts -->
  <?php if(isset($use_recaptcha) && $use_recaptcha && $config->use_recaptcha): ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $config->recaptcha_public_key; ?>"></script>
    <script type="text/javascript">
    grecaptcha.ready(function() {
        grecaptcha.execute('<?php echo $config->recaptcha_public_key; ?>', {action: 'submit'}).then(function(token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });        
    </script>
  <?php endif; ?>

    <script type="text/javascript">
        window.addEventListener('load', async () => {
            await fetchLanguageData();
            <?php if(isset($loadAI) && $loadAI): ?>
                fetchLoadData(<?php echo $AI_ID; ?>);
            <?php endif; ?>
        });
    </script>

  <!-- Google Analytics -->
  <?php 
    if(isset($config->use_google_analytics) && $config->use_google_analytics):
      $decoded_code = html_entity_decode($config->google_analytics_code, ENT_QUOTES | ENT_HTML401, 'UTF-8');
      echo $decoded_code;
    endif;
  ?>

  </body>
</html>