    </main>
    
  </div>
</div>

<?php 
  if (isset($_SESSION['action']) && isset($_SESSION['action_message'])) {
    unset($_SESSION['action']);
    unset($_SESSION['action_message']);
  }
?>
  <script src="<?php echo $base_url; ?>/admin/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="<?php echo $base_url; ?>/admin/js/jquery-3.6.0.min.js"></script>
  <script src="<?php echo $base_url; ?>/admin/js/app.js?v1-2"></script> 
  <script src="<?php echo $base_url; ?>/admin/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo $base_url; ?>/admin/js/dataTables.bootstrap5.min.js"></script>
  <script src="<?php echo $base_url; ?>/admin/js/trumbowyg.min.js"></script>
  <script src="<?php echo $base_url; ?>/admin/js/toastr.min.js"></script>
  <?php if(isset($use_sortable) && $use_sortable){?>
  <script src="<?php echo $base_url; ?>/admin/js/sortable.min.js"></script>
  <?php } ?>
  
  <?php if(isset($use_select2) && $use_select2){?>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
  <script type="text/javascript">
    $("#multiple-select-field").select2({
    theme: "bootstrap-5",
    containerCssClass: "select2--medium", 
    selectionCssClass: "select2--large",
    dropdownCssClass: "select2--medium",
    });    
  </script>
  <?php } ?>

  <script type="text/javascript">
  $(document).ready(function() {
    $('.data-table').DataTable({
        pageLength: 50,
        columnDefs: [
            { orderable: false, targets: 3 }
        ]
    });
  });     
  $('#editor').trumbowyg();
  </script>

  </body>
</html>