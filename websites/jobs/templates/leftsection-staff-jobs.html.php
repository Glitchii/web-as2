<section class="left">
    <h3>Options:</h3>
    <ul>
        <?php if (isset($jobId)) { ?>
            <li><a href="/admin/jobs/modify?id=<?= $jobId ?>" class="<?= $subpage == 'modify' ? 'current' : '' ?>">Edit Job</a></li>
            <li><a href="/admin/jobs/applicants?id=<?= $jobId ?>" class="<?= $subpage == 'applicants' ? 'current' : '' ?>">Applicants</a></li>
            <hr>
        <?php } ?>
        <li><a href="/admin/jobs/modify" class="<?= $subpage == 'modify' && !$jobId ? 'current' : '' ?>">Add Job</a></li>
        <li><a href="/admin/jobs" class="<?= !$subpage ? 'current' : '' ?>">Jobs</a></li>
        <hr>
        <li><a href="/admin">Dashboard</a></li>
    </ul>
</section>