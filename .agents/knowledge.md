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
