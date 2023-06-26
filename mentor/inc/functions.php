<?php 

// Function to decode Unicode escape sequences.
function decodeUnicodeEscapeSequences($matches) {
    return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
}

// Function to process translations.
// This replaces Unicode escape sequences with their corresponding UTF-8 characters.
function processTranslations($translations) {
    foreach ($translations as $key => $value) {
        $translations[$key] = preg_replace_callback('/u([0-9a-fA-F]{4})/', 'decodeUnicodeEscapeSequences', $value);
    }
    return $translations;
}

// Function to merge two arrays, while checking for empty values.
// Values from the second array will overwrite those from the first,
// unless they are empty or the corresponding value in the first array doesn't exist.
function array_merge_check_empty($arr1, $arr2) {
    foreach($arr2 as $key => $value) {
        if(is_array($value) && isset($arr1[$key])) {
            $arr1[$key] = array_merge_check_empty($arr1[$key], $arr2[$key]);
        } else {
            if(empty($value)) {
                continue;
            }
            $arr1[$key] = $value;
        }
    }
    return $arr1;
}

// Function to return the URL protocol (http or https) based on the $_SERVER superglobal.
function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

// Function to remove custom input from a given text.
// This removes any text starting with '↵↵' and ending with a period (inclusive).
function removeCustomInput($text) {
    // remove everything after &crarr;&crarr;
    $clean_text = preg_replace('/&crarr;&crarr;[\s\S]*/', '', $text);
    // then remove everything after ↵↵;
    $clean_text = preg_replace('/↵↵[\s\S]*/', '', $clean_text);
    return $clean_text;
}

// Function to reorder an array by a given id.
// This moves the item with the specified id to the start of the array.
function reorderArrayById($array, $id) {
    $reorderedArray = [];
    $itemWithId = null;

    foreach ($array as $item) {
        if ($item->id == $id) {
            $itemWithId = $item;
        } else {
            $reorderedArray[] = $item;
        }
    }

    if ($itemWithId) {
        array_unshift($reorderedArray, $itemWithId);
    }

    return $reorderedArray;
}

// Function to generate a new id for a thread.
function threadNewID() {
    return uniqid("thread_", true);
}


// Function to find a Stripe payment intent based on order id.
function findPaymentIntent($config, $id_order) {

    // List all PaymentIntents
    $paymentIntents = \Stripe\PaymentIntent::all();

    // Find the PaymentIntent with the matching id_order
    $paymentIntentFound = null;
    foreach ($paymentIntents->data as $paymentIntent) {
        if (isset($paymentIntent->metadata['id_order']) && $paymentIntent->metadata['id_order'] === $id_order) {
            $paymentIntentFound = $paymentIntent;
            break;
        }
    }
    
    return $paymentIntentFound;
}

// Function to truncate text to a specified maximum length.
// This cuts off the text at the last full word before the limit, and appends an ellipsis if the text was truncated.
// Line breaks are then converted to HTML <br> tags.
function truncateText($text, $maxLength) {
  if (strlen($text) > $maxLength) {
    $text = substr($text, 0, strrpos(substr($text, 0, $maxLength), ' '));
    $text .= '...';
  }
  $text = nl2br($text);
  return $text;
}