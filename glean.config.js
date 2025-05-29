/** @type {import('./src/config').GlnConfig} */
module.exports = {
  "gitignore": true,
  "includePatterns": [
    "**/*.php"
  ],
  "blocks": {
    "user-registration": [
      "controllers/AuthController.php",
      "controllers/UserController.php",
      "includes/init.php",
      "includes/helpers.php",
      "public/index.php",
      "views/register.php",
      "views/login.php",
      "views/manage_users.php"
    ],
    "dashboard": [
      "controllers/DashboardController.php",
      "controllers/UnitController.php",
      "controllers/SectionController.php",
      "controllers/LessonController.php",
      "includes/db.php",
      "public/dashboard/unit-editor.php",
      "public/dashboard/section-editor.php",
      "public/dashboard/lesson-editor.php",
    ]
  }
};
