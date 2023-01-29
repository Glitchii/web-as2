<section class="left">
    <h3>Options:</h3>
    <ul>
        <?php if (isset($category->id)) { ?>
            <li><a href="/admin/categories/modify?id=<?= $category->id ?>" class="<?= $category->id ? 'current' : '' ?>">Edit</a></li>
            <li data-confirm="Are you sure you want to delete the category '<?= $category->name ?>'?"><a href="/admin/categories/?action=delete&id=<?= $category->id ?>" class="delete">Delete</a></li>
            <hr>
        <?php } ?>
        <li><a href="/admin/categories/modify" class="<?= $subpage && !isset($category->id) ? 'current' : '' ?>">Add</a></li>
        <li><a href="/admin/categoieys" class="<?= $subpage ? '' : 'current' ?>">Categories</a></li>
        <hr>
        <li><a href="/admin">Dashboard</a></li>
    </ul>
</section>