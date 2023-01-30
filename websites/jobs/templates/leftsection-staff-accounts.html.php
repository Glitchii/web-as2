<section class="left">
    <?php if ($subpage == 'modify') { ?>
        <h3>Options:</h3>
        <ul>
            <?php if (isset($account->id)) { ?>
                <li><a href="/admin/accounts/modify?id=<?= $account->id ?>" class="<?= $account->id ? 'current' : '' ?>">Edit</a></li>
                <li data-confirm="Are you sure you want to delete '<?= $account->username ?>'?"><a href="/admin/accounts/modify?action=delete&id=<?= $account->id ?>" class="delete">Delete</a></li>
                <hr>
            <?php } ?>
            <li><a href="/admin/accounts/modify" class="<?= isset($account->id) ? '' : 'current' ?>">Add Account</a></li>
            <li><a href="/admin/accounts">All Accounts</a></li>
    <?php } else { ?>
        <h3>Type:</h3>
        <ul>
            <li><a href="?type=client" class="<?= $accountType == 'client' ? 'current' : '' ?>">Clients</a></li>
            <li><a href="?type=staff" class="<?= $accountType == 'staff' ? 'current' : '' ?>">Staff</a></li>
            <li><a href="/admin/accounts" class="<?= !$accountType ? 'current' : '' ?>">All</a></li>
    <?php } ?>
        <hr>
        <li><a href="/admin">Dashboard</a></li>
    </ul>
</section>