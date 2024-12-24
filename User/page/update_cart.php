<?php
require_once "../../_base.php";

if (isset($_SESSION['userId'], $_POST['prodID'], $_POST['newQty'])) {
    $userID = $_SESSION['userId'];
    $prodID = (int)$_POST['prodID'];
    $newQty = (int)$_POST['newQty'];

    try {
        // Fetch the user's cart ID
        $stm = $_db->prepare('SELECT * FROM cart WHERE userID = ?');
        $stm->execute([$userID]);
        $cart = $stm->fetch();

        if ($cart) {
            $cartID = $cart->cartID;

            // Update the quantity for the specific product
            $stm2 = $_db->prepare('UPDATE cart_item SET cartItemsQty = ? WHERE cartID = ? AND prodID = ?');
            $stm2->execute([$newQty, $cartID, $prodID]);

            echo "success";
        } else {
            echo "error1"; // No cart found
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "error2";
    }
} else {
    echo "error3"; // Missing required data
}
