#!/bin/bash

hostname=$(uname -n)

while true
do
	# GENERER LA DATE	
	DATE=$(date +%d-%m-%Y-%H-%M)

	# CONVERTIR L'IMAGE EN IMAGE TRAITABLE
	convert test.jpg -resize 2000 -threshold 90% -density 300 -depth 8 -negate -strip -background white -alpha off out-$DATE.tif
	# GENERATION DU NUMERO DE COMPTEUR
	tesseract out-$DATE.tif output-$DATE -c tessedit_char_whitelist=0123456789 -psm 6;

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
	sleep 10
done
