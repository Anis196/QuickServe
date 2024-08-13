<?php
include('../conn_db.php');

$username = $_POST["username"];
$password = $_POST["pwd"];

// Prepare and execute the query to find the vendor
$query = "
    SELECT c_id, c_username, c_firstname, c_lastname 
    FROM customer 
    WHERE c_username = ? AND c_pwd = ? AND c_type = 'VEN'
    LIMIT 1
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Successful login
    $row = $result->fetch_assoc();
    session_start();
    $_SESSION["aid"] = $row["c_id"];
    $_SESSION["firstname"] = $row["c_firstname"];
    $_SESSION["lastname"] = $row["c_lastname"];
    $_SESSION["utype"] = "VENDOR";

    // Fetch s_id from shop based on the username
    $s_id_query = "SELECT s_id FROM shop WHERE s_username = ?";
    $s_id_stmt = $mysqli->prepare($s_id_query);
    $s_id_stmt->bind_param("s", $username);
    $s_id_stmt->execute();
    $s_id_result = $s_id_stmt->get_result();
    
    if ($s_id_result->num_rows == 1) {
        $s_id_row = $s_id_result->fetch_assoc();
        $_SESSION["s_id"] = $s_id_row["s_id"];
        
        // Redirect to vendor home
        header("Location: vendor_home.php");
        exit();
    } else {
        // Handle case where s_id isn't found
        ?>
        <script>
            alert("Shop details not found for the vendor!");
            history.back();
        </script>
        <?php
    }
} else {
    // Invalid login
    ?>
    <script>
        alert("You entered wrong username and/or password!");
        history.back();
    </script>
    <?php
}
?>
