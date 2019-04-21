<div class="main-container" data-version="<?= VERSION; ?>">
	<div class="left-bar">
		<a href="/boutique/choix-produit/<?= $d->product->payment_mean; ?>" title="Revenir au choix du produit"><div class="symbol-back"></div></a>

		<div class="user-info">
			<div class="coins">
				<span><?= getUser()->coins ?> coin<?= ((getUser()->coins > 1) ? "s" : "") ?></span>
			</div>
			<span class="playername"><?= getUser()->playername; ?></span>
		</div>
	</div>

	<div class="product-confirmation">
		<h1 class="title">Vous avez choisi</h1>

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

		<div class="advantages-box">
			<h3>Ce que vous pourrez acheter après cet l'achat :</h3>
			
			<?php if (!empty($d->advProducts["home"])): $am = $d->advProducts["home"]->amount; ?>
				<div class="col home">
					<span><b><?= $am ?></b> maison<?= ($am > 1) ? "s" : "" ?> en plus pour 2 mois</span>
				</div>
			<?php endif; ?>

			<?php if (!empty($d->advProducts["claim"])): $am = $d->advProducts["claim"]->amount; ?>
				<div class="col claim">
					<span><b><?= $am ?></b> claim<?= ($am > 1) ? "s" : "" ?> supplémentaire<?= ($am > 1) ? "s" : "" ?> à vie<sup>*</sup></span>
				</div>
			<?php endif; ?>

			<?php if (!empty($d->advProducts["tpa"])): $am = $d->advProducts["tpa"]->amount; ?>
				<div class="col tpa">
					<span><b><?= $am ?></b> téléportation<?= ($am > 1) ? "s" : "" ?> en plus pour 2 mois</span>
				</div>
			<?php endif; ?>
		</div>

		<?php if (!empty($d->advProducts["claim"])): ?>
			<div class="sup-info" style="position:relative;width:70%;float:left;margin-top:20px">
				<p>
					* Sous réserve de protéger les zones dans les deux mois suivant l'achat et de ne pas les supprimer.
				</p>
			</div>
		<?php endif; ?>

		<a href="<?= $d->payment->link; ?>" title="Payer le produit"><div class="confirm-button button-<?= $d->product->payment_mean ?>"><span>Payer</span></div></a>

	</div>

</div>

<span class="secure-form"><i class="fa fa-lock"></i> Fomulaire sécurisé</span>