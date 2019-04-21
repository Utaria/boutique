<div class="main-container" data-version="<?= VERSION; ?>">
	<div class="left-bar">
		<a href="/boutique/cancel" title="Annuler "><div class="symbol-cancel"></div></a>

		<div class="user-info">
			<div class="coins">
				<span><?= getUser()->coins ?> coin<?= ((getUser()->coins > 1) ? "s" : "") ?></span>
			</div>
			<span class="playername"><?= getUser()->playername; ?></span>
		</div>
	</div>

	<div class="means-of-payment">
		<h1 class="title">Choix du moyen de paiement</h1>

		<a href="/boutique/choix-produit/paypal" title="">
			<div class="mean paypal"><div class="logo"></div></div>
		</a>
		<a href="/boutique/choix-produit/cb" title="">
			<div class="mean cb"><div class="logo"></div></div>
		</a>
		<a href="/boutique/choix-produit/paysafecard" title="">
			<div class="mean paysafecard"><div class="logo"></div></div>
		</a>
		<a href="/boutique/choix-produit/youpass" title="">
			<div class="mean youpass"><div class="logo"></div></div>
		</a>
	</div>
</div>

<span class="secure-form"><i class="fa fa-lock"></i> Fomulaire sécurisé</span>