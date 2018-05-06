<?php
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
$users = $db["users"][0];

// Retrieve the search term and orderby term
$key = $_GET["key"];
$search = $_GET["s"];
$orderby = $_GET["orderby"];
$search_result["contents"];

// Check the username and make the output
if (array_key_exists(strtolower(trim($_GET["username"])), $users)) {
    $output["success"] = "yes";
    $output["contents"] = $users[strtolower(trim($_GET["username"]))]["contents"][0];
    
    // Delete corresponding JSON obj
    unset($output["contents"][$key]);
    // Update the record
    $db["users"][0][strtolower(trim($_GET["username"]))]["contents"][0] = $output["contents"];
    file_put_contents("db.json", json_encode($db, JSON_PRETTY_PRINT));

    // Sorting
    if ($search == NULL || $orderby != NULL) {
        switch ($orderby) {
            case "title":
                ksort($output["contents"]);
                break;
            case "year":
                usort($output["contents"], function($a, $b) { //Sort the array using a user defined function
                    return $a["year"] > $b["year"] ? -1 : 1;
                });
                break;
            default:
                // Nothing to do
        }
    }
    
    // Searching
    $index = 0;
    if ($search != NULL) {
        $search_result["contents"] = '{';
        foreach ($output["contents"] as $item) {
            foreach ($item as $subitem) {
                // Look for the search term in subitem value
                if (gettype($subitem) != "array") {
                    if (stripos($subitem, $search) !== false) {
                        if ($index == 0) {
                            $search_result["contents"] = $search_result["contents"] . '"' . $item["name"] . '":' . json_encode($item);
                        } else {
                            $search_result["contents"] = $search_result["contents"] . ',"' . $item["name"] . '":' . json_encode($item);
                        }
                        $index++;
                        break;
                    }
                }
            }
        }
        $search_result["contents"] = $search_result["contents"] . '}';
        $output["contents"] = json_decode($search_result["contents"]);
    }
    
} else {
    $output["success"] = "no";
    $output["contents"] = "";
}

header("content-type: application/json");

print json_encode($output);
?>
