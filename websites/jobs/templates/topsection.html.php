<?php

/**
 * This file should be required from the createHead method in classes/Pages.php.
 * This script will create the head, header, and nav using varibles from the method.
 * 
 * The body tag will be added and closed by the browser.
 */
?>

<!DOCTYPE html>
<html>

<head>
    <title>Jo's Jobs - <?= $title ?></title>
    <link rel="stylesheet" href="/static/styles.css" />
    <script src="/static/script.js"></script>
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
            <a href="/jobs">Jobs</a>
            <div class="menudropdown">
                <section>
                    <h3>Category</h3>
                    <ul>
                        <?php foreach ($this->categories as $category) { ?>
                            <li><a href="/jobs?categoryId=<?= $category->id ?>"><?= $category->name ?></a></li>
                        <?php } ?>
                    </ul>
                </section>
                <!-- <section class="seperator">OR</section> -->
                <section>
                    <h3>Location</h3>
                    <form action="/jobs">
                        <?php if (strpos($_SERVER['REQUEST_URI'], '/jobs') === 0) {
                            if (isset($_GET['categoryId']))
                                echo "<input type='hidden' name='categoryId' value='{$_GET['categoryId']}' />";
                            if (isset($_GET['location']))
                                echo "<input type='hidden' name='location' value='{$_GET['location']}' />";
                            if (isset($_GET['id']))
                                echo "<input type='hidden' name='id' value='{$_GET['id']}' />";
                        } ?>
                        
                        <input placeholder="Enter a location" name="location" class="nice" />
                        <input type="submit" value="Apply" />
                    </form>
                </section>
            </div>
        </li>
        <li><a href="/about">About Us</a></li>
        <li><a href="/faqs">FAQs</a></li>
        <li>
            <a>More</a>
            <div class="menudropdown" style="margin-left: 2em;">
                <section class="single">
                    <a href="/contact">Contact</a>
                    <a href="/admin">Dashboard</a>
                    <?php if ($this->loggedIn()) { ?>
                        <a href="/admin/logout">Logout</a>
                    <?php } ?>
                </section>
            </div>
        </li>
    </ul>
</nav>

<img src="/images/randombanner.php" />