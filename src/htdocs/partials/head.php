<?php
namespace AirQualityInfo\head;

function navItem($action, $desc) {
  global $currentAction, $currentController;
  require('partials/navbar/nav_item.php');
}
?><!DOCTYPE html>
<html lang="en">
  <head>
<?php if (CONFIG['ga_id']): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo CONFIG['ga_id']; ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '<?php echo CONFIG['ga_id']; ?>');
    </script>
<?php endif; ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="512x512" href="/public/img/dragon-512.png">
    <link rel="manifest" href="/manifest.json">

    <title><?php echo __('Air quality') ?> - <?php echo $currentDevice['description']; ?></title>

    <style><?php echo file_get_contents('public/css/critical.css') ?></style>

    <script defer src="/public/js/vendor.min.js"></script>
    <script defer src="/public/js/main.js?v=17"></script>
    <script defer src="/public/js/graph.js?v=17"></script>
    <script defer src="/public/js/annual_graph.js?v=17"></script>
  </head>
  <body data-pm10-limit1h="<?php echo PM10_LIMIT_1H ?>" data-pm25-limit1h="<?php echo PM25_LIMIT_1H ?>" data-pm10-limit24h="<?php echo PM10_LIMIT_24H ?>" data-pm25-limit24h="<?php echo PM25_LIMIT_24H ?>" data-current-lang='<?php echo $currentLocale->getCurrentLang() ?>' data-locale='<?php echo json_encode($currentLocale->getMessages()) ?>'>
    <div class="container">
      <div class="row">
        <div class="col-md-8 offset-md-2">
          <nav class="navbar navbar-expand-md navbar-light bg-light">
            <a href="<?php echo l('main', 'index'); ?>" class="navbar-left navbar-brand"><img src="/public/img/dragon.png"/> Air Quality Info</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Nawigacja">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                <?php navItem(array('main', 'index'), 'Home'); ?>
                <?php if (CONFIG['db']['type'] === 'mysql'): ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo __('Graphs') ?>
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <?php navItem(array('graph', 'index'), 'Graphs'); ?>
                    <?php navItem(array('annual_stats', 'index'), 'Annual stats'); ?>
                  </ul>
                </li>
                <?php else: ?>
                <?php navItem(array('graph', 'index'), 'Graphs'); ?>
                <?php endif ?>
                <?php navItem(array('static', 'about'), 'About'); ?>
                <?php require('partials/navbar/locations.php') ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo __('Theme') ?>
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <li>
                  <?php foreach(\Theme::THEMES as $name => $desc): ?>
                    <a class="dropdown-item <?php echo ($name == $currentTheme->getTheme()) ? 'active' : ''; ?>" href="<?php echo l($currentController, $currentAction, null, array(), array('theme' => $name)); ?>"><?php echo __($desc) ?></a>
                  <?php endforeach ?>
                  </li>
                  </ul>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <?php foreach(\AirQualityInfo\Locale::SUPPORTED_LANGUAGES as $lang => $desc): ?>
                  <li>
                    <a class="dropdown-item <?php echo ($lang == $currentLocale->getCurrentLang()) ? 'active' : ''; ?>" href="<?php echo l($currentController, $currentAction, null, array(), array('lang' => $lang)); ?>"><img src="/public/img/flags/<?php echo $lang ?>.png"/> <?php echo $desc ?></a>
                  </li>
                  <?php endforeach ?>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
<?php if (isset($device['maintenance'])): ?>
      <div class="row">
        <div class="col-md-8 offset-md-2">
          <div class="alert alert-warning">
            <?php echo $device['maintenance'] ?>
          </div>
        </div>
      </div>
<?php endif ?>
