<?php 

set_time_limit(2400);
session_start();
ob_start();


include_once('config.php');


include_once('db/db.php');

// Conecto con Twitter
require_once ('codebird-php.php');
\Codebird\Codebird::setConsumerKey($tw_consumer, $tw_secret); // static, see 'Using multiple Codebird instances'

$cb = \Codebird\Codebird::getInstance();
$cb->setToken($tw_token_a, $tw_token_b);

include('bot.php');

if (isset($_GET["screen_name"])) {$screen_name = $_GET["screen_name"];}
if (isset($_GET["accion"])) {$accion = $_GET["accion"];}

if ($accion == "") {$accion = "buscar";} // default

if ($accion == "buscar")
{
    $usuario = get_user($screen_name);    
    
}
if ($accion == "ok")
{
    $id_str = $_GET["id_str"];
    // guardo semilla
    // marco que es bot
    // vuelvo al menú inicial
    $f = new stdClass();
    $f->id_str = $_GET["id_str"];
    $f->name = $_GET["name"];
    $f->screen_name = $_GET["screen_name"];
    $f->location = $_GET["location"];
    $f->description = $_GET["description"];
    $f->followers_count = $_GET["followers_count"];
    $f->friends_count = $_GET["friends_count"];
    $f->created_at = $_GET["created_at"];
    $f->statuses_count = $_GET["statuses_count"];
    $f->lang = $_GET["lang"];
    

    save_if_not_exist($f, 1, 0);
    
     if ($screen_name <> "")
    {
        // tomo los tuits, prueba típica de bots para análisis posterior
        $tuits = get_tuits ($screen_name);
        // guardo los tuits del usuario analizado
        save_tuits ($id_str, $screen_name, $tuits);
        // tomo followers y amigos
        $users = array_merge(get_followers($screen_name),get_friends($screen_name));        
        // ordeno y limpio duplicados
        $users = array_unique($users,SORT_REGULAR);
    }
    foreach ( $users as $foo ) {
        // limpio los que ya vi
    if (!visto_user($foo->id_str))
            {
                $usuarios[]=$foo;
            }
    }  
    foreach ( $users as $f ) {
        // guardo sus contactos para chequear después
            save_if_not_exist($f, 0, 0); // default para comenzar
            // guardo su relación
            save_relation($id_str,$f->id_str);
    }
    // redirect
    header("Location: index.php");
}




