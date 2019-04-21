<div class="main-container" data-version="<?= VERSION; ?>">
	<div class="left-bar">
		<a href="//boutique.utaria.fr/boutique/choix-produit/paysafecard" title="Retour"><div class="symbol-back"></div></a>

		<div class="user-info">
			<div class="coins">
				<span><?= getUser()->coins ?> coin<?= ((getUser()->coins > 1) ? "s" : "") ?></span>
			</div>
			<span class="playername"><?= getUser()->playername; ?></span>
		</div>
	</div>

	<div class="product-confirmation enter-paysafecard">
		<h1 class="title">Entrez votre code Paysafecard</h1>
		
		<div class="product-info-box">
			<div class="coins">
				<span><?= $d->product->coins ?></span>
				coins
			</div>
			<div class="price-container">
				<div class="price"><?= number_format(intval($d->product->price), 2); ?></div>
				<div class="currency">EUR TTC</div>
			</div>
		</div>

		<form method="POST" action="">
			<div class="input">
				<label for="email">Votre e-mail</label>
				<input type="email" name="email" placeholder="xxxxxx.xxxxxx@xxxxx.xx" required>
				<div class="clear"></div>
			</div>
			<div class="input codes">
				<label for="paysafe_code_1">Votre code Paysafecard</label>
				<input type="text" name="paysafe_code_1" placeholder="XXXX" maxlength="4" required>
				<input type="text" name="paysafe_code_2" placeholder="XXXX" maxlength="4" required>
				<input type="text" name="paysafe_code_3" placeholder="XXXX" maxlength="4" required>
				<input type="text" name="paysafe_code_4" placeholder="XXXX" maxlength="4" required>
				<div class="clear"></div>
			</div>

			<div class="notif warning">
				<b>Attention !</b> Ce mode de paiement est différé, car nous vérifions manuellement les codes. Vous recevrez vos coins en <b>1 jour ouvré</b> après l'envoi du formulaire (si code correct). Un e-mail informatif vous sera envoyé.
			</div>

			<a href="<?= $d->payment->link; ?>" title="Payer le produit"><button type="submit" class="confirm-button button-<?= $d->product->payment_mean ?>"><span>Envoyer</span></div></a>
		</form>
	</div>
</div>

<span class="secure-form"><i class="fa fa-lock"></i> Fomulaire sécurisé</span>