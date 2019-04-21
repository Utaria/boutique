<!DOCTYPE html>
<html>
<head>
	<meta name="description" content="Utaria, les serveurs de demain ! Marre du survie classique de Minecraft ? Venez tester notre survie UNIQUE sur mc.utaria.fr !">
	<meta name="keywords" content="minecraft,serveur minecraft,serveur,survie unique,unique,original,nouveau,survival">
	<meta name="author" content="Utaria">
	<meta name="dcterms.rightsHolder" content="utaria">
	<meta name="Revisit-After" content="2 days">
	<meta name="Rating" content="general">
	<meta name="language" content="fr-FR" />
	<meta name="robots" content="all" />
	<meta charset="UTF-8">

	<title>Utaria | La boutique officielle en ligne</title>

	<meta name="viewport" content="width=device-width, initial-scale = 1, user-scalable = no">
	
	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@Utaria_FR">
	<meta name="twitter:title" content="Utaria, les serveurs de demain !">
	<meta name="twitter:description" content="Utaria, un serveur Minecraft innovant.">
	<meta property="og:title" content="Utaria">
	<meta property="og:type" content="website">
	<meta property="og:url" content="https://utaria.fr/">

	<link rel="icon" type="image/png" href="//utaria.fr/img/favicon.png" />

	<link href="https://fonts.googleapis.com/css?family=Lato|Open+Sans:300,400,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/main.css">
	<link rel="stylesheet" type="text/css" href="/css/awesome.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">

	<?php if(isset($GLOBALS["HEAD_SCRIPT"])): echo $GLOBALS["HEAD_SCRIPT"]; endif; ?>
</head>
<body class="<?= $this->page ?>-page">

	<section id="main">
		<?= $content_for_layout; ?>
	</section>

	<?php if ($config['noel']): ?><script type="text/javascript" src="/js/snow.js"></script><?php endif; ?>
	<script type="text/javascript" src="/js/app.js"></script>
	<?php if ($this->page == "login"): ?><script type="text/javascript" src="/js/login.js"></script><?php endif; ?>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-87706617-2', 'auto');
	  ga('send', 'pageview');
	</script>
</body>
</html>
