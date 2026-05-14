# Palette Report - UX & Accessibility Improvements

## Work Done

### 1. Centralization of Assets
- Extracted all inline styles and `<style>` blocks from Blade views into `public/dist/css/style.css`.
- Created a minified version `public/dist/css/style-min.css`.
- Centralized JavaScript logic into `public/dist/js/app/app.core.js` and module-specific files in `public/dist/js/app/modules/`.
- Removed `onsubmit` and `onchange` handlers from Blade templates, moving the logic to the `App` object in JS modules.

### 2. Accessibility & UX Enhancements
- Added `aria-label` and `title` to all icon-only buttons across the system (Users, Processes, Process Types).
- Implemented `:focus-visible` focus indicators for better keyboard navigation without affecting mouse users.
- Applied `role="button"` and `tabindex="0"` where appropriate for non-semantic interactive elements.
- Added keyboard support (Enter/Space) for interactive elements via `app.core.js`.
- Improved layout semantic structure in `layouts/app.blade.php`.

### 3. Comprehensive Refactoring & Accessibility (Phase 2)
- Extracted remaining inline styles from all Blade views (`layouts/app.blade.php`, `processos/*`, `usuarios/*`, `tipos_processos/*`, `auth/login.blade.php`) into centralized utility classes in `public/dist/css/style.css`.
- Implemented `.form-label-required` utility to visually indicate mandatory fields using a pseudo-element (`::after`) with a red asterisk, ensuring consistency across all forms.
- Enhanced sidebar accessibility by adding `aria-current="page"` to active navigation links.
- Improved accessibility in User Management by removing redundant `role="button"` on semantic `<button>` elements.
- Ensured all icon-only buttons and filter buttons have appropriate `aria-label` and `title` attributes.
- Refactored the login page to use a centralized `.bg-login-gradient` and utility classes for layout and typography.
- Standardized spacing and sizing across the application using new utility classes (e.g., `.mb-1`, `.mt-2`, `.w-200`, `.h-45`).

### 4. Files Modified
- `public/dist/css/style.css`: Added utility classes and accessibility rules.
- `resources/views/layouts/app.blade.php`: Sidebar accessibility and style extraction.
- `resources/views/processos/*.blade.php`: Style extraction and accessibility improvements.
- `resources/views/usuarios/*.blade.php`: Style extraction and accessibility improvements.
- `resources/views/tipos_processos/*.blade.php`: Style extraction and accessibility improvements.
- `resources/views/auth/login.blade.php`: Complete refactor to remove inline styles.
- `tests/Feature/ExampleTest.php`: Updated test to match redirection behavior.

## Lessons Learned
- Centralizing styles early prevents "style soup" in Blade templates and improves maintainability.
- Keyboard navigation is often overlooked but easily improved with `:focus-visible` and event delegation in a core JS file.
