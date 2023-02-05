<main class="sidebar">
    <section class="left categories">
        <ul>
            <?php foreach ($this->categories as $cat) { ?>
                <li <?= $category && $cat->id == $categoryId ? 'class="current"' : '' ?>>
                    <a href="<?= $this->appendQuery("categoryId={$cat->id}", 'id') ?>"><?= $cat->name ?></a>
                </li>
            <?php } ?>
        </ul>
    </section>

    <section class="right">
        <h1><?= $categoryName ?></h1>
        <ul class="listing">
            <?php
            if (!$jobs){
                $string = '';
                if ($categoryId) $string .= ' in this category';
                if ($location) $string .= " with a location containing '$location'";
                if (!$string) $string .= ' yet';

                echo "<p>No unexpired and unarchived jobs$string. <a href='/jobs'> Clear filters?</a></p>";
            } else {
                // If a jobId param is set, display the selected job first
                if ($job) { ?>
                    <li>
                        <div class="details">
                            <h2><?= $job->title ?></h2>
                            <h3><?= $job->salary ?></h3>
                            <p><?= nl2br($job->description) ?></p>
                            <a class="more" href="/jobs/apply?id=<?= $job->id ?>">Apply for this job</a>
                        </div>
                    </li>
                <?php }

                // Display jobs that are not the selected one (or all jobs if no job is selected)
                foreach ($jobs as $job) { ?>
                    <?php if ($job->id != $jobId) { ?>
                        <li>
                            <div class="details">
                                <h2><?= $job->title ?></h2>
                                <h3><?= $job->salary ?></h3>
                                <p><?= nl2br($job->description) ?></p>
                                <a class="more" href="/jobs/apply?id=<?= $job->id ?>">Apply for this job</a>
                            </div>
                        </li>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </ul>
    </section>
</main>