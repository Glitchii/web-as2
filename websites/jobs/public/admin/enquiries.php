<?php
require_once "../../utils/utils.php";

$page->createHead("Enquiries");
$user = $page->staffOnly();
$enquryId = $page->param('id', 0);
$completedFilter = $page->param('completed', 0) !== null;
$uncompletedFilter = $page->param('uncompleted', 0) !== null;

if ($page->param('status', 0))
    // Form submitted to mark enquiry as delt with
    $db->enquiry->update(['completedBy' => $user['id']], ['id' => $enquryId]);

if ($enquryId == null)
    $enquiries = $db->enquiry->selectAll();
else {
    $enquiry = $db->enquiry->select(['id' => $enquryId]);
    if (!$enquiry) exit("<p>Enquiry not found</p>");
    if ($enquiry['completedBy']) $enquiry['completedBy'] = $db->account->select(['id' => $enquiry['completedBy']])['username'] ?? 'Unknown';
}
?>

<main class="sidebar">
    <?php if ($enquryId == null) { ?>
        <section class="left">
            <h3>Filter by:</h3>
            <ul>
                <li><a href="?completed" class="<?= $completedFilter ? 'current' : '' ?>">Completed</a></li>
                <li><a href="?uncompleted" class="<?= $uncompletedFilter ? 'current' : '' ?>">Uncompleted</a></li>
                <li><a href="<?= $_SERVER['PHP_SELF'] ?>" class="<?= !$completedFilter && !$uncompletedFilter ? 'current' : '' ?>">All</a></li>
            </ul>
        </section>
        <section class="right">
            <h2>Enquiries</h2>
            <?php if (count($enquiries) == 0) { ?>
                <p>No enquiries found</p>
            <?php } else { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Enquiry</th>
                            <th>Created On</th>
                            <th>Completed</th>
                        </tr>
                    </thead>

                    <tbody class="trlinks">
                        <?php foreach ($enquiries as $enquiry) {
                            if ($enquiry['completedBy']) $enquiry['completedBy'] = $db->account->select(['id' => $enquiry['completedBy']])['username'] ?? 'Unknown';
                            if ($completedFilter && !$enquiry['completedBy']) continue;
                            if ($uncompletedFilter && $enquiry['completedBy']) continue; ?>
                            <tr>
                                <td><a href="enquiries.php?id=<?= $enquiry['id'] ?>"><?= $enquiry['name'] ?></a></td>
                                <td><a href="enquiries.php?id=<?= $enquiry['id'] ?>"><?= $enquiry['email'] ?></a></td>
                                <td><a href="enquiries.php?id=<?= $enquiry['id'] ?>"><?= $enquiry['telephone'] ?></a></td>
                                <td><a href="enquiries.php?id=<?= $enquiry['id'] ?>"><?= sub($enquiry['enquiry']) ?></a></td>
                                <td><a href="enquiries.php?id=<?= $enquiry['id'] ?>"><?= $enquiry['created'] ?></a></td>
                                <td><a href="enquiries.php?id=<?= $enquiry['id'] ?>"><?= $enquiry['completedBy'] ? "By {$enquiry['completedBy']}" : 'Not yet' ?></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </section>
    <?php } else { ?>
        <section class="left">
            <ul>
                <li><a href="/admin/enquiries.php">Back to Enquiries</a></li>
                <li><a href="/index.php">Back Home</a></li>
            </ul>
        </section>
        <section class="right">
            <h2 style="margin-bottom: 0;">Enquiry</h2>
            <div class="enquiry">
                <div>By <span class="name"><?= $enquiry['name'] ?></span> on <span class="created"><?= $enquiry['created'] ?></span></div>
                <h3>Contact Details</h3>
                <div>Email: <span class="email"><?= $enquiry['email'] ?? 'Not provided' ?></span></div>
                <div>Telephone: <span class="telephone"><?= $enquiry['telephone'] ?? 'Not provided' ?></span></div>
                <h3>Message</h3>
                <div class="message"><?= $enquiry['enquiry'] ?></div>
                <h3>Status</h3>
                <?php if ($enquiry['completedBy']) { ?>
                    <div class="status">
                        Completed by <?= $enquiry['completedBy'] ?>
                    </div>
                <?php } else { ?>
                    <form class="status" method="post">
                        <span>Uncomplete.</span>
                        <label for="status" class="link" style="text-decoration-color: #444444b2;">Mark as complete</label>
                        <input type="submit" name="status" id="status" style="display: none;" />
                    </form>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
</main>

<?php include '../../templates/footer.html.php'; ?>