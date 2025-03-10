Pour initialiser correctement la base de données, il faut exécuter les fichiers sql dans l'ordre :
1) Struct_BDD_V2.sql - Crée les tables de la base de données
2) Fonctions supplémentaires.sql - Fonctions annexes, qui peuvent servir dans n'importe quel contexte et qui sont utilisées dans les triggers
3) Trigger SHA256 hash_column.sql - Fonctions et triggers qui permettent la création d'une checksum dans toutes les tables, actualisée uniquement à la création (par trigger) ou à la modification licite via le backend
