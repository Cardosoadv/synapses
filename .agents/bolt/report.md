# Bolt Performance Report

## Implemented Improvements

### 1. N+1 Query Fix in Process Details
- **Issue:** The process details view (`processos.show`) was performing a separate query for each user associated with a process movement when rendering the timeline.
- **Solution:** Modified `App\Repositories\ProcessoRepository::findById` to eager load `movimentacoes.user`.
- **Impact:** Reduces the number of queries on the process details page from `2 + N` (where N is the number of movements) to a constant `4` queries regardless of movement history size.

### 2. SARGable Query for Process Number Generation
- **Issue:** The `getLatestProcessNumber` method used `whereYear('data_abertura', $year)`, which prevents the database from using indices on the `data_abertura` column.
- **Solution:** Replaced `whereYear` with a direct range comparison: `where('data_abertura', '>=', "{$year}-01-01 00:00:00")->where('data_abertura', '<=', "{$year}-12-31 23:59:59")`.
- **Impact:** Allows the database to perform an index scan/seek on the `data_abertura` column, significantly improving performance as the `processos` table grows.

### 3. Database Indexing for Critical Columns
- **Action:** Created a new migration adding indices to frequently filtered and ordered columns.
- **Indices Added:**
    - `processos`: `status`, `data_abertura`, `created_at`
    - `tipos_processos`: `is_active`
    - `users`: `is_active`
- **Impact:**
    - Faster filtering in the process list by status.
    - Improved performance for the default ordering by `created_at`.
    - Optimized lookups for active process types and users in dropdowns and validation.

## Technical Details
- **Migration:** `2026_05_15_072540_add_performance_indices.php`
- **Modified Files:**
    - `app/Repositories/ProcessoRepository.php`
    - `tests/Feature/ExampleTest.php` (Test maintenance)
