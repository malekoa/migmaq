<?php
// Determine the current page
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$breadcrumbTitle = 'Dashboard';

if (str_starts_with($currentPath, '/dashboard/unit-editor')) {
    $breadcrumbTitle = 'Unit Editor';
} elseif (str_starts_with($currentPath, '/dashboard/section-editor')) {
    $breadcrumbTitle = 'Section Editor';
} elseif (str_starts_with($currentPath, '/dashboard/lesson-editor')) {
    $breadcrumbTitle = 'Lesson Editor';
}
?>

<nav class="bg-body-tertiary navbar navbar-expand-lg">
    <div class="d-flex align-items-center justify-content-between container-fluid">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="mb-0 breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard/unit-editor">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($breadcrumbTitle) ?></li>
            </ol>
        </nav>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="me-auto mb-2 mb-lg-0 navbar-nav"></ul>
            <div class="d-flex align-items-center">
                <span class="navbar-text">Signed in as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                <span class="me-3"></span>
                <a class="btn-success btn" href="/logout">Log Out</a>
            </div>
        </div>
    </div>
</nav>
