function loadForm() {
	var container  = document.querySelector(".login-container");
	var bg         = container.querySelector(".sign-in-background");

	var signin     = container.querySelector(".sign-in-container");
	var signup     = container.querySelector(".sign-up-container");
	var form       = container.querySelector("#login-form");
	var submit     = container.querySelector(".input.submit");
	var errorLabel = container.querySelector("span.error-label");

	var lastTarget = null;
	container.onmousemove = function(e) {
		var target = e.srcElement || e.target;

		if (target.tagName ==    "I") target = target.parentNode;
		if (target.tagName == "SPAN") target = target.parentNode;

		if (lastTarget == target) {
			lastTarget = target;
			return;
		}

		if (target == signin) {
			signin.classList.add("focus-in");
			signin.classList.remove("focus-out");

			signup.classList.add("focus-out");
			signup.classList.remove("focus-in");
		} else if (target == signup) {
			signin.classList.add("focus-out");
			signin.classList.remove("focus-in");

			signup.classList.add("focus-in");
			signup.classList.remove("focus-out");
		} else {
			signin.classList.remove("focus-out");
			signin.classList.remove("focus-out");

			signin.classList.remove("focus-in");
			signup.classList.remove("focus-in");
		}

		lastTarget = target;
	}

	container.onmouseleave = function() {
		signin.classList.remove("focus-out");
		signin.classList.remove("focus-out");

		signup.classList.remove("focus-in");
		signup.classList.remove("focus-in");

		lastTarget = null;
	}

	signin.onclick = function(event) {
		var target = event.srcElement || event.target || event.toElement;

		if (target != null && target.nodeName == "INPUT") return false;

		signin.classList.add("opened");
		signin.classList.remove("closed");

		signup.classList.add("closed");
		signup.classList.remove("opened");

		setTimeout(function() {
			signin.querySelector(".autofocus").focus();
		}, 500);
	}
	signup.onclick = function() {
		signin.classList.add("closed");
		signin.classList.remove("opened");

		signup.classList.add("opened");
		signup.classList.remove("closed");
	}

	/*   Gestion du formulaire   */
	form.onsubmit = function(e) {
		e.preventDefault();
		// Si le formulaire est déjà en cours, on ne fait rien
		if (submit.classList.contains("waiting")) return;

		submit.classList.add("waiting");
		errorLabel.style.display = "none";

		var pseudo   = this.querySelector("input[name='pseudo']").value;
		var password = this.querySelector("input[name='password']").value;

		// On envoie la requête avec les valeurs au controlleur
		var xhr = new XMLHttpRequest();
		xhr.open("POST", this.getAttribute("action"), true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function(e) {
			if (xhr.readyState == 4 && xhr.status == 200) {
				var state = xhr.responseText;

				submit.classList.remove("waiting");

				if (state == "true") {
					submit.classList.add("success");
					setTimeout(function() { window.location.href = "/boutique/choix-moyen-paiement" }, 1000);
				} else {
					submit.classList.add("error");
					errorLabel.style.display = "block";
					setTimeout(function() { submit.classList.remove("error"); }, 5000);
				}
			}
		}

 		xhr.send("pseudo=" + pseudo + "&password=" + password); 


		return false;
	}
}

window.onload = loadForm;