<?php

include "../Admin/header.php";

?>
<div id="info"><?= temp('info') ?></div>

<?php

if ($_user) {
    $pCount = $_db->prepare("SELECT COUNT(*) AS totalProducts FROM Product");
    $oCount = $_db->prepare("SELECT COUNT(*) AS totalOrders FROM `Order`");
    $totalR = $_db->prepare("SELECT SUM(grandTotal) AS totalRevenue 
FROM `Order` 
WHERE orderStatus = 'Delivered';
");

    $topS  =  $_db->prepare("SELECT 
    p.prodName,
    SUM(oi.orderItemsQty) AS totalSold
FROM Order_Item oi
JOIN Product p ON oi.prodID = p.prodID
GROUP BY oi.prodID, p.prodName
ORDER BY totalSold DESC
LIMIT 5;");




    $pCount->execute();
    $oCount->execute();
    $totalR->execute();
    $topS->execute();

    $productCount = $pCount->fetch(PDO::FETCH_ASSOC);
    $orderCount = $oCount->fetch(PDO::FETCH_ASSOC);
    $totalRevenue = $totalR->fetch(PDO::FETCH_ASSOC);
    $topSelling = $topS->fetch(PDO::FETCH_ASSOC);
    print_r($topSelling);
}

if (!$_user) {
    redirect("pages/adminLogin.php");
}
?>
<div>
    <div>
        <h1>Total Products: <?= htmlspecialchars($productCount['totalProducts'] ?? '0') ?></h1>
        <h1>Total Order: <?= htmlspecialchars($orderCount['totalOrders'] ?? '0') ?></h1>
        <h1>Total Revenue: <?= htmlspecialchars($totalRevenue['totalRevenue'] ?? '0') ?></h1>
        <h1>Top Selling Product: <?= htmlspecialchars($topSelling['prodName']) ?> <br>
            Sold: <?= htmlspecialchars($topSelling['totalSold']) ?>
        </h1>

    </div>
</div>


</body>


</html>