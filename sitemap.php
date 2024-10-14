<?php header("Content-type: text/xml"); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<?php

		if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false)
			$conn = new mysqli("localhost", "root", "", "dicerollers");
		else
			$conn = new mysqli("localhost", "ldiceroy_root", "h049iyz8j8tn", "ldiceroy_dicerollers");
		$sql="SELECT * FROM keywords WHERE text_body!='' ORDER BY created DESC";
		$result=$conn->query($sql);
		if($result && $result->num_rows>0){
			while($page=$result->fetch_array(MYSQLI_ASSOC)){
	?>
	<url>
		<loc>https://dicerollers.it/<?php echo $page['url']?>.html</loc>
		<lastmod><?php echo date('Y-m-d',$page['created'])?>T<?php echo date('H:i:s',$page['created'])?>+02:00</lastmod>
		<changefreq>daily</changefreq>
		<priority>1.0</priority>
	</url>
	<?php }} ?>
</urlset>