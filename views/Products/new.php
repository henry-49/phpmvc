<h1>New Product</h1>

<form method="post" action="/products/create">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" />

    <?php if (isset($errors["name"])) : ?>
    <p><?= $errors["name"] ?></p>
    <?php endif; ?>

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="3"></textarea>

    <button type="submit">Save</button>
</form>


</body>

</html>