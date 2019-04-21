<div class="main-container" data-version="<?= VERSION; ?>">
	<div class="left-bar">
		<a href="/boutique/choix-moyen-paiement" title="Revenir au choix du moyen de paiement"><div class="symbol-back"></div></a>

		<div class="user-info">
			<div class="coins">
				<span><?= getUser()->coins ?> coin<?= ((getUser()->coins > 1) ? "s" : "") ?></span>
			</div>
			<span class="playername"><?= getUser()->playername; ?></span>
		</div>
	</div>

	<div class="products row-<?= count($d); ?>">

		<?php if(empty($d)): ?>
			<div class="no-product">
				<h2>Aucun produit via ce moyen de paiement</h2>
			</div>
		<?php endif; ?>

		<?php 
		$firstProduct = null;
		foreach ($d as $product): 
			$youpass    = $product->payment_mean == "youpass";
			$reducCoins = 0;
			$reducPrice = 0;

			if ($firstProduct == null) $firstProduct = $product;
			else {
				$reducCoins = $product->coins - ($product->price * $firstProduct->coins / $firstProduct->price);
				$reducPrice = ($product->coins * $firstProduct->price / $firstProduct->coins) - $product->price;
			}
		?>
			<div class="product">
				<a href="/boutique/confirmer-choix-produit/<?= $product->id ?>-<?= $product->coins ?>coins" title="Acheter <?= $product->coins ?> coins à <?= $product->price ?> euros TTC">
					<div class="product-container">
						<div class="coins">
							<span><?= $product->coins ?></span>
							coins

						</div>
						<?php if ($reducCoins > 0): ?>
							<div class="coins reduc">
								<?= $reducCoins; ?> coins
								<span>gratuits !</span>
								<span style="margin-top:12px;font-size:.7em">
									(gain de <?= number_format($reducPrice, 2) ?>€)
								</span>
							</div>
						<?php endif; ?>
						<div class="price-container">
							<div class="price"><?= number_format(intval($product->price), 2); ?></div>
							<div class="currency">EUR TTC</div>
						</div>
					</div>
				</a>
			</div>
		<?php endforeach ?>
	</div>

	<?php if(isset($youpass) && $youpass): ?>
		<span class="warning">Les prix affichés sont variables suivant le pays et le type de code envoyé.</span>
	<?php endif; ?>

</div>

<span class="secure-form"><i class="fa fa-lock"></i> Fomulaire sécurisé</span>