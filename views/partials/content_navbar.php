<nav class="px-2 py-0 navbar" style="background-color: rgb(240, 69, 21);">
    <div class="d-flex align-items-center justify-content-between container-fluid">
        <!-- Left: Brand -->
        <a href="/" class="d-flex align-items-center mb-0 text-white navbar-brand">
            <img src="/assets/logo.png" alt="Logo" height="60" class="me-2 py-0">
            Learn Mi'gmaq Online
        </a>

        <!-- Right: Social Icons -->
        <div class="d-flex gap-3">
            <a href="#" class="text-white fs-4" title="Facebook">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="#" class="text-white fs-4" title="X (Twitter)">
                <i class="bi bi-twitter-x"></i>
            </a>
            <a href="#" class="text-white fs-4" title="Instagram">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="#" class="text-white fs-4" title="Tumblr">
                <i class="bi bi-tumblr"></i>
            </a>
        </div>
    </div>
</nav>

<!-- Breadcrumbs -->
<?php if (isset($breadcrumbs)): ?>
    <nav aria-label="breadcrumb" class="bg-light py-2">
        <div class="container-fluid">
            <ol class="mb-0 breadcrumb">
                <?php foreach ($breadcrumbs as $crumb): ?>
                    <?php if (!empty($crumb['url'])): ?>
                        <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= htmlspecialchars($crumb['label']) ?></a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($crumb['label']) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </div>
    </nav>
<?php endif; ?>
