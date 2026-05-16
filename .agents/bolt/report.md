# Bolt - Performance Report

## Optimization: N+1 Query Resolution and SARGable Date Queries

### Identified Issues
1. **N+1 Queries in Process Details**: The `ProcessoRepository::findById` method was eager loading `tipoProcesso` and `interessado`, but not the users associated with each movement in the history. This resulted in one additional query per movement record displayed in the view.
2. **Non-SARGable Query in Process Number Generation**: `ProcessoRepository::getLatestProcessNumber` used `whereYear('data_abertura', $year)`. This function prevents the database from using indexes on the `data_abertura` column.

### Actions Taken
- Modified `ProcessoRepository::findById` to include `movimentacoes.user` in the `with()` clause.
- Refactored `ProcessoRepository::getLatestProcessNumber` to use range comparisons (`>=` and `<`) instead of `whereYear`.
- Created a performance test suite `Tests\Feature\PerformanceOptimizationTest` to verify query counts and SQL structure.

### Results
- **N+1 Fixed**: Query count for loading a process with 10 movements reduced from 14 to 5.
- **Query Optimization**: SQL for latest process number now allows index usage on `data_abertura`.

## Learning
- Nested eager loading in Laravel (e.g., `movimentacoes.user`) is a powerful way to solve deep N+1 issues.
- Always prefer range comparisons over date functions on indexed columns to maintain SARGability.
- SQLite translates `whereYear` to `strftime('%Y', ...)` which is definitely not SARGable.
