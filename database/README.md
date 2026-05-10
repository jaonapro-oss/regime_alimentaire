# Database Setup

## Quick Setup

### Option 1: Import SQL File
```bash
mysql -u root -p < database/database_complete.sql
```

### Option 2: Use Migrations
```bash
# Run migrations
php spark migrate

# Run seeders
php spark db:seed UserSeeder
php spark db:seed RegimeSeeder
php spark db:seed ActivitySeeder
php spark db:seed CodeSeeder
```

## Test Users

| Email | Password | Wallet Balance |
|-------|----------|----------------|
| alice@test.com | password123 | 50,000 Ar |
| bob@test.com | password123 | 25,000 Ar (Gold) |
| charlie@test.com | password123 | 0 Ar |
| admin@test.com | admin123 | 100,000 Ar |

## Sample Wallet Codes

Run this query to see unused codes:
```sql
SELECT code, montant FROM codes WHERE is_used = 0 LIMIT 5;
```

## Database Info

- **Name:** regime_alimentaire
- **Tables:** 8 (users, health_info, regimes, activities, objectives, wallet, codes, subscriptions)
- **Test Data:** 4 users, 5 regimes, 5 activities, 15 wallet codes

Last updated: 2026-05-10
