<?php 
$module_name = "prompts";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");

function handleCategories($id) {
    $prompts_categories = new PromptsCategories();
    $prompts_categories->deletePromptCategory($id);
    if (isset($_POST['categories']) && is_array($_POST['categories'])) {
        foreach ($_POST['categories'] as $category) {
            $prompts_categories->addPromptCategory($id, $category);
        }
    }
}

function handleAction($module_name, $action, $id = null) {
    global $config;
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';

    if($config->demo_mode){
        redirect("/admin/{$module_name}", "This option is not available in demo mode.", "error");
        exit();
    }

    $checkSlug = $module_object->getBySlug($_POST['slug']);

    // Se o slug do formulário já existe no banco de dados
    if(is_object($checkSlug) && isset($checkSlug->slug)){
        // Se o slug pertence a um registro diferente do que estamos tentando atualizar
        if ($checkSlug->id !== $_POST['id']) {
            $_POST['slug'] = $checkSlug->slug . "-";
        }
    }


    switch ($action) {
        case 'add':
            $_POST['item_order'] = 0;
            $getMaxOrder = $module_object->getMaxOrder();
            if(isset($getMaxOrder->max_order) && $getMaxOrder->max_order > 0){
                $_POST['item_order'] = (int) $getMaxOrder->max_order + 1;
            }

            $result = $module_object->add();
            if($result){
                $lastInsertId = $module_object->getLastInsertId();
                handleCategories($lastInsertId);
            }
            $message = $result ? 'Record added successfully.' : 'An error occurred while adding a new record. Please try again.';
            break;
        case 'edit':
            handleCategories($_POST['id']);
            $result = $module_object->update($id);
            $message = $result ? 'Record updated successfully.' : 'An error occurred while updating the record. Please try again.';
            break;
        case 'delete':
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