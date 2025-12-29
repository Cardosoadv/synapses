# Knowledge Base

## Antigravity

### Project Overview

- **Name**: Synapses
- **Framework**: CodeIgniter 4
- **Database**: MySQL/PostgreSQL

### Recent Learnings (2025-12-26)

- **Biometric Registration**:

  - The `professionals` table (migration `2025-12-26-103230_CreateRegistrationTables.php`) has been updated to include:
    - `photo` (VARCHAR 255): Path to professional's photo.
    - `fingerprint` (VARCHAR 255): Path to fingerprint file.
    - `signature` (VARCHAR 255): Path to signature file.
  - These columns are nullable.

- **Documentation Updates**:

  - `README.md` was rewritten in Portuguese to detail the Synapses project, its modules (Professionals, Companies, Contracts, SEI! Integration), and system requirements.
  - `Requisitos.md` was revised (v0.0.2) to include Biometric Data collection in RF001 and format the document for better readability.

- **Architectural Patterns (User Management)**:
  - **Dual Entity Pattern**: Users (access) and Employees (HR data) are separate entities joined by `user_id`.
  - **Service Transaction**: Creation/Updates are handled in `FuncionariosService` wrapping both Shield User and Employee Model operations in a single DB transaction to ensure data integrity.
  - **Repository Aggregation**: `FuncionariosRepository` joins `employees`, `users`, and `auth_identities` (for emails) to provide a flat, enriched view to the controller.
