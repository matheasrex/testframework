<div class="hero-unit">
	<h1>Framework test</h1>
	<h2>Requirement:</h2>
	<p>"
		Adott egy captcha-val védett login oldal. Itt tudnak a userek bejelentkezni. A bejelentkezés után pár oldal kell elérhető legyen egy minimális jogosultság kezeléssel: legyen egy user, aki csak az egyik oldalt éri el, legyen egy másik, aki csak egy másik oldalt ér el, és legyen egy harmadik user, aki mindkét oldalt meg tudja tekinteni. A user és jogosultság kezelés backendje valamilyen adatbázis legyen, továbbá legyen valamilyen template kezelés. Ezenkívül fontos, hogy a session kezelés működjön több szerveres környezetben is, valamint működjön saját hibakezelő, ami hiba esetén (sima hiba, exception, fatal error) elküldi a hibákat a kiszolgálás végeztével egy megadott email címre. Legyen unit tesztelve"
	</p>
	<? if (isset($userData) && $userData): ?>
		You Are Logged in!
	<? else: ?>
		<p><a href="/login/" class="btn btn-primary btn-large">Go and try this captcha login &raquo;</a></p>
	<? endif ?>
</div>