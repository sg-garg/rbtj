<?php
include('setup.php');
login();
if (user('account_type','return')=='Poster' ) {
  header('Location: /site/Poster_Sections/menu.item.Posters/menu.item.Clients.php');
}else if(user('account_type','return') == 'Client Poster') {
  header('Location: /site/User_Sections/menu.item.Post_Ads/menu.item.Manual_Ad_Posting.php');
}else {
  header('Location: /site/User_Sections/menu.item.Ad_Capaigns/menu.item.List_Campaigns.php');
}
?>