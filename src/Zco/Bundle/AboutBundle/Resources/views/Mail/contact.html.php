<p>Bonjour cher administrateur,</p>

<p>
	Un visiteur vous a contacté via le formulaire de contact. Voici son message,
	ainsi que quelques informations d'identification.
</p>

<ul>
	<?php if ($contact->id): ?>
	<li>Message envoyé par <a href="<?php echo $view['router']->generate('zco_user_profile', array('id' => $contact->id, 'slug' => rewrite($contact->pseudo)), true) ?>"><?php echo htmlspecialchars($contact->pseudo) ?></a>.</li>
	<?php else: ?>
	<li>Ce message a été envoyé par un utilisateur non enregistré.</li>
	<?php endif; ?>
	<li>Nom de la personne : <?php echo $contact->nom ? htmlspecialchars($contact->nom) : 'non renseigné' ?>.</li>
	<li>Adresse mail indiquée : <a href="mailto: <?php echo htmlspecialchars($contact->courriel) ?>"><?php echo htmlspecialchars($contact->courriel) ?></a>.</li>
	<li>Adresse IP : <a href="<?php echo URL_SITE ?>/ips/analyser.html?ip=<?php echo $ip ?>"><?php echo $ip ?></a>.</li>
</ul>

<hr />

<p><?php echo nl2br(htmlspecialchars($contact->message)) ?></p>
