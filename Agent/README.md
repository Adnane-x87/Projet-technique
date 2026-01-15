### Automated Testing Strategy (Job-Skills)

**Command:**

* Run the tests with: `php artisan test`

**Guiding Principles:**
1. **Use of Seeders:** Tests must use existing data loaded by the seeders (`UserSeeder`, `SkillsSeeder`, `EmploiSeeder`) rather than creating dummy data ("factories") on the fly. Use methods like `Emploi::first()` or `Skills::whereHas('emplois')->first()`.

2. **Target:** Validate the business logic encapsulated in the core services:

* `EmploiService`: Job posting management, search, skills filtering, and CRUD operations.

* `SkillsService`: Technical skills management.

3. **Location:**

* Unit Tests: `tests/Unit` (e.g., `EmploiServiceTest.php`)

4. **Isolation:** Use the `DatabaseTransactions` trait to ensure that tests do not permanently modify the seeders' dataset.

**Objective:**
Ensure that the Job-Skills platform's business logic functions predictably on the application's "real" dataset, while ensuring that the relationships between Jobs and Skills are correctly maintained.