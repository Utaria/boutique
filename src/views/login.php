<div class="main-container login-container" data-version="<?= VERSION ?>">
	<div class="left-bar">
		<a href="/" title="Revenir à la page d'accueil"><div class="symbol-back"></div></a>
	</div>
	
	<div class="sign-in-container">
		<i class="fa fa-unlock-alt"></i>
		<span class="title">Connexion</span>

		<div class="open-container">
			<h1>Connexion</h1>

			<p style="text-align:justify">Tapez ci-dessous votre pseudo Minecraft et le mot de passe que vous utilisez pour vous connecter sur le serveur.</p>

			<form method="POST" id="login-form" action="?p=/login/process">
				<div class="input">
					<input type="text" name="pseudo" class="autofocus" placeholder="PSEUDONYME" autocomplete="off">
				</div>
				<div class="input">
					<input type="password" name="password" placeholder="MOT DE PASSE">
				</div>

				<div class="submit input">
					<span class="error-label">Un ou plusieurs champs sont incorrects.</span>
					<button type="submit"></button>
				</div>
			</form>
		</div>
	</div>
	<div class="sign-up-container">
		<i class="fa fa-user-plus"></i>
		<span class="title">Jamais connecté ?</span>

		<div class="open-container">
			<h1>Jamais connecté ?</h1>

			<div class="contenu">
				Connectez-vous une première fois sur notre serveur à l'adresse <span class="ip-server">mc.utaria.fr</span> et enregistrez-vous.

				<img src="/img/connexion.png" alt="Connexion au serveur Utaria" style="width:100%;height:auto">

				<br>
				Vous pouvez faire un don ici : <a href="https://paypal.me/UtariaMC" target="_blank" style="color:#3498db" title="Faire un don sur Paypal">Faire un don sur Paypal</a>
			</div>
		</div>
	</div>

	<div class="clear"></div>
</div>

<span class="secure-form"><i class="fa fa-lock"></i> Fomulaire sécurisé</span>