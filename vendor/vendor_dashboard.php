<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        include("../conn_db.php"); 
        include('../head.php');
        if ($_SESSION["utype"] != "VENDOR") {
            header("location: ../restricted.php");
            exit(1);
        }

        // Fetch the canteen name based on the s_id
        $s_id = $_SESSION['s_id'];
        $canteen_query = "SELECT s_name FROM shop WHERE s_id = ?";
        $stmt = $mysqli->prepare($canteen_query);
        $stmt->bind_param("i", $s_id);
        $stmt->execute();
        $canteen_result = $stmt->get_result();
        $canteen_row = $canteen_result->fetch_assoc();
        $canteen_name = $canteen_row['s_name'];
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <title>Vendor Dashboard | QuickServe</title>
</head>

<body>
    <?php include('nav_header_vendor.php')?>

    <div class="container">
        <h2>Vendor Dashboard - <?php echo htmlspecialchars($canteen_name); ?></h2>
        <div class="row">
            <div class="col">
                <h4>Orders</h4>
                <?php
                $query = "
                    SELECT oh.orh_id, oh.orh_ordertime, oh.orh_orderstatus, oh.t_id, 
                           od.f_id, f.f_name, od.ord_amount, od.ord_buyprice, od.ord_note,
                           p.p_amount
                    FROM order_header oh
                    JOIN order_detail od ON oh.orh_id = od.orh_id
                    JOIN food f ON od.f_id = f.f_id
                    LEFT JOIN payment p ON oh.t_id = p.p_id
                    WHERE oh.s_id = ?
                    ORDER BY oh.orh_ordertime DESC
                ";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("i", $s_id);
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<table class='table'>";
                echo "<thead>
                        <tr>
                            <th>Food Name</th>
                            <th>Order Time</th>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Transaction ID</th>
                            <th>Note</th>
                            <th>Payment Amount</th>
                        </tr>
                      </thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['f_name']}</td>
                            <td>{$row['orh_ordertime']}</td>
                            <td>{$row['orh_id']}</td>
                            <td>{$row['orh_orderstatus']}</td>
                            <td>{$row['t_id']}</td>
                            <td>{$row['ord_note']}</td>
                            <td>{$row['p_amount']}</td>
                          </tr>";
                }
                echo "</tbody></table>";
                ?>
            </div>
        </div>
    </div>

    <?php include('vendor_footer.php')?>
</body>

</html>
