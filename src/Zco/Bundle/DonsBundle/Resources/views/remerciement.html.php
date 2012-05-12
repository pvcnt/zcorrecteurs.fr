<?php $view->extend('ZcoDonsBundle::layout.html.php') ?>

<div style="float: right; width: 340px;">
    <?php echo $view->render('ZcoDonsBundle::_menu.html.php', array('donner' => false)) ?>
</div>

<div style="margin-right: 380px;">
    <h1>Merci de nous soutenir !</h1>

    <p><a href="/dons/">&larr; Retour à l'accueil des dons</a></p>

    <p style="text-align: justify; margin-top: 15px;">
        Vous venez de faire un don et toute l’équipe des bénévoles vous en remercie chaleureusement !
        Sans vos dons, le site aurait du mal à se développer, c’est pourquoi ils sont essentiels pour 
        nous. Vous nous aidez ainsi à poursuivre notre mission et pérenniser notre activité.
    </p>

    <p style="text-align: justify; margin-top: 15px;">
        Pour toute question suite à votre don, pour apparaître dans 
        <a href="/dons/#donateurs">la liste des donateurs</a>
		ou bien pour recevoir un reçu vous permettant de bénéficier 
		d’<a href="deduction-fiscale.html">une déduction fiscale</a>
        n’hésitez pas à <a href="<?php echo $view['router']->generate('zco_about_contact', array('objet' => 'Don')) ?>">prendre contact avec nous</a>.
    </p>
</div>