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
            </tr>
        </thead>

        <tbody class="trlinks">
            <?php
            // Array to select unarchived and unexpired jobs.
            $binds = ['archived' => 0, 'and', 'closingDate', '>', date('Y-m-d')];
            $categoryId = $this->param('category');
            $location = $this->param('location');

            // Include category filter if set.
            if ($categoryId) {
                $binds[] = 'and';
                $binds['categoryId'] = $categoryId;
            }

            // Order by closing date in ascending to show the jobs that are closing soonest first and limit to 10.
            $binds[] = 'order by closingDate asc limit 10';

            // Also filter by location or any locations if location is not set and search.
            $jobs = $this->db->job->search(['location' => $location ? "%$location%" : "%"], $binds);

            foreach ($jobs as $job) {
                $applicantCount = $this->db->applicant->select(['jobId' => $job['id']], 'count(*) as count');
                $category = $this->db->category->select(['id' => $job['categoryId']]);
            ?>
                <tr>
                    <td><a href="/jobs?id=<?= $job['id'] ?>"><?= $category['name'] ?></td></a>
                    <td><a href="/jobs?id=<?= $job['id'] ?>"><?= $job['title'] ?></td></a>
                    <td><a href="/jobs?id=<?= $job['id'] ?>"><?= is_numeric(substr($job['salary'], 0, 1)) ? '£' . $job['salary'] : $job['salary'] ?></td></a>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>