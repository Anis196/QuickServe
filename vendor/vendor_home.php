<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <?php 
        session_start(); 
        include("../conn_db.php"); 
        include('../head.php');

        if ($_SESSION["utype"] != "VENDOR") {
            header("Location: ../restricted.php");
            exit(1);
        }

        // Retrieve the shop name
        $s_id = $_SESSION['s_id'];
        $query = "SELECT s_name FROM shop WHERE s_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $s_id);
        $stmt->execute();
        $stmt->bind_result($s_name);
        $stmt->fetch();
        $stmt->close();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/Color Icon with background.png" rel="icon">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/main1.css" rel="stylesheet">
    <title>Vendor Dashboard | QuickServe</title>
</head>

<body class="d-flex flex-column">

    <?php include('nav_header_vendor.php')?>

    <div class="d-flex text-center text-white promo-banner-bg py-3">
        <div class="p-lg-2 mx-auto my-3">
            <h1 class="display-5 fw-normal">VENDOR DASHBOARD</h1>
            <!-- Display Shop Name -->
            <?php if (isset($s_name)): ?>
                <p class="lead fw-normal">Welcome to <?php echo htmlspecialchars($s_name); ?>!</p>
            <?php else: ?>
                <p class="lead fw-normal">Shop name not found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container p-5" id="vendor-dashboard">
        <h2 class="border-bottom pb-2"><i class="bi bi-graph-up"></i> System Status</h2>

        <!-- ADMIN GRID DASHBOARD -->
        <div class="row row-cols-1 row-cols-lg-2 align-items-stretch g-4 py-3">

            <!-- GRID OF FOOD -->
            <div class="col">
                <a href="vendor_food_list.php" class="text-decoration-none text-dark">
                    <div class="card rounded-5 border-primary p-2">
                        <div class="card-body">
                            <h4 class="card-title">
                                <i class="bi bi-card-list"></i>
                                Menu
                            </h4>
                            <p class="card-text my-2">
                                <span class="h5">
                                    <?php
                                    // Query for food items for this vendor
                                    $food_query = "SELECT COUNT(*) AS cnt FROM food WHERE s_id = ?";
                                    $food_stmt = $mysqli->prepare($food_query);
                                    $food_stmt->bind_param("i", $s_id);
                                    $food_stmt->execute();
                                    $food_arr = $food_stmt->get_result()->fetch_array();
                                    $food_stmt->close();
                                    echo $food_arr["cnt"];
                                    ?>
                                </span>
                                menu item(s) for your shop
                            </p>
                            <div class="text-end">
                                <a href="vendor_food_list.php" class="btn btn-sm btn-outline-dark">Go to Menu List</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END GRID OF FOOD -->

            <!-- GRID OF ORDER -->
            <div class="col">
                <a href="vendor_order_list.php" class="text-decoration-none text-dark">
                    <div class="card rounded-5 border-warning p-2">
                        <div class="card-body">
                            <h4 class="card-title">
                                <i class="bi bi-card-list"></i>
                                Order
                            </h4>
                            <p class="card-text my-2">
                                <span class="h5">
                                    <?php
                                    // Query for orders for this vendor
                                    $order_query = "SELECT COUNT(*) AS cnt FROM order_header WHERE s_id = ?";
                                    $order_stmt = $mysqli->prepare($order_query);
                                    $order_stmt->bind_param("i", $s_id);
                                    $order_stmt->execute();
                                    $order_arr = $order_stmt->get_result()->fetch_array();
                                    $order_stmt->close();
                                    echo $order_arr["cnt"];
                                    ?>
                                </span>
                                order(s) for your shop
                            </p>
                            <div class="text-end">
                                <a href="vendor_order_list.php" class="btn btn-sm btn-outline-dark">Go to Order List</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END GRID OF ORDER -->

        </div>
        <!-- END ADMIN GRID DASHBOARD -->

    </div>
    <?php include('vendor_footer.php')?>
</body>

</html>
