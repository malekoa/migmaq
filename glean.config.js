/** @type {import('./src/config').GlnConfig} */
module.exports = {
  "gitignore": true,
  "includePatterns": [
    "**/*.php",
    "!lib/PHPMailer"
  ],
  "blocks": {
    "nextprev": [
      "views/partials/content_navbar.php",
      "views/partials/dashboard_navbar.php",
      "views/partials/footer.php",
      "views/partials/head.php",
      "views/partials/lesson_modal.php",
      "views/partials/section_modal.php",
      "views/partials/toast.php",
      "views/partials/unit_modal.php",
      "views/show_lesson.php",
      "views/show_section.php",
      "views/show_unit.php",
      "views/units.php",
      "public/index.php",
      "models/Lesson.php",
      "models/Section.php",
      "models/Unit.php",
      "includes/init.php",
      "controllers/AudioController.php",
      "controllers/AuthController.php",
      "controllers/ContentsController.php",
      "controllers/DashboardController.php",
      "controllers/LessonController.php",
      "controllers/PageController.php",
      "controllers/SectionController.php",
      "controllers/UnitController.php",
      "controllers/UserController.php"
    ]
  }
};