/*
stdClass Object ( [id] => 12894042 [id_str] => 12894042 [name] => Fabio Baccaglioni [screen_name] => fabiomb [location] => Buenos Aires, Argentina [profile_location] => [description] => Blogger, Vlogger, publicista, consultor, developer, nardogeek, periodista, https://t.co/GtunXaywha, https://t.co/obcWLaKHs4, https://t.co/GbSj5yV2D5 [url] => https://t.co/Z4EJD9wBGc [entities] => stdClass Object ( [url] => stdClass Object ( [urls] => Array ( [0] => stdClass Object ( [url] => https://t.co/Z4EJD9wBGc [expanded_url] => http://www.fabio.com.ar [display_url] => fabio.com.ar [indices] => Array ( [0] => 0 [1] => 23 ) ) ) ) [description] => stdClass Object ( [urls] => Array ( [0] => stdClass Object ( [url] => https://t.co/GtunXaywha [expanded_url] => http://Fabio.com.ar [display_url] => Fabio.com.ar [indices] => Array ( [0] => 75 [1] => 98 ) ) [1] => stdClass Object ( [url] => https://t.co/obcWLaKHs4 [expanded_url] => http://Tecnogeek.com [display_url] => Tecnogeek.com [indices] => Array ( [0] => 100 [1] => 123 ) ) [2] => stdClass Object ( [url] => https://t.co/GbSj5yV2D5 [expanded_url] => http://FabioTV.com [display_url] => FabioTV.com [indices] => Array ( [0] => 125 [1] => 148 ) ) ) ) ) [protected] => [followers_count] => 13892 [friends_count] => 1042 [listed_count] => 562 [created_at] => Thu Jan 31 00:33:06 +0000 2008 [favourites_count] => 4197 [utc_offset] => -10800 [time_zone] => Buenos Aires [geo_enabled] => 1 [verified] => [statuses_count] => 160691 [lang] => es [status] => stdClass Object ( [created_at] => Mon Jul 25 19:45:27 +0000 2016 [id] => 757663071485714433 [id_str] => 757663071485714433 [text] => @Grizzluza @pablotossi ojo, que de tan abandoner te podés volver neohippie y empezar con alpargatas y comiendo semillitas... [truncated] => [entities] => stdClass Object ( [hashtags] => Array ( ) [symbols] => Array ( ) [user_mentions] => Array ( [0] => stdClass Object ( [screen_name] => Grizzluza [name] => Fernando Barbella [id] => 29786735 [id_str] => 29786735 [indices] => Array ( [0] => 0 [1] => 10 ) ) [1] => stdClass Object ( [screen_name] => pablotossi [name] => Pablo Tossi [id] => 7801342 [id_str] => 7801342 [indices] => Array ( [0] => 11 [1] => 22 ) ) ) [urls] => Array ( ) ) [source] => TweetDeck [in_reply_to_status_id] => 757662863183970304 [in_reply_to_status_id_str] => 757662863183970304 [in_reply_to_user_id] => 29786735 [in_reply_to_user_id_str] => 29786735 [in_reply_to_screen_name] => Grizzluza [geo] => [coordinates] => [place] => [contributors] => [is_quote_status] => [retweet_count] => 0 [favorite_count] => 0 [favorited] => [retweeted] => [lang] => es ) [contributors_enabled] => [is_translator] => [is_translation_enabled] => [profile_background_color] => 29004F [profile_background_image_url] => http://pbs.twimg.com/profile_background_images/456140245633740800/O5YHyfqj.jpeg [profile_background_image_url_https] => https://pbs.twimg.com/profile_background_images/456140245633740800/O5YHyfqj.jpeg [profile_background_tile] => [profile_image_url] => http://pbs.twimg.com/profile_images/757107547992125440/4QXa_Wzl_normal.jpg [profile_image_url_https] => https://pbs.twimg.com/profile_images/757107547992125440/4QXa_Wzl_normal.jpg [profile_banner_url] => https://pbs.twimg.com/profile_banners/12894042/1435286325 [profile_link_color] => 0084B4 [profile_sidebar_border_color] => FFFFFF [profile_sidebar_fill_color] => DDFFCC [profile_text_color] => 333333 [profile_use_background_image] => 1 [has_extended_profile] => 1 [default_profile] => [default_profile_image] => [following] => [follow_request_sent] => [notifications] => [suspended] => [needs_phone_verification] => [httpstatus] => 200 [rate] => Array ( [limit] => 181 [remaining] => 180 [reset] => 1469481814 ) )
*/



?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>BotKillah!</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" ></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <style>
            /* Move down content because we have a fixed navbar that is 50px tall */
body {
  padding-top: 50px;
}


/*
 * Global add-ons
 */

.sub-header {
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

/*
 * Top navigation
 * Hide default border to remove 1px line.
 */
.navbar-fixed-top {
  border: 0;
}
        </style>
    </head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">BotKillah</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

        </div><!--/.navbar-collapse -->
      </div>
    </nav>
    <div class="container">
        <h1>BotKillah</h1>
        
        <div class="row">
            <div class="col-sm-12">

                <?php 
//print_r (($usuario));
?>
    Datos obtenidos del usuario
   <form action="semilla.php" method="GET">

   <input id="accion" type="hidden" name="accion" value="ok">
   screen_name: <input id="screen_name" type="text" name="screen_name" value="<?php echo $usuario->screen_name;?>"><br />
   name: <input id="name" type="text" name="name" value="<?php echo $usuario->name;?>"><br />
   id_str: <input id="id_str" type="text" name="id_str" value="<?php echo $usuario->id_str;?>"><br />
   location: <input id="location" type="text" name="location" value="<?php echo $usuario->location;?>"><br />
   description: <input id="description" type="text" name="description" value="<?php echo $usuario->description;?>"><br />
   followers_count: <input id="followers_count" type="text" name="followers_count" value="<?php echo $usuario->followers_count;?>"><br />
   friends_count: <input id="friends_count" type="text" name="friends_count" value="<?php echo $usuario->friends_count;?>"><br />
    created_at: <input id="created_at" type="text" name="created_at" value="<?php echo $usuario->created_at;?>"><br />
    statuses_count: <input id="statuses_count" type="text" name="statuses_count" value="<?php echo $usuario->statuses_count;?>"><br />
    lang: <input id="lang" type="text" name="lang" value="<?php echo $usuario->lang;?>"><br />

    
   <input id="next" type="submit" value="Insertar semilla »" class="btn btn-default">
   </form>
          </div>
        </div>

    </div>

    </body>
</html>
