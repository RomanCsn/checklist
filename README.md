# Application Checklist

## Vue d'ensemble

Cette application de gestion de checklists est construite avec Symfony. Elle permet aux utilisateurs de créer, gérer et suivre des checklists pour divers projets.

## Fonctionnalités

- Authentification et inscription des utilisateurs
- Création et gestion de checklists de projet
- Ajout et suivi des éléments de checklist
- Design réactif utilisant Tailwind CSS

## Installation

Clonez le dépôt :
   ```bash 
   git clone https://github.com/RomanCsn/checklist.git
   cd checklist
   ```

## BDD
L'application utilise une base de données SQLite. 
    [...]/var/data.db

#### Réflexion sur la sauvegarde potentielle

Il est envisageable de mettre en place un cron qui exécutera un script pour automatiser la sauvegarde du projet. Ce script pourrait utiliser : git add . , git commit -m "backup xxx" , et git push pour enregistrer et pousser les modifications vers un dépôt à intervalles réguliers.