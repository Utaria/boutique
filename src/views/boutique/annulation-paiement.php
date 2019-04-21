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
		<div class="error-message">
			<h2>Paiement annulé. Une erreur s'est produite.</h2>
			<br/><br/>
			<?php if(!empty($_GET["reason"])): ?><p style="text-align:center">Raison de l'annulation : <?= $_GET["reason"]; ?></p><?php endif; ?>
		</div>
	</div>

</div>

<span class="secure-form"><i class="fa fa-lock"></i> Fomulaire sécurisé</span>