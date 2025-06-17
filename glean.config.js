/** @type {import('./src/config').GlnConfig} */
module.exports = {
  "gitignore": true,
  "includePatterns": [
    "**/*.php",
    "!lib/PHPMailer"
  ],
  "blocks": {
    "core": [
      "lib/sendmail.php",
      "views/account.php",
      "views/forgot_password.php",
      "views/login.php",
      "views/manage_users.php",
      "views/register.php",
      "views/reset_password.php",
      "controllers/AuthController.php",
      "controllers/UserController.php",
      "includes/helpers.php",
      "includes/init.php",
      ".example.env"
    ],
    "static": [
      "views/404.php",
      "views/account.php",
      "views/contents.php",
      "views/dashboard_home.php",
      "views/forgot_password.php",
      "views/landing.php",
      "views/lesson_editor.php",
      "views/login.php",
      "views/manage_users.php",
      "views/register.php",
      "views/reset_password.php",
      "views/section_editor.php",
      "views/show_lesson.php",
      "views/show_section.php",
      "views/show_unit.php",
      "views/unit_editor.php",
      "views/units.php",
      "includes/helpers.php",
      "includes/init.php",
      "public/index.php"
    ]
  }
};
