# Sentinel Security Report - Document CRUD

## Actions Taken
- Implemented file validation in `DocumentoController`:
    - restricted mimes to `pdf`.
    - restricted max size to `10MB`.
- Files are stored in a non-public directory (`storage/app/documentos`).
- Used `Auth::id()` to link document to the logged-in user.
- Added `nivel_acesso` control to documents.

## Security Improvements
- Prevented arbitrary file uploads (only PDF allowed).
- Ensured documents are associated with existing processes.
- Prepared for future access control checks based on `nivel_acesso`.
