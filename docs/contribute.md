![Logo ToDo-Co](/public/img/logo.png)
# Contribuer au projet  

  ## Sommaire  
1. Généralités
2. Méthodologie Gitflow
3. Qualité du code
    1. Codacy
    2. Tests unitaires
    3. Tests fonctionnels
4. Performance  
  
  ### 1 - Généralités  
Dans le but de maintenir le code à un haut niveau de qualité et de garantir la maintenabilité de celui-ci voici quelques règles et conduites à respecter :
- Respecter la structure de fichier de Symfony, les fichiers publiques comme le css, le javascript ou les images doivent être dans le dossier public/. La configuration dans le dossier config/, les vues dans le dossier templates/ et les sources dans src/leDossierAdapté.
- Les contrôleurs doivent contenir le minimum de logique métier et respecter le Principe de Responsabilité Unique.
- Toujours documenter ses commits avec le plus de précision et de concision possible.
-	Ne pas laisser de code superflu.
- Commenter le code seulement si le nom des fichiers/fonctions ne suffit pas à en expliciter le but.
- Respecter les standards de sécurité [PSR-1](https://www.php-fig.org/psr/psr-1/) et [PSR-4](https://www.php-fig.org/psr/psr-4/)  
  
### 2 - Méthodologie
#### A. Installation du projet
   
   L’installation du projet est détaillée dans le fichier README.md à la racine du projet ([Voir ici](https://github.com/ClementThuet/ToDo-Co/blob/master/README.md))
#### B. Utilisation de Git
   Veuillez adopter la méthodologie gitflow, pour cela positionner vous sur la branche feature/featureToDev et récupérer la dernière version stable du projet.  
   
>git branch feature/featureToDev  

Vous pouvez maintenant développer la fonctionnalité souhaitée.    

Lorsque vous avez terminé, écrivez un message de commit explicite et pushez votre travail sur le dépôt distant feature/featureToDev  

>git add .  
>git commit  
>git push origin feature/featureToDev  

Ouvrez ensuite une pullRequest basée sur la branche develop pour que la nouvelle fonctionnalité soit examinée avant d’être incorporé.
Une fois cela fait, vous pouvez supprimer la branche locale correspondant à cette fonctionnalité. 
Plus d’explication sur la méthodologie [ici](https://blog.nathanaelcherrier.com/fr/gitflow-la-methodologie-et-la-pratique/)

### 3 - Qualité du code
#### A. Tests unitaires  
Les tests unitaires permettent de tester le bon fonctionnement d'une partie précise d'un programme et de s'assurer que le comportement d'une application est correct.  
Dans notre cas ils servent principalement à tester le bon fonctionnement de nos entités et que nos différentes pages sont bien accessibles.  
On utilise PhpUnit pour les mettre en place, les tests doivent être écris dans le dossier tests/. Le nom des fichiers doit se terminer par Test et le nom des méthodes doit commencer par test.
On lance ensuite les tests avec la commande :  
>vendor/bin/phpunit  

Plus d’informations [ici](https://phpunit.readthedocs.io/fr/latest/writing-tests-for-phpunit.html)

#### B. Tests fonctionnelles
Les tests fonctionnels sont les tests qui servent à tester automatiquement toutes les fonctionnalités de l’application, ils vont plus loin que les tests unitaires, par exemple ils permettent de tester l’authentification d’un utilisateur.  
On utilise ici Behat, il faut tout d’abord écrire les scénarios des tests dans le dossier features/ à la racine du projet puis les tests en eux même dans le fichier features/bootstrap/FeatureContext.php  
On lance ensuite les tests avec la commande  
>vendor/bin/behat  

Vous pouvez vous réferer à la [documentation](http://behat.org/en/latest/user_guide.html)

#### C. Utilisation de Codacy
Codacy identifie automatiquement les erreurs de style dans le code, les portions inutilisées et les problèmes de sécurité.  
Le résultat est accessible à cette [adresse](https://app.codacy.com/manual/ClementThuet/ToDo-Co/dashboard).   
Il faut veiller à ce que la note de qualité reste satisfaisante en corrigeant les éventuelles erreurs ajoutées au fil du temps.

### 4 - Performance
Lors de l’ajout de chaque fonctionnalité il est recommandé de d’effectuer une analyse Blackfire globale et de la comparer avec l’analyse de la version précédente afin de vérifier comment les changements apportés impactent la performance de l’application.  
L’outil permet d’analyser avec précision chaque étape afin de voir quelle partie monopolise le plus de ressource.  
Par exemple, si l'on observe l'analyse de la création d'une tâche on voit que la méthode createAction requiert 26.43% des ressources de l’action.
