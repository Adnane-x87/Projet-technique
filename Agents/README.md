### Stratégie de Tests Automatisés (Job-Skills)

**Commande :**
*   Exécuter les tests avec : `php artisan test`

**Principes Directeurs :**
1.  **Exploitation des Seeders :** Les tests doivent impérativement utiliser les données existantes chargées par les seeders (`UserSeeder`, `SkillsSeeder`, `EmploiSeeder`) plutôt que de créer des données fictives ("factories") à la volée. Utiliser des méthodes comme `Emploi::first()` ou `Skills::whereHas('emplois')->first()`.
2.  **Cible :** Valider la logique métier encapsulée dans les services principaux :
    *   `EmploiService` : Gestion des offres d'emploi, recherche, filtrage par compétences et CRUD.
    *   `SkillsService` : Gestion des compétences techniques.
3.  **Localisation :**
    *   Tests Unitaires : `tests/Unit` (ex: `EmploiServiceTest.php`)
4.  **Isolation :** Utiliser le trait `DatabaseTransactions` pour garantir que les tests ne modifient pas de manière permanente le jeu de données des seeders.

**Objectif :**
Garantir que la logique métier de la plateforme Job-Skills fonctionne de manière prévisible sur le jeu de données "réel" de l'application, tout en s'assurant que les relations entre les Emplois et les Skills sont correctement maintenues.
