<?php
// Start the session

// Include necessary files
include "header.php";





if (is_get()) {
    $stm = $_db->prepare('SELECT * FROM Admin WHERE adminid = ?');
    $stm->execute([$_user->adminID]);
    $a = $stm->fetch();
}
?>

<body>
    <div id="info"><?= temp('info') ?></div>
    <?php if ($a): ?>
        <form id="admin-profile-form" action="adminUpdateDetails.php?id=<?= htmlspecialchars($a->adminID) ?>" method="post">

            <div class="profile-container">
                <div id="image-container">
                    <img
                        src="/image/admin/uploads/<?= $_user->adminPic ?>"
                        alt="Profile Picture"
                        class="profile-img">
                </div>
                <!-- Admin Details -->
                <h2><?= htmlspecialchars($a->adminName) ?></h2>
                <p><strong>Email:</strong> <?= htmlspecialchars($a->adminEmail) ?></p>
                <p><strong>Phone Number:</strong> <?= htmlspecialchars($a->adminPhoneNo) ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars($a->adminRole) ?></p>

                <!-- Update Button -->
                <a href="adminUpdateDetails.php?id=<?= htmlspecialchars($a->adminID) ?>" class="update-btn">
                    Update Profile
                </a>
            </div>
        </form>

    <?php else: ?>
        <p>Admin profile not found.</p>
    <?php endif; ?>
</body>

</html>