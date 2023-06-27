<?php 
$use_bootstrap_icons = true;
// Include necessary files
require_once(__DIR__ . '/inc/includes.php');
define('META_TITLE', $seoConfig['home_meta_title']);
define('META_DESCRIPTION', $seoConfig['home_meta_description']);
require_once(__DIR__ . '/inc/header.php');

// Set the prompts list
$getPrompts = $prompts->getListFront();

// Flag to determine if bootstrap icons should be used
$use_bootstrap_icons = true;
?>

<!-- Start of the Hero Section -->
<section id="hero" class="align-items-center hide-section" style="background: url(<?php echo $base_url."/public_uploads/".$getTheme->image_hero_background; ?>);">
    <!-- Hero Content -->
    <div class="container">
        <div class="row align-items-center">
            <!-- Hero Text -->
            <div class="col-lg-6 col-md-12">
                <h1><?php echo $lang['main_title']; ?></h1>
                <p class="translate-sub-title"><?php echo $lang['sub_title']; ?></p>
                <a href="#ai-team" class="btn btn-primary btn-lg translate-button-header-cta"><?php echo $lang['button_header_cta']; ?></a>
            </div>
            <!-- Hero Image -->
            <div class="col-lg-6 col-md-12 d-flex justify-content-lg-end justify-content-md-center justify-content-sm-center hero-call-action-img">
                <img src="<?php echo $base_url."/public_uploads/".$getTheme->image_hero; ?>" alt="<?php echo $lang['company_name']; ?>" title="<?php echo $lang['company_name']; ?>">
            </div>
        </div>
    </div> 
</section>

<!-- Start of the AI Team Section -->
<section id="ai-team">
    <div class="container section-spacing">
        <div class="row">
            <!-- AI Team Title -->
            <div class="col text-center">
                <h2 class="default-title"><?php echo $lang['body_title_cta']; ?></h2>
            </div>
        </div>

        <div class="row">
            <!-- AI Team Subtitle -->
            <div class="col text-center">
                <p><?php echo $lang['body_sub_title']; ?></p>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Iterating through each Prompt and creating a card for it -->
            <?php foreach ($getPrompts as $showPrompts) : ?>
                <div class="col-lg-3 col-md-4">
                    <div class="card-ai d-grid">
                        <!-- AI Image -->
                        <div class="card-ai-image">
                            <a href="<?php echo $base_url; ?>/chat/<?php echo $showPrompts->slug; ?>">
                                <img src="<?php echo $base_url; ?>/public_uploads/<?php echo $showPrompts->image; ?>" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'" alt="<?php echo $showPrompts->name; ?>" title="<?php echo $showPrompts->name; ?>">
                            </a>
                        </div>
                        <!-- AI Details -->
                        <div class="card-ai-bottom">
                            <!-- AI Name -->
                            <div class="card-ai-name">
                                <h3><?php echo $showPrompts->name; ?></h3>
                            </div>
                            <!-- AI Expertise -->
                            <div class="card-ai-job">
                                <span><?php echo $showPrompts->expert; ?></span>
                            </div>
                            <!-- Start Chat Button -->
                            <a href="<?php echo $base_url; ?>/chat/<?php echo $showPrompts->slug; ?>">
                                <span class="btn btn-primary btn-md start-chat"><i class="bi bi-chat"></i> <?php echo $lang['chat_now']; ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- End of the AI Team Section -->


<?php 
// Clean up the session if necessary
if (isset($_SESSION['buy_credit_id'])) {
    unset($_SESSION['buy_credit_id']);
};

// Include the footer
require_once("inc/footer.php");
?>