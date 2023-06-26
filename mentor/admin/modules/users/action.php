<?php 
$module_name = "users";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
if($config->demo_mode){
    redirect("/admin/{$module_name}", "This option is not available in demo mode.", "error");
    exit();
}  

if(isset($_POST['password']) && $_POST['password']){
    $_POST['password'] = md5($_POST['password'].addslashes($saltnumber));
}


function handleAction($module_name, $action, $id = null) {
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';

    @$_POST['permission'] = json_encode($_POST['permission']);
    switch ($action) {
        case 'add':
            //$module_object->debug(true);
            $result = $module_object->add();
            $message = $result ? 'Record added successfully.' : 'An error occurred while adding a new record. Please try again.';
            break;
        case 'edit':

            $checkUser = $users->get($id);
            /*
            if($checkUser->forbid_delete){
                $permission = is_array($_POST['permission']) ? $_POST['permission'] : json_decode($_POST['permission'], true);
                if (!in_array('users', $permission)) {
                    array_push($permission, 'users');
                }
                $_POST['permission'] = $permission;
            }/*/

            $result = $module_object->update($id);
            $message = $result ? 'Record updated successfully.' : 'An error occurred while updating the record. Please try again.';
            break;

            case 'delete':

            $checkUser = $users->get($id);
            if($checkUser->forbid_delete){
                redirect("/admin/{$module_name}", "This item cannot be deleted", "error");
            }

            $result = $module_object->delete($id);
            $message = $result ? 'Record deleted successfully.' : 'An error occurred while deleting the record. Please try again.';
            break;
    }

    if ($message) {
        $messageType = $result ? 'success' : 'error';
        redirect("/admin/{$module_name}", $message, $messageType);
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($action) {
    handleAction($module_name, $action, $id);
}
?>
