<?php /** Left sidebar for the admin area. */ ?>

<section class="left">
    <h3>Options</h3>
    <ul>
        <li><a href="/admin/jobs">Manage Jobs</a></li>
        <?php if ($this->isStaff()) { ?>
            <li><a href="/admin/categories">Categories</a></li>
            <li><a href="/admin/accounts">Accounts</a></li>
            <li><a href="/admin/enquiries">Enquiries</a></li>
        <?php } ?>
        <hr>
        <li><a href="/admin/logout">Logout</a></li>
    </ul>
</section>