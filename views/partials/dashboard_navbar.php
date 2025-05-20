<?php // views/partials/navbar.php ?>
<nav class="bg-body-tertiary navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Learn Mi'gmaq <i class="bi-chevron-compact-right bi"></i> Lesson Editor <i class="bi-chevron-compact-right bi"></i> Units</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="me-auto mb-2 mb-lg-0 navbar-nav"></ul>
            <div class="d-flex">
                <span class="navbar-text">Signed in as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                <span class="me-3"></span>
                <a class="btn-success btn" href="/logout">Log Out</a>
            </div>
        </div>
    </div>
</nav>