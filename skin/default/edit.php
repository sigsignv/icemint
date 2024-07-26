<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit <?= $title ?> - IceMint</title>
  </head>
  <body>
    <form method="post">
      <input type="hidden" name="title" value="<?= $title ?>" />
      <textarea name="content" rows="4" cols="50"><?= $content ?></textarea>
      <button type="submit">Submit</button>
    </form>
  </body>
</html>
