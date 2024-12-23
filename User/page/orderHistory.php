<?php
include '../../_base.php';

// ----------------------------------------------------------------------------

// (1) Authorization (member)
// auth('Member');

// (2) Return orders belong to the user (descending)
$stm = $_db->prepare('
    SELECT * FROM `order`
    WHERE userID = ?
    ORDER BY orderID DESC
');
 $stm->execute([$userId]);
$arr = $stm->fetchAll();




// ----------------------------------------------------------------------------

$_title = 'Order | History';
include '../header.php';
?>

<!-- (B) EXTRA: CSS -->
<!-- TODO -->
 <style>
    tr:hover .popup  {
        display: grid !important;
        grid: auto / repeat(5, auto);
        gap: 1px;
        border: none;
    } 

    .popup img {
        width: 50px;
        height: 50px;
        outline: 1px solid #333; /* border color*/
    }
</style>


<p>
    <button data-post="reset.php" data-confirm>Reset</button>
</p>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Datetime</th>
        <th>Count</th>
        <th>Total (RM)</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $o): ?>
    <tr>
        <td><?= $o->id ?></td>
        <td><?= $o->datetime ?></td>
        <td class="right"><?= $o->count ?></td>
        <td class="right"><?= $o->total ?></td>
        <td>
            <button data-get="detail.php?id=<?= $o->id ?>">Detail</button>
            <!-- (A) EXTRA: Product photos -->
            <!-- TODO -->
             <div class="popup">
                <?php
                    $stm = $_db->prepare('
                        SELECT p.photo 
                        FROM item AS i, product AS p
                        WHERE i.product_id = p.id
                        AND i.order_id = ?
                    ');
                    $stm->execute([$o->id]);
                    $photos = $stm->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($photos as $photo) {
                        echo "<img src='/products/$photo'>";
                    }
                ?>
            </div>

        </td>
    </tr>
    <?php endforeach ?>
</table>

<?php
include '../footer.php';