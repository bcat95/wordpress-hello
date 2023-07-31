<!doctype html>
<html lang="<?php echo $getDefaultLanguage->lang; ?>" dir="<?php echo $dir; ?>">
  <head>
    <meta charset="<?php echo $config->meta_charset; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo defined('META_TITLE') ? META_TITLE : 'Aigency'; ?></title>
    <meta name="description" content="<?php echo defined('META_DESCRIPTION') ? META_DESCRIPTION : 'Aigency'; ?>">
    <?php if (!is_null($getSeo->image_thumb)) { ?>
    <meta property="og:image" content="<?php echo $base_url."/public_uploads/".$getSeo->image_thumb; ?>" />
    <?php }?>
    <meta name="theme-color" content="<?php echo $seoConfig['theme_color']; ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="<?php echo $bootstrapCSS; ?>" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/style/app.css?v=230626" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/style/dark-mode.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/style/highlight.min.css" />    
    <link rel="stylesheet" href="<?php echo $base_url; ?>/style/highlight.dark.min.css" />
    <link rel="stylesheet" href="<?php echo $base_url; ?>/style/toastr.min.css" />
    <?php if(isset($use_bootstrap_icons) && $use_bootstrap_icons){?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <?php } ?>
    <link itemprop="url" href="<?php echo $base_url; ?>/img/thumb.jpg">
    <link itemprop="thumbnailUrl" href="<?php echo $base_url; ?>/img/thumb.jpg">
    <meta name="theme-color" content="#1995f0">
    <meta property="og:locale"          content="vi_VN" />
    <meta property="og:type"            content="website" />
    <meta property="og:title"           content="Cố vấn AI bởi ChatGPT" />
    <meta property="og:description"     content="Giải pháp thông minh cho nhu cầu thực tế của bạn. Với chuyên môn trong các lĩnh vực như thiết kế, viết lách, mạng xã hội và nhiều hơn nữa, đội ngũ của chúng tôi có thể cung cấp cho bạn các giải pháp thông minh mang lại kết quả thực tế." />
    <meta property="og:url"             content="https://mentor.chatgptvietnam.org" />
    <meta property="og:site_name"       content="Cố vấn AI bởi ChatGPT">
    <meta property="fb:app_id"          content="143893371929977" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $base_url; ?>/fav/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $base_url; ?>/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $base_url; ?>/fav/favicon-16x16.png">
    <link rel="manifest" href="<?php echo $base_url; ?>/fav/site.webmanifest">
    <link rel="mask-icon" href="<?php echo $base_url; ?>/fav/safari-pinned-tab.svg" color="#5bbad5"> 
    <style type="text/css">
      header{
      background: linear-gradient(180deg, <?php echo $theme_skin['header_background_color1']; ?> 0%, 
      <?php echo $theme_skin['header_background_color2']; ?> 100%);
      border-bottom: 1px solid <?php echo $theme_skin['header_border_bottom']; ?>;
      }
      #hero .btn{
      background: linear-gradient(180deg, <?php echo $theme_skin['hero_button_background_color1']; ?> 0%, 
      <?php echo $theme_skin['hero_button_background_color2']; ?> 100%);
      }      
      #hero .btn:hover{
        box-shadow: 0px 1px 19px <?php echo $theme_skin['hero_button_background_color_hover']; ?>;
      }
      #hero h1, #hero p{
        color: <?php echo $theme_skin['hero_home_text_color']; ?>;
      }
      footer{
      background: linear-gradient(180deg, <?php echo $theme_skin['footer_background_color1']; ?> 0%, 
      <?php echo $theme_skin['footer_background_color2']; ?> 100%);
      }   
      footer ul li, footer ul li a, footer p{
        color: <?php echo $theme_skin['footer_text_color']; ?>;
      }   
      footer ul li a:hover{
        color: <?php echo $theme_skin['footer_text_color_hover']; ?>;
      }
      .btn-sign-up{
        background: <?php echo $theme_skin['btn_sign_up_background_color']; ?>;
        color: <?php echo $theme_skin['btn_sign_up_text_color']; ?>;
        border: 1px solid <?php echo $theme_skin['btn_sign_up_border_color']; ?>;
      }      
      .btn-sign-up:hover, .btn-sign-up:active, .btn-sign-up:focus{
        background: <?php echo $theme_skin['btn_sign_up_background_color_hover']; ?>;
        color: <?php echo $theme_skin['btn_sign_up_text_color_hover']; ?>;
        border: 1px solid  <?php echo $theme_skin['btn_sign_up_border_color_hover']; ?>;
      }
      .btn-sign-in{
        background: <?php echo $theme_skin['btn_sign_in_background_color']; ?>;
        color: <?php echo $theme_skin['btn_sign_in_text_color']; ?>;
        border: 1px solid <?php echo $theme_skin['btn_sign_in_border_color']; ?>;
      }      
      .btn-sign-in:hover, .btn-sign-in:active, .btn-sign-in:focus{
        background: <?php echo $theme_skin['btn_sign_in_background_color_hover']; ?>;
        color: <?php echo $theme_skin['btn_sign_in_text_color_hover']; ?>;
        border: 1px solid  <?php echo $theme_skin['btn_sign_in_border_color_hover']; ?>;
      }      
      .primary-menu li a{
        color: <?php echo $theme_skin['header_menu_links_color']; ?> !important;
      }
      .primary-menu li a:hover{
        color: <?php echo $theme_skin['header_menu_links_color_hover']; ?> !important;
      }
      .primary-menu li a.nav-link-effect::before {
        background-color: <?php echo $theme_skin['header_menu_links_color_effect_hover']; ?>;
      }
      .navbar-expand-lg .navbar-nav .dropdown-menu {
        background: <?php echo $theme_skin['header_menu_links_dropdown_background']; ?>;
        border: 1px solid <?php echo $theme_skin['header_menu_links_dropdown_border']; ?>;
      }
      .navbar-expand-lg .navbar-nav .dropdown-menu a{
        color: <?php echo $theme_skin['header_menu_links_dropdown_color']; ?>;
      }
      #inner-page{
        background: <?php echo $theme_skin['header_inner_page_background_color']; ?>;
      }
      #inner-page h3, #inner-page h1{
        color: <?php echo $theme_skin['header_inner_page_text_color']; ?>;
      }.offcanvas-custom {
        background: <?php echo $theme_skin['mobile_background_color']; ?>;
      }
      header .btn-close span, header .bi-list{
        color: <?php echo $theme_skin['mobile_btn_close_color']; ?>;
      }
    </style>
    <link href="/style/bcat.css?v=230626" rel="stylesheet">
    <script defer src="/app-load.js?v=230626"></script>
    <?php if($dir == "rtl"){?>
      <style type="text/css">
      .offcanvas-custom.show{
        visibility: visible;
      }
      @media (min-width: 992px){
        .offcanvas-custom{
          position: inherit;
          display: block;
          bottom: inherit;
          left: inherit;
          visibility: visible;
          z-index: inherit;
          max-width: inherit;
          transform: inherit;     
          width: auto;   
          background: transparent;
          border: 0;
        }
      }        
      </style>
    <?php } ?>
  </head>
  <body <?php if(isset($mobile_bg) && $mobile_bg) echo 'class="mobile-body"'; if(isset($bg_white) && $bg_white) echo 'class="bg-white"'; ?>>

  <div id="loading" style="display:none !important">
      <div class="spinner-border text-light spinner" role="status"></div>  
  </div>

  <?php if(!@$no_header){?>
<header <?php if(isset($header_min)) echo "class='header-min'";?>>
  <p class="mb-0 text-center">Hôm nay 28.07 sẽ có bản cập nhật tối ưu hệ thống</p>
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-3 col-md-3 col-6 d-flex justify-content-md-start">
        <a href="<?php echo $base_url; ?>/"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $getTheme->image_logo; ?>" alt="<?php echo $lang['company_name']; ?>" title="<?php echo $lang['company_name']; ?>" id="logo"></a>
      </div>
      <div class="col-lg-9 col-md-9 col-6 d-flex justify-content-end">

        <nav class="navbar navbar-expand-lg navbar-light nav-mobile">
          <button class="navbar-toggler ms-auto custom-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas">
            <i class="bi bi-list fs-1"></i>
          </button>
          <div class="offcanvas offcanvas-custom offcanvas-end" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
            <div class="d-lg-none d-md-none">
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="offcanvas-body">
              <ul class="navbar-nav primary-menu">
                <?php 
                $getMenuHeader = $menus->getListPosition("Header");
                foreach ($getMenuHeader as $showMenuHeader) {?>
                <li class="nav-item"><a class="nav-link nav-link-effect" href="<?php echo $showMenuHeader->slug; ?>"><?php echo $showMenuHeader->name; ?></a></li>
                <?php } ?>
                <?php if(!$isLogged){?>
                <li class="nav-item">
                  <a class="nav-link btn btn-sign-up" href="<?php echo $base_url; ?>/sign-up"><i class="bi bi-box-arrow-in-right fs-5"></i> <?php echo $lang['sign_up']; ?></a>
                </li>                                
                <li class="nav-item">
                  <a class="nav-link btn btn-sign-in" href="<?php echo $base_url; ?>/sign-in"><i class="bi bi-person-circle fs-5"></i> <?php echo $lang['sign_in']; ?></a>
                </li>
                <?php } else{ ?>
                <li class="nav-item">
                  <a class="nav-link btn btn-sign-in" href="<?php echo $base_url; ?>/panel"><i class="bi bi-person-circle fs-5"></i> <?php echo $lang['my_panel']; ?></a>
                </li>
                <a class="text-decoration-none" href="<?php echo $base_url; ?>/panel"><span class="my-credits"><?php echo $lang['my_credits']; ?>: <?php echo number_format($userCredits, 0, '.', ','); ?></span></a>
                <?php } ?>
              </ul>
            </div>
          </div>
        </nav>
        <div class="theme-icon" id="toggle-button">
          <i class="bi bi-sun fs-4" id="theme-icon"></i>
        </div>
      </div>
    </div>
  </div>
</header>
  <?php } ?>