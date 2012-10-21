{{php: $cache = new cacheGc('twitter', "", 1800);

if($cache->isDie()){
    $twitter = curl_init();
    curl_setopt($twitter, CURLOPT_URL, 'http://twitter.com/statuses/user_timeline/Lucas5190.xml?count=4');
    curl_setopt($twitter, CURLOPT_TIMEOUT, 1);
    curl_setopt($twitter, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($twitter);
     
    if($content==false){
      echo 'Curl error #'.curl_errno($twitter).': ' . curl_error($twitter);
      $tweet = $cache->getCache();
    }
    else{
      $cache->setVal($content);
      $cache->setCache($content);
      $tweet = $cache->getCache();
    }
}
else{
    $tweet = $cache->getCache();
} }}
		<footer>
			<div id="centre">
				<div class="copyright">
					Copyright © - Tout droits réservé - Copie non autorisée
				</div>
				<div class="clear"></div>
				<div class="bloc_footer" style="width: 290px;">
					<div class="bloc_titre_footer">
						<span class="restez_connecter">Liens utile</span>
					</div>
					<div class="liens_utiles">
						<ul>
							<a href="#"><li>Plan du site</li></a><br />
							<a href="#"><li>Newsletter</li></a><br />
							<a href="#"><li>Mes domaines</li></a><br />
							<div id="icones_reseaux_general" class="rss"></div>
							<div id="icones_reseaux_general" class="facebook"></div>
							<a href="http://twitter.com/lucas5190"><div id="icones_reseaux_general" class="twitter"></div></a>
							<a href="http://ubster.com/#!/lucas5190"><div id="icones_reseaux_general" class="ubster"></div></a>
							<a href="callto://Lucas51901"><div id="icones_reseaux_general" class="skype"></div></a>
						</ul>
					</div>
				</div>
				<div class="bloc_footer" style="background-color: #407c9b; padding: 0 5px 5px; width: 340px;">
					<div class="bloc_titre_footer" style="width: 340px;">
						<span class="restez_connecter">Mes derniers tweets :</span>
					</div>
					<foreach var="$xml->status" as="$statut">
						<div class="dernier_tweet">
							{{php: 
								$xml = new SimpleXMLElement($tweet);

								foreach($xml->status as $statut){
 									echo '<a href="https://twitter.com/#!/Lucas5190/statuses/'.$statut->id.'" target="blank">'.utf8_decode($statut->text).'</a><br />';
								}
							}}
						</div>
					</foreach>
				</div>
				<div class="bloc_footer" style="padding-right: 0; width: 290px;">
					<div class="bloc_titre_footer" style="width: 300px;">
						<span class="restez_connecter">Partenaires :</span>
					</div>
					<div class="liens_utiles">
						<ul>
							<a href="http://www.legeekcafe.com/"><li>Le Geek Café</li></a><br />
							<a href="http://www.dzv.me/"><li>DZVoice</li></a>
						</ul>
					</div>
				</div>
			</div>		
		</footer>
		<script type="text/javascript">
			$( 'input[type="checkbox"]' ).mTip( {
			align: 'bottom'
			} );
		</script>

		<script type="text/javascript">
			$( 'div' ).mTip( {
			align: 'bottom'
			} );
		</script>

		<script type="text/javascript">
			$( 'a' ).mTip( {
			align: 'bottom'
			} );
		</script>

		<script type="text/javascript">
			$( 'img' ).mTip( {
			align: 'bottom'
			} );
		</script>

		<script type="text/javascript">
			$( 'span' ).mTip( {
			align: 'bottom'
			} );
		</script>

		<script type="text/javascript">
			$( '#infobulle' ).mTip( {
			align: 'bottom'
			} );
		</script>
	</div>
