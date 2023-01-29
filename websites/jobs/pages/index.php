<main class="home padded">
    <p>Welcome to Jo's Jobs. <a href="/about" class="link">Find out about us</a></p>
    <h2 style="margin-top: 40px;">Jobs expiring soonest (10 max):</h2>

    <div class="tablemenu">
        <?php $this->renderTemplate('jobfilter'); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Title</th>
                <th>Salary</th>
                <th>Location</th>
                <th>Closing Date</th>
            </tr>
        </thead>

        <tbody class="trlinks">
            <?php
            foreach ($jobs as $job) {
                $applicantCount = $this->db->applicant->select(['jobId' => $job->id], 'count(*) as count');
                $category = $this->db->category->select(['id' => $job->categoryId]);
            ?>
                <tr>
                    <td><a href="/jobs?id=<?= $job->id ?>"><?= $category->name ?></td></a>
                    <td><a href="/jobs?id=<?= $job->id ?>"><?= $job->title ?></td></a>
                    <td><a href="/jobs?id=<?= $job->id ?>"><?= is_numeric(substr($job->salary, 0, 1)) ? '£' . $job->salary : $job->salary ?></td></a>
                    <td><a href="/jobs?id=<?= $job->id ?>"><?= $job->location ?></td></a>
                    <td><a href="/jobs?id=<?= $job->id ?>"><?= date('d/m/Y', strtotime($job->closingDate)) ?></td></a>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>