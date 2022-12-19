<?php

/**
 * This page is imported from categories.php
 * Some variables are defined there and used here.
 * 
 */

$db ??= new Database();
// Get category from id param so the name can be used in the title
$name = $db->category->select(['id' => $_GET['id']], 'name')['name'] ?? null;
// If the category doesn't exist, redirect to categories.php
$name === null && redirect('categories.php');
createHead("$name Jobs");
?>

<main class="sidebar">
    <section class="left">
        <ul>
            <?php foreach ($categories as $category) { ?>
                <li <?= $category['id'] == $_GET['id'] ? 'class="current"' : '' ?>>
                    <a href="?id=<?= $category['id'] ?>"><?= $category['name'] ?></a>
                </li>
            <?php } ?>
        </ul>
    </section>

    <section class="right">
        <h1><?= $name ?></h1>
        <ul class="listing">
            <?php
            // Fetch all jobs in the category that are not archived and have a closing date in the future
            $jobs = $db->job->selectAll(['categoryId' => $_GET['id'], 'AND', 'archived' => 0, 'AND', 'closingDate > ', (new DateTime())->format('Y-m-d')]);
            
            if (!$jobs)
                echo '<p>No jobs in this category yet.</p>';
            else
                foreach ($jobs as $job) { ?>
                <li>
                    <div class="details">
                        <h2><?= $job['title'] ?></h2>
                        <h3><?= $job['salary'] ?></h3>
                        <p><?= nl2br($job['description']) ?></p>
                        <a class="more" href="/apply.php?id=<?= $job['id'] ?>">Apply for this job</a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </section>
</main>

<?php include 'footer.html.php'; ?>