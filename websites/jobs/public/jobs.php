<?php
require_once "../include/utils.php";

$jobId = $_GET['jobId'] ?? $_POST['jobId'] ?? null;
$categoryId = $_GET['categoryId'] ?? $_POST['categoryId'] ?? null;

$job = null;
$category = null;

if ($jobId) {
    // If jobId param is set, select the job and the category it belongs to.
    $job = $db->job->select(['id' => $jobId]);
    $categoryId = $job['categoryId'] ?? null;
    $category = $db->category->select(['id' => $categoryId]);
} else if ($categoryId) {
    // Otherwise, if a categoryId param is set, select the category
    $category = $db->category->select(['id' => $categoryId]);
    if (!$category)
        $categoryId = null;
}

// Select the category if no job or category is selected
if (!$job && !$category) {
    $category = $db->category->select();
    $categoryId = $category['id'] ?? null;
}

$categoryName = $category['name'] ?? null;
$categoryId = $category['id'] ?? null;

$page->createHead("$categoryName Jobs");
?>

<main class="sidebar">
    <section class="left">
        <ul>
            <?php foreach ($page->categories as $cat) { ?>
                <li <?= $category && $cat['id'] == $categoryId ? 'class="current"' : '' ?>>
                    <a href="?categoryId=<?= $cat['id'] ?>"><?= $cat['name'] ?></a>
                </li>
            <?php } ?>
        </ul>
    </section>

    <section class="right">
        <h1><?= $categoryName ?></h1>
        <ul class="listing">
            <?php
            // Fetch all jobs in the category that are not archived and have a closing date in the future
            $jobs = $db->job->selectAll(['categoryId' => $categoryId, 'AND', 'archived' => 0, 'AND', 'closingDate > ', (new DateTime())->format('Y-m-d')]);

            if (!$jobs)
                echo '<p>No unexpired jobs in this category yet.</p>';
            else {
                // If a jobId param is set, display the selected job first
                if ($job) { ?>
                    <li>
                        <div class="details">
                            <h2><?= $job['title'] ?></h2>
                            <h3><?= $job['salary'] ?></h3>
                            <p><?= nl2br($job['description']) ?></p>
                            <a class="more" href="/apply.php?jobId=<?= $job['id'] ?>">Apply for this job</a>
                        </div>
                    </li>
                <?php }

                // Display jobs that are not the selected one (or all jobs if no job is selected)
                foreach ($jobs as $job) { ?>
                    <?php if ($job['id'] != $jobId) { ?>
                        <li>
                            <div class="details">
                                <h2><?= $job['title'] ?></h2>
                                <h3><?= $job['salary'] ?></h3>
                                <p><?= nl2br($job['description']) ?></p>
                                <a class="more" href="/apply.php?jobId=<?= $job['id'] ?>">Apply for this job</a>
                            </div>
                        </li>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </ul>
    </section>
</main>

<?php include '../include/footer.html.php'; ?>