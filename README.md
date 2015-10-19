# Unofficial API for infogreffe.fr
This PHP class allows you to query data on [infogreffe.fr](https://www.infogreffe.fr/societes/).

##How to use it
###Class
You import *classes/Infogreffe.php* into your PHP code and then use the ```Infogreffe::search``` to retrieve data.

###CLI
There is a basic commandline interface that you can use:

    php cli.php search "Bygmalion"
    php cli.php search 13000545700010
