<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="fr" />
		<meta name="description" content="<?php echo Page::$description; ?>" />
		<meta http-equiv="content-language" content="fr" />
		<meta name="robots" content="<?php echo Page::$robots; ?>" />
		<meta name="language" content="fr" />

		<title><?php echo str_replace(array(' '), ' ', Page::$titre); ?></title>

		<?php $view['vitesse']->requireResource('@ZcoCoreBundle/Resources/public/css/design.css') ?>
		<?php $view['vitesse']->requireResource('@ZcoCoreBundle/Resources/public/js/design.js') ?>
        
		<?php foreach ($view['vitesse']->stylesheets() as $assetUrl): ?>
		    <link rel="stylesheet" href="<?php echo $assetUrl ?>" media="screen" type="text/css" />
		<?php endforeach ?>
    	
		<?php foreach ($view['vitesse']->javascripts(array('mootools', 'mootools-more')) as $assetUrl): ?>
		    <script type="text/javascript" src="<?php echo $assetUrl ?>"></script>
		<?php endforeach ?>
		
		<?php echo $view['vitesse']->renderFeeds() ?>
		
		<link rel="icon" type="image/png" href="/favicon.png" />
		<link rel="start" title="zCorrecteurs.fr - Correction de tutoriels pour le Site du Zéro" href="/" />
	</head>

	<body>
		<div id="header">
			<div id="header-oreilles">
				<a href="/" title="zCorrecteurs.fr - Corrections orthographiques, forum, blog, quiz, et bien plus encore !">
					zCorrecteurs.fr - Corrections orthographiques, forum, blog, quiz, et bien plus encore !
				</a>
			</div>
			<div id="header-zcorrecteurs">
				<a href="/" title="zCorrecteurs.fr - Corrections orthographiques, forum, blog, quiz, et bien plus encore !">
					zCorrecteurs.fr - Corrections orthographiques, forum, blog, quiz, et bien plus encore !
				</a>
			</div>
		</div> <!-- /header -->
		
		<div class="navbar navbar-static">
			<div class="navbar-inner">
				<div class="container">
					<?php echo $view['ui']->speedbarre('bootstrap') ?>
					<?php echo $view['ui']->speedbarreRight('bootstrap') ?>
		    	</div>
			</div>
		</div> <!-- /navbar -->
		
		<div class="container">
			<?php echo $view->render('::layouts/flashes.html.php', compact('maintenance')) ?>

			<?php $view['slots']->output('_content') ?>
		</div> <!-- /container -->

		<div id="footer">
			<div class="left">
			    <span>Site fièrement édité par</span>
				<a href="http://www.corrigraphie.org" title="Ce site est hébergé et édité par l’association Corrigraphie.">Corrigraphie</a>
			</div>
			
			<div class="center">
				<p><a href="#" title="Remonter en haut de page" id="toplink">Haut de page</a></p>
					
				<?php echo $view['ui']->footer(1); ?>
				<?php echo $view['ui']->footer(2); ?>
			</div>
		</div>

		<?php foreach ($view['vitesse']->javascripts() as $assetUrl): ?>
		    <script type="text/javascript" src="<?php echo $assetUrl ?>"></script>
		<?php endforeach ?>
		<?php echo $view['javelin']->renderHTMLFooter() ?>
	</body>
</html>