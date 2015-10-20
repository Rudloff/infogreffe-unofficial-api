# Unofficial API for infogreffe.fr
This PHP class allows you to query data on [infogreffe.fr](https://www.infogreffe.fr/societes/).

##How to use it
###Class
You import *classes/Infogreffe.php* into your PHP code and then use the ```Infogreffe::search``` to retrieve data.

###CLI
There is a basic commandline interface that you can use:

    php cli.php search "Bygmalion"
    php cli.php search 13000545700010

##How does it work?
It uses an undocumented infogreffe.fr REST API.
We are willing to switch to the [Infogreffe open data API](https://datainfogreffe.fr/api/v1/documentation) if and when it includes the same features.
