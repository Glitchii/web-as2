<main class="home padded error">
    <h2><?= $title ?? 'Error' ?></h2>
    <ul>
        <?php foreach ($errors as $error) { ?>
            <li><?= $error ?></li>
        <?php } ?>
    </ul>
    <p><a class="link" href="<?= $_SERVER['REQUEST_URI'] ?>">Go back</a></p>
</main>