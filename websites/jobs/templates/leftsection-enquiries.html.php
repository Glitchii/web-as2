<section class="left">
    <h3>Options:</h3>
    <?php if ($enquiry) { ?>
        <ul>
            <li>
                <?php if ($enquiry['completedBy']) { ?>
                        <a href="/admin/enquiries?action=incomplete&id=<?= $enquiry['id'] ?>">Reopen</a>
                    <?php } else { ?>
                        <a href="/admin/enquiries?action=complete&id=<?= $enquiry['id'] ?>">Close</a>
                <?php } ?>
            </li>
            <li><a href="/admin/enquiries?action=delete&id=<?= $enquiry['id'] ?>" data-confirm="Are you sure you want to delete this enquiry?">Delete</a></li>
            <hr>
            <li><a href="/admin/enquiries">All Enquiries</a></li>
    <?php } else { ?>
        <ul>
            <li><a href="?completed" class="<?= $completedFilter ? 'current' : '' ?>">Completed</a></li>
            <li><a href="?incomplete" class="<?= $incompleteFilter ? 'current' : '' ?>">Incomplete</a></li>
            <li><a href="/admin/enquiries" class="<?= !$completedFilter && !$incompleteFilter ? 'current' : '' ?>">All Enquiries</a></li>
    <?php } ?>
            <hr>
            <li><a href="/admin">Dashboard</a></li>
        </ul>
</section>