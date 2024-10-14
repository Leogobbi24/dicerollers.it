<?php

if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false)
	$conn = new mysqli("localhost", "root", "", "autoblog");
else
	$conn = new mysqli("localhost", "ldiceroy_root", "h049iyz8j8tn", "ldiceroy_dicerollers");

$sql="SELECT * FROM keywords WHERE url='".$_GET['url']."'";
$result=$conn->query($sql);
if(!$result || $result->num_rows==0){

	header('HTTP/1.1 301 Moved Permanently');
	header('Location: https://dicerollers.it/');
	exit;

}else{
	$page=$result->fetch_array(MYSQLI_ASSOC);
	
	$limit=($page['url']) ? "LIMIT 0,3" : "" ;

	$sql="SELECT * FROM keywords WHERE text_body!='' AND id!=".$page['id']." ORDER BY created DESC ".$limit;
	$result=$conn->query($sql);
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="<?php echo $page['meta_description']?>">
    <meta name="keywords" content="<?php echo $page['keyword']?>">

    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />

    <title><?php echo $page['title']?></title>

    <script src="https://kit.fontawesome.com/99163fb6b3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css?3">
    <link rel="stylesheet" href="css/utility.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-02P4ME6TMS"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-02P4ME6TMS');
    </script>

    <?php if($page['url']){ ?>
	<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BlogPosting",
      <?php if(!$page['image']){ ?>
      "headline": "<?php echo $page['title']?>"
      <?php }else{ ?>
      "headline": "<?php echo $page['title']?>",
      "image": "https://www.dicerollers.it/img/blog/<?php echo $page['image']?>"
      <?php } ?>
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "https://dicerollers.it/"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "<?php echo $page['title']?>",
        "item": "https://dicerollers.it/<?php echo $page['url']?>.html"
      }]
    }
    </script>
    <?php } ?>
    
    <?php echo $page['structured_data']?>
</head>

<body>
	<div class="header">
		<div class="container">
			<img src="img/logo-dicerollers.png" alt="Logo Dice Rollers" title="Logo Dice Rollers">
		</div>
	</div>

	<section class="hero">
		<div class="container">
			<div class="col-md-6 offset-md-1">
				<h2><?php echo $page['header']?></h2>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="row">
				<div class="col-md-10 offset-md-1 v-middle post-content">
				    <?php if($page['image']){ ?>
				    <img src="img/blog/<?php echo $page['image']?>" alt="<?php echo ucfirst($page['keyword'])?>">
				    <?php } ?>
					<?php echo $page['text_body']?>
				</div>
				<div class="col-md-10 offset-md-1 v-middle">
					<b>Articoli correlati:</b>
					<?php while($relatedPage=$result->fetch_array(MYSQLI_ASSOC)){ ?>
						<a href="<?php echo $relatedPage['url']?>.html" title="<?php echo $relatedPage['title']?>"><?php echo $relatedPage['title']?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>

	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
					@<?php echo date('Y')?> dicerollers.it | GBBLRD99E24G224G
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
    var _iub = _iub || [];
    _iub.csConfiguration = {"siteId":3780185,"cookiePolicyId":61479398,"lang":"it"};
    </script>
    <script type="text/javascript" src="https://cs.iubenda.com/autoblocking/3780185.js"></script>
    <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
</body>
<?php } ?>