# Steam Euro / Hour calculator

Dit is een web app die ik gemaakt heb waarmee je kan uitrekenen hoeveel euro je per uur hebt betaald voor een spel dat je gespeeld hebt op Steam ( Steam is een software platform voor games, denk aan een itunes voor games) .

Deze app werkt samen met zowel de ‘Steam web api’ als de ‘Steam community api’ en is gemaakt in angular.

Om de app zelf uit te proberen klik op de volgende link.

http://willemhuijben.nl/steam

Als je zelf geen steamID hebt is hier een voorbeeld ID:

76561198011289296

Na het bekijken van je eigen steam library is het ook mogelijk om die van je vrienden te bekijken.
Dit kan via de "Friendlist" button.

Om zo min mogelijk requests naar de ‘Steam web api’ te sturen is het systeem zo gebouwt dat alle gegevens eerst in mijn eigen database opgeslagen worden en dan pas getoond worden.

Naast de "library" en "friendlist" functies bestaat er ook een recommendation feature.
Deze berekent op basis van de overeenkomstigheid van spellen die je met andere gebruikers deelt en speeltijd die hierin zit wat voor jou de beste game zou kunnen zijn om te kopen.



