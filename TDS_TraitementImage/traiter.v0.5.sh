#!/bin/bash

#
# AUTEUR : NOËL Jonathan
# 
# COLLABORATEURS : 
#   LAGACHE Jordan, MEYER Nathan, EEROLA Juhani, MUTEBA Jean-Luc, RENARD Arnaud
#
#
# Dans le cadre du Projet d intégration organisé par l EPHEC-Louvain-la-Neuve
# 	et réalisé par des élèves de 3e BAC
#
#
#
# SOURCES
#
# crop.morphology.py
# https://github.com/danvk/oldnyc/blob/master/ocr/tess/crop_morphology.py
# 
#


# VERIFICATION SYSTEME A JOUR
apt-get -y update
apt-get upgrade -y


# VERIFICATION ET INSTALLATION DU DRIVER MYSQL
apt-get install -y mysql-server

# VERIFICATION ET INSTALLATION DU DRIVER TESSERACT-OCR
apt-get install -y tesseract-ocr

# VERIFICATION ET INSTALLATION DU DRIVER CONVERT (ImageMagick)
apt-get install -y imagemagick

# VERIFICATION ET INSTALLATION DU DRIVER FSWEBCAM
apt-get install -y fswebcam

# VERIFICATION ET INSTALLATION DU DRIVER POUR LE SCRIPT DE DECOUPAGE
apt-get install -y python-scipy

# VERIFICATION ET INSTALLATION DU DRIVER POUR LE SCRIPT DE DECOUPAGE
apt-get install -y python-imaging

# VERIFICATION ET INSTALLATION DU DRIVER CV2
apt-get install -y python-opencv

# MISE A NIVEAU DU SYSTEME
apt-get -y update
apt-get -y upgrade


# LE NOM DE LA MACHINE = LE NOM DU CLIENT
hostname=$(uname -n)


#
# BOUCLE DE TRAITEMENT
#
while true
do
	# GENERER LA DATE	
	DATE=$(date +%d-%m-%Y-%H-%M)


	# CONVERTIR L'IMAGE EN IMAGE TRAITABLE
	# Premier essai fonctionnel avec erreur = 1
	# convert imageATraiter.tif -resize 2000 -threshold 90% -density 300 -depth 8 -negate -strip -background white -alpha off out-$DATE.tif

	# Second essai avec crop.py et erreur = 0
	./crop_morphology.py test.jpg
	imageATraiter=$(base64 test.crop.png)
	convert test.crop.png -resize 2000 -threshold 33% -density 300 -depth 8 -negate -strip -background white -alpha off out-$DATE.tif


	# GENERATION DU NUMERO DE COMPTEUR
	# Premier essai fonctionnel
	# tesseract out-$DATE.tif output-$DATE -c tessedit_char_whitelist=0123456789 -psm 6;

	# Second essai avec erreur = 0
	tesseract out-$DATE.tif output-$DATE -c tessedit_char_whitelist=0123456789 -psm 12;

	rmSpaceOutput=$(cat output-$DATE.txt | tr -d ' ')
	echo "$rmSpaceOutput" > output-$DATE.txt
	

	# NOTER LE NOM DU LOCATAIRE (ON NOMME LA MACHINE A SON NOM)	
	echo " $hostname" >> output-$DATE.txt
	# NOTER LA DATE-HEURE DE LA PRISE
	echo " $DATE" >> output-$DATE.txt

	# GESTION DES ESPACES INUTILES / FACILITER POUR INSERT BDD
	contenuFichier="$(cat output-$DATE.txt)"
	contenuFichierSansEspaces="$(echo -e $contenuFichier | tr -d '\v')"
	echo $contenuFichierSansEspaces > output-$DATE.txt
	cat output-$DATE.txt

	# INSERTION DES DONNEES DANS LA BDD
	# ??????????????? QUID DE LA BDD VPS ???????????????
	inputfile="output-$DATE.txt"
	cat $inputfile | while read compteur nom heure; do
		echo "INSERT INTO Control (value, user, time, picture) VALUES ('$compteur', '$nom', '$heure', '$imageATraiter');"
	done | mysql -h 137.74.172.37 -ujon -proot emonitor;

	# DEPLACEMENT DES FICHIERS DANS UN DOSSIER D'ARCHIVAGE
	mv output-$DATE.txt Archives/$DATE.txt
	mv out-$DATE.tif Archives/$DATE.tif

	# RELANCER LA PRISE TOUTES LES MINUTES
	sleep 60
	#
done