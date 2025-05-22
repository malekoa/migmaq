<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>

<body class="d-flex flex-column min-vh-100">

    <header>
        <nav class="px-2 py-0 navbar" style="background-color: rgb(240, 69, 21);">
            <div class="d-flex align-items-center justify-content-between container-fluid">
                <!-- Brand -->
                <a href="/" class="d-flex align-items-center mb-0 text-white navbar-brand">
                    <img src="/assets/logo.png" alt="Logo" height="75" class="me-2 py-0">
                    Learn Mi'gmaq Online
                </a>

                <!-- Social Icons -->
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
                </div>
            </div>
        </nav>
    </header>



    <main class="flex-grow-1">
        <div class="hero-image" style="
        background-image: url('/assets/landscape.png');
        background-size: cover;
        background-position: center 50%;
        height: 250px;">
        </div>

        <div class="mt-5 container">
            <div class="align-items-center row">
                <!-- Text Content -->
                <div class="mb-4 mb-md-0 text-md-start text-center col-md-8">
                    <h1>Learn Mi'gmaq Online</h1>
                    <p class="lead">This site helps you learn Mi'gmaq on your own or alongside classes.</p>
                    <p>Each section includes units with vocabulary, dialogs, and practice exercises. You'll hear real Mi'gmaq speakers to train your ear and pronunciation.</p>
                    <p>The lessons come from the Mi'gmaq Partnership between Listuguj Education Directorate, McGill, and Concordia. Many speakers are from Listuguj, so their accent may differ from your community's.</p>

                    <a href="/contents" class="mt-3 btn btn-secondary btn-lg">
                        Browse Table of Contents
                    </a>
                </div>

                <!-- Logos -->
                <div class="text-center col-md-4">
                    <div class="d-flex flex-column align-items-center gap-4">
                        <img src="/assets/listuguj-logo.png" alt="Listuguj Education Directorate" style="width: 200px; object-fit: contain;">
                        <img src="/assets/mcgill-logo.png" alt="McGill University" style="width: 200px; object-fit: contain;">
                        <img src="/assets/concordia-logo.png" alt="Concordia University" style="width: 200px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>


</body>

</html>