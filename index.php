<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>

<?php

$config = parse_ini_file('config.ini', true);

if (isset($_GET['recentlyPlayed'])) {
    $url = sprintf($config['endpoints']['recentlyPlayed'], $config['keys']['api_key'], $config['keys']['steam_id']);
} else {
    $url = sprintf($config['endpoints']['getOwnedGames'], $config['keys']['api_key'], $config['keys']['steam_id']);
}

$response = file_get_contents($url);
$data = json_decode($response);
?>

<div id="content">
    <h1>Steam Games</h1>

    <nav>
        <ul>
            <li class="<?php echo (isset($_GET['getOwnedGames']) || !isset($_GET) )? 'active' : '';?>"><a href="?getOwnedgames" title="Games owned">Games owned</a></li>
            <li class="<?php echo (isset($_GET['recentlyPlayed']))? 'active' : '';?>"><a href="?recentlyPlayed" title="Played recently">Played recently</a></li>
            <li class="<?php echo (isset($_GET['unplayed']))? 'active' : '';?>"><a href="?unplayed" title="Unplayed/Untracked">Unplayed/Untracked</a></li>
        </ul>
    </nav>

    <table summary="steam games">
        <thead>
            <tr>
                <th colspan="2" data-sort="string">Game</th>
                <th data-sort="int">Playtime</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($data->response->games as $k => $game) {
            if (isset($_GET['unplayed']) && isset($game->playtime_forever)) {
                continue;
            } else {
                ?>
                <tr <?php if ($k % 2 == 0) { echo "class='alt'"; };?>>
                    <td data-sort-value="<?php echo $game->name;?>">
                        <?php if (!empty($game->img_icon_url)): ?>
                            <img src='http://media.steampowered.com/steamcommunity/public/images/apps/<?php echo $game->appid;?>/<?php echo $game->img_icon_url;?>.jpg'>
                        <?php endif; ?>
                    </td>
                    <td data-sort-value="<?php echo $game->name;?>"><?php echo $game->name;?></td>
                    <td data-sort-value="<?php echo (isset($game->playtime_forever)) ? $game->playtime_forever : 0 ;?>"><?php
                    if (isset($game->playtime_forever)) {
                        $hours = floor($game->playtime_forever / 60);
                        $mins = $game->playtime_forever % 60;
                        echo $hours."h ".$mins."m";
                    } else {
                        echo "No time logged";
                    }
                    ?></td>
                    <td><a class="btn" href="steam://run/<?php echo $game->appid;?>" title="Play <?php echo $game->name;?>">Play</a></td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/stupidtable.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>