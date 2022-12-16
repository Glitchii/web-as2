<?php
/*
 * This page is imported from /admin/categories.php
 * Some variables are defined there and used here.
 * 
 */

// Get the category from id so the name can be used in the title
$stmt = dbConnection()->prepare('SELECT name FROM category WHERE id = :id');
$stmt->execute(['id' => $_GET['id']]);
$name = $stmt->fetch()['name'] ?? null;

// If the category doesn't exist, redirect to categories.php
$name === null && exit(header('Location: categories.php'));
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
            $stmt = $pdo->prepare('SELECT * FROM job WHERE archived = 0 AND categoryId = :id AND closingDate > :date');
            $stmt->execute(['id' => $_GET['id'], 'date' => (new DateTime())->format('Y-m-d')]);
            $jobs = $stmt->fetchAll();

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