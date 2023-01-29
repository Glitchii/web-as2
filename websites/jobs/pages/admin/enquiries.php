<main class="sidebar">
    <?php $this->leftSection(compact('enquiry', 'completedFilter', 'incompleteFilter')); ?>

    <section class="right">
        <?php if ($enquiry) { ?>
            <h2 style="margin-bottom: 0;">Enquiry</h2>
            <div class="enquiry">
                <div>By <span class="name"><?= $enquiry->name ?></span> on <span class="created"><?= $enquiry->created ?></span></div>
                <h3>Contact Details</h3>
                <div>Email: <span class="email"><?= $enquiry->email ?? 'Not provided' ?></span></div>
                <div>Telephone: <span class="telephone"><?= $enquiry->telephone ?? 'Not provided' ?></span></div>
                <h3>Message</h3>
                <div class="message"><?= $enquiry->enquiry ?></div>
                <h3>Status</h3>
                <?php if ($enquiry->completedBy) { ?>
                    <div class="status">
                        <form class="status" method="post" action="/admin/enquiries?action=incomplete&id=<?= $enquiry->id ?>">
                            Completed by <?= $enquiry->completedBy ?>.
                            <label for="status" class="link" style="text-decoration-color: #444444b2;">Reopen</label>
                            <input type="submit" name="status" id="status" style="display: none;" />
                        </form>
                    </div>
                <?php } else { ?>
                    <form class="status" method="post" action="/admin/enquiries?action=complete&id=<?= $enquiry->id ?>">
                        Incomplete.
                        <label for="status" class="link" style="text-decoration-color: #444444b2;">Mark as complete</label>
                        <input type="submit" name="status" id="status" style="display: none;" />
                    </form>
                <?php } ?>
            </div>
        <?php } else { ?>
            <h2>Enquiries</h2>
            <?php if (!$enquiries) { ?>
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
                            if ($enquiry->completedBy) $enquiry->completedBy = $this->db->account->select(['id' => $enquiry->completedBy])->username ?? 'Unknown';
                            if ($completedFilter && !$enquiry->completedBy) continue;
                            if ($incompleteFilter && $enquiry->completedBy) continue; ?>
                            <tr>
                                <td><a href="/admin/enquiries?id=<?= $enquiry->id ?>"><?= $enquiry->name ?></a></td>
                                <td><a href="/admin/enquiries?id=<?= $enquiry->id ?>"><?= $enquiry->email ?></a></td>
                                <td><a href="/admin/enquiries?id=<?= $enquiry->id ?>"><?= $enquiry->telephone ?></a></td>
                                <td><a href="/admin/enquiries?id=<?= $enquiry->id ?>"><?= $sub($enquiry->enquiry) ?></a></td>
                                <td><a href="/admin/enquiries?id=<?= $enquiry->id ?>"><?= $enquiry->created ?></a></td>
                                <td><a href="/admin/enquiries?id=<?= $enquiry->id ?>"><?= $enquiry->completedBy ? "By {$enquiry->completedBy}" : 'Not yet' ?></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        <?php } ?>
    </section>
</main>