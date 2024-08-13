<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <?php 
        session_start(); 
        include("../conn_db.php"); 
        include('../head.php');

        if($_SESSION["utype"] != "VENDOR") {
            header("Location: ../restricted.php");
            exit(1);
        }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/Color Icon with background.png" rel="icon">
    <link href="../css/main.css" rel="stylesheet">
    <title>Menu List | QuickServe</title>
</head>

<body class="d-flex flex-column h-100">

    <?php include('nav_header_vendor.php')?>

    <div class="container p-2 pb-0" id="admin-dashboard">
        <div class="mt-4 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php
            if(isset($_GET["dsb_fdt"])){
                if($_GET["dsb_fdt"] == 1){
                    ?>
            <!-- START SUCCESSFULLY DELETE MENU -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <i class="bi bi-check-circle ms-2"></i>
                    <span class="ms-2 mt-2">Successfully removed menu.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="vendor_food_list.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY DELETE MENU -->
            <?php }else{ ?>
            <!-- START FAILED DELETE MENU -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                    <i class="bi bi-x-circle ms-2"></i><span class="ms-2 mt-2">Failed to remove menu.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="vendor_food_list.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED DELETE MENU -->
            <?php }
                }
            if(isset($_GET["add_fdt"])){
                if($_GET["add_fdt"] == 1){
                    ?>
            <!-- START SUCCESSFULLY ADD FOOD MENU -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <i class="bi bi-check-circle ms-2"></i>
                    <span class="ms-2 mt-2">Successfully added new menu.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="vendor_food_list.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY ADD FOOD MENU -->
            <?php }else{ ?>
            <!-- START FAILED ADD FOOD MENU -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                    <i class="bi bi-x-circle ms-2"></i><span class="ms-2 mt-2">Failed to add new menu.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="vendor_food_list.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED ADD FOOD MENU -->
            <?php }
                }
            ?>

            <h2 class="pt-3 display-6">Menu List</h2>
            <form class="form-floating mb-3" method="GET" action="vendor_food_list.php">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control" id="f_name" name="f_name" placeholder="Food name"
                            <?php if(isset($_GET["search"])){?>value="<?php echo htmlspecialchars($_GET["f_name"], ENT_QUOTES, 'UTF-8');?>" <?php } ?>>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="search" value="1" class="btn btn-success">Search</button>
                        <button type="reset" class="btn btn-danger"
                            onclick="javascript: window.location='vendor_food_list.php'">Clear</button>
                        <a href="vendor_food_add.php" class="btn btn-primary">Add new menu</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="container pt-2" id="cust-table">
        <?php
            // Retrieve the shop ID from session
            $s_id = $_SESSION['s_id'];
            
            if(!isset($_GET["search"])){
                // Default query
                $search_query = "
                    SELECT f.f_id, f.f_name, f.f_price
                    FROM food f
                    WHERE f.s_id = ?
                    ORDER BY f.f_price DESC
                ";
            } else {
                // Search query with filtering
                $search_fn = $_GET["f_name"];
                $search_query = "
                    SELECT f.f_id, f.f_name, f.f_price
                    FROM food f
                    WHERE f.s_id = ? AND f.f_name LIKE ?
                    ORDER BY f.f_price DESC
                ";
                $search_fn = "%{$search_fn}%";
            }
            
            $stmt = $mysqli->prepare($search_query);
            
            if (isset($search_fn)) {
                $stmt->bind_param("ss", $s_id, $search_fn);
            } else {
                $stmt->bind_param("i", $s_id);
            }
            
            $stmt->execute();
            $search_result = $stmt->get_result();
            $search_numrow = $search_result->num_rows;
            
            if ($search_numrow == 0) {
        ?>
        <div class="row">
            <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                <i class="bi bi-x-circle ms-2"></i><span class="ms-2 mt-2">No menu items found!</span>
                <a href="vendor_food_list.php" class="text-white">Clear Search Result</a>
            </div>
        </div>
        <?php } else { ?>
        <div class="table-responsive">
        <table class="table rounded-5 table-light table-striped table-hover align-middle caption-top mb-5">
            <caption><?php echo $search_numrow; ?> menu(s) <?php if(isset($_GET["search"])){?><br /><a
                    href="vendor_food_list.php" class="text-decoration-none text-danger">Clear Search
                    Result</a><?php } ?></caption>
            <thead class="bg-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Menu name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($row = $search_result->fetch_assoc()) { ?>
                <tr>
                    <th><?php echo $i++; ?></th>
                    <td><?php echo htmlspecialchars($row["f_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $row["f_price"] . " INR"; ?></td>
                    <td>
                        <a href="vendor_food_detail.php?f_id=<?php echo $row["f_id"]; ?>"
                            class="btn btn-sm btn-primary">View</a>
                        <a href="vendor_food_edit.php?f_id=<?php echo $row["f_id"]; ?>"
                            class="btn btn-sm btn-outline-success">Edit</a>
                        <a href="vendor_food_delete.php?f_id=<?php echo $row["f_id"]; ?>"
                            class="btn btn-sm btn-outline-danger">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
        <?php }
            $search_result->free_result();
        ?>
    </div>

    <?php include('vendor_footer.php')?>
</body>

</html>
