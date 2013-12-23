cirque
======

Réseau social mobile orienté cirques

_Installation de la base de données:_

requis: mysql

Remplacer les valeurs entre crochets:

``$ mysql -h localhost -u [user] -p[password] < db.sql``

_Arborescence:_
	
	/ios => application iPhone
	/android => application Android
	/models => modèles de l'application web
	/controllers => controleurs de l'application web
	/views => vues de l'application web
	/config => fichiers de configuration de l'application web
	/global => ressources de l'application web (feuille de style, images, JavaScript)
	/lib => bibliothèques externes pour l'application web

_Bundles:_

	membership => cirques et utilisateurs
	location => géolocalisation
	activity => abonnements (follow), notes, commentaires
