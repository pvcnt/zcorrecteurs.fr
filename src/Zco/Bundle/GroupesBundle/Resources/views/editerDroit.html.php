<?php $view->extend('::layouts/default.html.php') ?>

<h1>Modifier un droit</h1>

<form method="post" action="">
	<fieldset>
		<legend>Modifier un droit</legend>
		<div class="send">
			<input type="submit" name="Envoyer" />
		</div>

		<label for="nom">Code du droit : </label>
		<input type="text" name="nom" id="nom" size="40" value="<?php echo htmlspecialchars($InfosDroit['droit_nom']); ?>" />
		<em>Utilisé en interne dans les scripts.</em><br />

		<label for="desc">Nom du droit : </label>
		<input type="text" name="desc" id="desc" size="40" value="<?php echo htmlspecialchars($InfosDroit['droit_description']); ?>" />
		<em>Nom apparaissant sur les pages du site définissant le droit.</em><br />

		<label for="cat">Catégorie : </label>
		<select name="cat" id="cat">
			<?php echo GetListeCategories($InfosDroit['droit_id_categorie']); ?>
		</select>
		<em>Pour un droit réglable par catégorie, ceci doit être la catégorie parente des catégories où le droit peut s'appliquer.</em><br />

		<label for="choix_cat">Droit réglable par catégorie : </label>
		<input type="checkbox" name="choix_cat" id="choix_cat"<?php if($InfosDroit['droit_choix_categorie']) echo ' checked="checked"'; ?> /><br />

		<label for="choix_binaire">Droit pouvant prendre une valeur numérique : </label>
		<input type="checkbox" name="choix_binaire" id="choix_binaire"<?php if(!$InfosDroit['droit_choix_binaire']) echo ' checked="checked"'; ?> /><br /><br />

		<label for="description">Description longue du droit, à l'attention
		des administrateurs (facultatif) :</label><br /><br />
		<?php echo $view->render('::zform.html.php', array('texte' => $InfosDroit['droit_description_longue'])); ?>

		<div class="send">
			<input type="submit" name="Envoyer" />
		</div>
	</fieldset>
</form>