# Performance Report - Bolt Agent

## Identified Improvements

### 1. Database Query Optimization
- **Issue**: `ProcessoRepository::getLatestProcessNumber` was using `whereYear('data_abertura', $year)`. This function prevents the database from using an index on the `data_abertura` column.
- **Solution**: Replaced `whereYear` with a `whereBetween` range query: `whereBetween('data_abertura', ["{$year}-01-01 00:00:00", "{$year}-12-31 23:59:59"])`. This allows the database to perform an index range scan.

### 2. Missing Indices
- **Issue**: Several columns used for filtering and ordering lacked indices, which would lead to full table scans as the dataset grows.
- **Solution**: Added indices to the following columns:
    - `processos`: `status`, `data_abertura`, `created_at`
    - `tipos_processos`: `is_active`
    - `users`: `is_active`

## Impact
- Faster retrieval of the latest process number when generating new processes.
- Improved performance for the Process list view (filtering by status and sorting by creation date).
- More efficient dropdown population for active process types and users.
