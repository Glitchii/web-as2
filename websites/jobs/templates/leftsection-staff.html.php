<?php /** Left sidebar for the admin area. */ ?>

<section class="left">
    <h3>Options</h3>
    <ul>
        <li><a href="/admin/jobs.php">Manage Jobs</a></li>
        <?php if ($page->isStaff()) { ?>
            <!-- Show the categories page if the user is an staff -->
            <li><a href="/admin/categories.php">Categories</a></li>
            <li><a href="/admin/accounts.php">Accounts</a></li>
        <?php } ?>
        <li><a href="/admin/logout.php">Logout</a></li>
    </ul>
</section>