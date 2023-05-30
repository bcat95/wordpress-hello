<?php
$module_name = "prompts";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-cpu fs-3'></i> Prompts", $module_name, "Add new AI");
$get = $prompts->getList();
require_once(__DIR__."/../../helpers/message-session.php");
?>

      <div class="table-responsive">
        <table class="table table-striped border data-table align-middle">
          <thead>
            <tr>
              <th scope="col" style="width: 1%;"></th>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Expert</th>
              <th scope="col">Url</th>
              <th scope="col">Temperature</th>
              <th scope="col">Status</th>
              <th scope="col">Model</th>
              <th scope="col" class="text-end">Action</th>
            </tr>
          </thead>
          <tbody id="sortableTableBody" data-module="<?php echo $module_name; ?>">
            <?php foreach ($get as $show) {?>
            <tr data-id="<?php echo $show->id; ?>">
              <td class="handle" style="cursor: move;">&#9776;</td>
              <td><div class="wrapper-form-img"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $show->image; ?>" onerror="this.src='<?php echo $base_url; ?>/admin/img/placeholder.jpg'"></div></td>
              <td><?php echo $show->name; ?></td>
              <td><?php echo $show->expert; ?></td>
              <td><a href="<?php echo $base_url."/chat/".$show->slug; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-up-right"></i> View chat</a></td>
              <td><?php echo $show->temperature; ?></td>
              <td>
                <div class="form-check form-switch form-switch-lg">
                  <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch-<?php echo $show->id; ?>" <?php echo ($show->status == 1) ? 'checked' : ''; ?>>
                </div>
              </td>              
              <td><?php echo $show->API_MODEL; ?></td>
              <td class="text-end">
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-module="<?php echo $module_name; ?>"  data-id="<?php echo $show->id; ?>"><i class="bi bi-trash-fill fs-6"></i></button>
                <a href="<?php echo $base_url."/admin/".$module_name."/edit/".$show->id; ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square fs-6"></i></a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      </div>    

<?php
require_once("../../inc/footer.php");
?>

