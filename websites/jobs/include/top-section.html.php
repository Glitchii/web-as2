<?php

/**
 * This file should be required from the createHead function in utils.php.
 * This script will create the head, header, and nav using varibles from the function.
 * 
 * The body tag will be added and closed by the browser.
 */
?>

<!DOCTYPE html>
<html>

<head>
    <title>Jo's Jobs - <?= $title ?></title>
    <link rel="stylesheet" href="/styles.css" />
</head>

<header>
    <section>
        <aside>
            <h3>Office Hours:</h3>
            <p>Mon-Fri: 09:00-17:30</p>
            <p>Sat: 09:00-17:00</p>
            <p>Sun: Closed</p>
        </aside>
        <h1>Jo's Jobs</h1>
    </section>
</header>

<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li>
            <a href="/admin/jobs.php">Jobs</a>
            <div class="menudropdown">
                <section>
                    <h3>Categories</h3>
                    <ul>
                        <?php foreach ($categories as $category) { ?>
                            <li><a href="/admin/categories.php?id=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
                        <?php } ?>
                    </ul>
                </section>
                <section>
                    <h3>Location</h3>
                    <form action="/admin/jobs.php">
                        <input placeholder="Enter a location" name="location" class="nice" />
                        <input type="submit" value="Apply" />
                    </form>
                </section>
            </div>
        </li>
        <li><a href="/about.php">About Us</a></li>
        <li><a href="/faqs.php">FAQs</a></li>
        <li>
            <a>More</a>
            <div class="menudropdown" style="margin-left: 2em;">
                <section class="single">
                    <a href="/admin/logout.php">Logout</a>
                    <a href="/admin/index.php">Admin</a>
                </section>
            </div>
        </li>
    </ul>
</nav>

<img src="/images/randombanner.php" />