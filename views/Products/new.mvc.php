{% extends "base.mvc.php" %}

{% block title %}Product{% endblock %}

{% block body %}


<h1>New Product</h1>

<form method="post" action="/products/create">
    <?php require "../views/Products/form.php"; ?>
</form>

{% endblock %}