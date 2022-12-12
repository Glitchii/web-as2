<?#
/*
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
        <li>Jobs
            <ul>
                <?php foreach ($categories as $category) { ?>
                    <li><a href="/admin/categories.php?id=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
                <?php } ?>
            </ul>
        </li>
        <li><a href="/about.html">About Us</a></li>
        <li><a href="/faqs.php">FAQs</a></li>
    </ul>
</nav>

<img src="/images/randombanner.php" />