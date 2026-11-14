<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo Ichi</title>
  <link rel="icon" href="/favicon.ico" sizes="any">

  <?php if (!$isDevelopment): ?>
    <link rel="stylesheet" href="/vue/main.css">
  <?php endif; ?>
</head>
<body>
    <div id="vue-app"></div>

    <?php if ($isDevelopment): ?>
      <script type="module" src="<?= e($viteBaseUrl) ?>/@vite/client"></script>
      <script type="module" src="<?= e($viteBaseUrl) ?>/src/vue/main.js"></script>
    <?php else: ?>
      <script type="module" src="/vue/app.js"></script>
    <?php endif; ?>
</body>
</html>