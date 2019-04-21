<div class="main-container" data-version="<?= VERSION; ?>">
	<div class="left-bar">
		<a href="//boutique.utaria.fr/boutique/choix-moyen-paiement" title="Revenir au choix du moyen de paiement"><div class="symbol-back"></div></a>

		<div class="user-info">
			<div class="coins">
				<span><?= getUser()->coins ?> coin<?= ((getUser()->coins > 1) ? "s" : "") ?></span>
			</div>
			<span class="playername"><?= getUser()->playername; ?></span>
		</div>
	</div>

	<div class="transaction-container">
		<div class="success-message">
			<?php if(!isset($_GET["paysafe"])): ?>
				<h2>
					Paiement terminé.<br /> Merci pour votre confiance, bon jeu !<br>
					Reconnectez-vous sur notre serveur pour recharger vos coins.
				</h2>
			<?php else: ?>
				<h2>
					Code envoyé.<br /> Merci pour votre confiance, bon jeu !<br>
					Merci de patientez le temps que notre équipe valide votre paiement.
				</h2>
			<?php endif; ?>
		</div>
	</div>

</div>

<span class="secure-form"><i class="fa fa-lock"></i> Fomulaire sécurisé</span>