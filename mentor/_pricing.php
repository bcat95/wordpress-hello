<?php 
$header_min = true;
$loadAI = false;
$use_bootstrap_icons = true;
require_once("inc/includes.php");
define('META_TITLE', $seoConfig['pricing_meta_title']);
define('META_DESCRIPTION', $seoConfig['pricing_meta_description']);
require_once("inc/header.php");
$getCreditsPacks = $credits_packs->getListFront();
$getMenuName = $menus->get(3);
?>

<section id="inner-page">
  <div class="container">
    <div class="row">
      <div class="col"><h1><?php echo $getMenuName->name; ?></h1></div>
    </div>
  </div>  
</section>

<section class="pricing py-5">
  <div class="container">

    <div class="row">
      <div class="col text-center py-4">
        <h2 class="default-title"><?php echo $lang['price_page_title']; ?></h2>
      </div>
    </div>    

    <?php
      if (isset($_SESSION['action']) && !empty($_SESSION['action'])) {
        echo '<div class="row text-center"><div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div></div>';
      }

      $stripe_active = $config->stripe_payment_active;
      $bank_deposit_active = $config->bank_deposit_active;
      $single_payment_method = ($stripe_active xor $bank_deposit_active);
    ?>


    <div class="row">
      <?php foreach ($getCreditsPacks as $showCreditsPack) { ?>
        <div class="col-lg-4 mb-5 ">
          <div class="card mb-5 mb-lg-0 h-100">
            <div class="card-body d-flex flex-column">
              <div class="card-price-thumb"><img src="<?php echo $base_url."/public_uploads/".$showCreditsPack->image; ?>"  onerror="this.src='<?php echo $base_url; ?>/img/coin-placeholder.png'"></div>
              <h5 class="card-title text-muted text-uppercase text-center"><?php echo $showCreditsPack->name; ?></h5>
              <h6 class="card-price text-center"><?php echo $showCreditsPack->price; ?></h6>
              <hr>
              <ul>
                <?php
                $desc = json_decode($showCreditsPack->description); 
                  foreach ($desc as $showDescription) {
                    echo "<li>".$showDescription."</li>";
                  }
                ?>
              </ul>
              <div class="d-grid mt-auto">
                <button data-id="<?php echo $showCreditsPack->id; ?>" data-href="<?php echo $base_url.'/recharge-credits'; ?>" class="btn btn-primary text-uppercase purchase-btn" <?php if ($single_payment_method) echo 'data-single-payment-method="true"'; ?>><?php echo $lang['price_page_btn_purchase']; ?></button>
                <div class="payment-options d-none">
                    <?php if ($stripe_active) { ?>
                        <button class="btn btn-primary stripe-btn"><i class="bi bi-stripe"></i> <?php echo $lang['price_page_pay_stripe']; ?></button>
                    <?php } ?>
                    <?php if ($bank_deposit_active) { ?>
                        <button class="btn btn-secondary bank-deposit-btn"><i class="bi bi-bank"></i> <?php echo $lang['price_page_pay_bank_deposit']; ?></button>
                    <?php } ?>
                    <button type="button" class="close-payment-options"><?php echo $lang['close_payment_method']; ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>


<?php
require_once("inc/footer.php");
?>