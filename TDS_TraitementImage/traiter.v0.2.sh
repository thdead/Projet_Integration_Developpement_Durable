#!/bin/bash

#
# AUTEUR : NOËL Jonathan
# 
# COLLABORATEURS : 
#   LAGACHE Jordan, MEYER Nathan, EEROLA Juhani, MUTEBA Jean-Luc, RENARD Arnaud
#
#
# Dans le cadre du Projet d'intégration organisé par l'EPHEC-Louvain-la-Neuve
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

#
# VERIFICATION ET INSTALLATION DU DRIVER MYSQL
#
PKG_MYSQL=$(dpkg-query -l mysql-server)
MYSQL_OK=$(echo $?)

if [ "$MYSQL_OK" = "0" ]
then 
	echo "OK - Le driver MYSQL-SERVER est correctement installé"
else
	echo "NOK - Il faut installer le driver MYSQL-SERVER"
	apt-get install mysql-server
fi


#
# VERIFICATION ET INSTALLATION DU DRIVER TESSERACT-OCR
#
PKG_TESSERACT=$(dpkg-query -l tesseract-ocr)
TESSERACT_OK=$(echo $?)

if [ "$TESSERACT_OK" = "0" ]
then 
	echo "OK - Le driver TESSERACT-OCR est correctement installé"
else
	echo "NOK - Il faut installer le driver TESSERACT-OCR"
	apt-get install tesseract-ocr
fi


#
# VERIFICATION ET INSTALLATION DU DRIVER CONVERT (ImageMagick)
#
PKG_CONVERT=$(dpkg-query -l imagemagick)
CONVERT_OK=$(echo $?)

if [ "$CONVERT_OK" = "0" ]
then 
	echo "OK - Le driver IMAGEMAGICK est correctement installé"
else
	echo "NOK - Il faut installer le driver IMAGEMAGICK"
	apt-get install imagemagick
fi


#
# VERIFICATION ET INSTALLATION DU DRIVER FSWEBCAM
#
PKG_FSWEBCAM=$(dpkg-query -l fswebcam)
FSWEBCAM_OK=$(echo $?)

if [ "$FSWEBCAM_OK" = "0" ]
then 
	echo "OK - Le driver FSWEBCAM est correctement installé"
else
	echo "NOK - Il faut installer le driver FSWEBCAM"
	apt-get install fswebcam
fi


#
# VERIFICATION ET INSTALLATION DU DRIVER POUR LE SCRIPT DE DECOUPAGE
#
PKG_SCIPY=$(dpkg-query -l python-scipy)
SCIPY_OK=$(echo $?)

if [ "$SCIPY_OK" = "0" ]
then 
	echo "OK - Le driver PYTHON_SCIPY est correctement installé"
else
	echo "NOK - Il faut installer le driver PYTHON_SCIPY"
	apt-get install python-scipy
fi


#
# VERIFICATION ET INSTALLATION DU DRIVER POUR LE SCRIPT DE DECOUPAGE
#
PKG_IMAGING=$(dpkg-query -l python-imaging)
IMAGING_OK=$(echo $?)

if [ "$IMAGING_OK" = "0" ]
then 
	echo "OK - Le driver PYTHON_IMAGING est correctement installé"
else
	echo "NOK - Il faut installer le driver PYTHON_IMAGING"
	apt-get install python-imaging
fi


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
		echo "INSERT INTO test (compteur, nom, heure) VALUES ('$compteur', '$nom', '$heure');"
	done | mysql -uroot -ptest test;

	# DEPLACEMENT DES FICHIERS DANS UN DOSSIER D'ARCHIVAGE
	mv output-$DATE.txt Archives/$DATE.txt
	mv out-$DATE.tif Archives/$DATE.tif

	# RELANCER LA PRISE TOUTES LES MINUTES
	sleep 60
	#
done
