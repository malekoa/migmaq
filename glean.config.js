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
    ]
  }
};
