<?php
$categoryId = $_POST['category'] ?? $_GET['category'] ?? null;
$location = $_POST['location'] ?? $_GET['location'] ?? null;
?>

<details class="filter">
    <summary>Filter Jobs</summary>
    <form>
        <label for="category">Category</label>
        <select name="category">
            <option value>All</option>
            <?php foreach ($categories as $category) { ?>
                <option value="<?= $category['id'] ?>" <?= $categoryId == $category['id'] ? 'selected' : '' ?>>
                    <?= $category['name'] ?>
                </option>
            <?php } ?>
        </select>
        <label for="location">Location</label>
        <input placeholder="Enter location" name="location" class="nice" value="<?= $location ?>" />

        <input type="submit" value="Filter" />
    </form>
</details>


<script>
    document.querySelector('details.filter')
        .addEventListener('toggle', e => {
            if (e.target.open)
                // Scroll submite button into view thus revealing the input fields also.
                e.target.closest('.filter')
                .querySelector('input[type="submit"]')
                .scrollIntoView({
                    behavior: 'smooth'
                });
        });
</script>