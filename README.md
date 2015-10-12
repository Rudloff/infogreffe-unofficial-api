# Unofficial API for infogreffe.fr
This PHP class allows you to query data on [infogreffe.fr](https://www.infogreffe.fr/societes/).

##How to use it
###Class
You import *classes/Infogreffe.php* into your PHP code and then use the ```Infogreffe::searchByName``` and ```Infogreffe::searchBySIRET``` to retrieve data.

###CLI
There is a basic commandline interface that you can use.

Search by SIRET:

    php cli.php search:siret 13000545700010

Search by name:

    php cli.php search:name "Bygmalion"
