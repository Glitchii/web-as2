<?php
$categoryId = $this->param('category');
$location = $this->param('location');
?>

<details class="filter">
    <summary>Filter Jobs</summary>
    <form>
        <label for="category">Category</label>
        <select name="category">
            <option value>All</option>
            <?php foreach ($this->categories as $category) { ?>
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