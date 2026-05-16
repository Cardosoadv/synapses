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

### 3. Files Modified
- `resources/views/layouts/app.blade.php`: Included new CSS/JS, removed inline styles, and added accessibility roles.
- `resources/views/processos/index.blade.php`, `show.blade.php`, `create.blade.php`: UX and accessibility updates, centralized styling.
- `resources/views/usuarios/index.blade.php`, `form.blade.php`: UX and accessibility updates, centralized styling.
- `resources/views/tipos_processos/index.blade.php`, `create.blade.php`, `edit.blade.php`: UX and accessibility updates, centralized styling.
- `resources/views/auth/login.blade.php`: Completely refactored to remove inline styles.
- `public/dist/css/style.css`: Added utility classes and `.form-label-required`.

### 4. Recent Improvements (Batch 2)
- **CSS Utility Classes**: Created a set of standardized classes for dimensions (`.w-200`, `.h-45`), spacing (`.mb-2`, `.mt-1`), alignment (`.flex-center`), and typography.
- **Mandatory Fields**: Implemented `.form-label-required` using `::after` pseudo-element to consistently mark mandatory fields across all forms.
- **Accessibility Roles**: Added `role="alert"` to flash messages and `aria-current="page"` to active sidebar links.
- **Semantic Cleanup**: Removed redundant `role="button"` from semantic `<button>` elements while ensuring accessibility for icon-only buttons via `title` and `aria-label`.

## Lessons Learned
- Centralizing styles early prevents "style soup" in Blade templates and improves maintainability.
- Keyboard navigation is often overlooked but easily improved with `:focus-visible` and event delegation in a core JS file.
- Using CSS utility classes for recurring layout patterns (like centered containers or form margins) significantly reduces the weight of Blade files.
