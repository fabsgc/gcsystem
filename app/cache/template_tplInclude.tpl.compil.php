<?php 

$cache = new cacheGc('twitter', "", 10);

if($cache->isDie()){
    $twitter = curl_init();
    curl_setopt($twitter, CURLOPT_URL, 'http://twitter.com/statuses/user_timeline/etudiant_libre.xml?count=6');
    curl_setopt($twitter, CURLOPT_TIMEOUT, 5);
    curl_setopt($twitter, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($twitter);
     
    if($content==false){
      echo 'Curl error #'.curl_errno($twitter).': ' . curl_error($twitter);
      echo $cache->getCache();
    }
    else{
      $cache->setVal($content);
      $cache->setCache($content);
      echo $cache->getCache();
    }
}
else{
    echo $cache->getCache();
}

 ?>