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
- `resources/views/layouts/app.blade.php`: Included new CSS/JS and removed inline styles.
- `resources/views/processos/index.blade.php`, `show.blade.php`, `create.blade.php`: UX and accessibility updates.
- `resources/views/usuarios/index.blade.php`, `form.blade.php`: UX and accessibility updates.
- `resources/views/tipos_processos/index.blade.php`: UX and accessibility updates.
- `resources/views/auth/login.blade.php`: Extracted inline styles and added required field indicators.
- `resources/views/processos/create.blade.php`: Extracted inline styles and added required field indicators.
- `resources/views/usuarios/form.blade.php`: Added required field indicators.
- `resources/views/tipos_processos/create.blade.php`: Extracted inline styles and added required field indicators.

## Latest Improvements (Palette Trajectory)

### 1. Style Centralization & Utility Classes
- Extracted remaining inline styles from `login.blade.php`, `app.blade.php`, `processos/index.blade.php`, `processos/show.blade.php`, and `tipos_processos/create.blade.php`.
- Created utility classes for common layouts: `.login-wrapper`, `.login-card`, `.container-medium`, `.w-200`, `.w-150`, `.h-45`, `.p-1-5`, `.p-2`, `.mb-1`, `.mt-1`, `.mt-2`.
- Standardized spacing and alignment using these utilities, removing hardcoded `style` attributes.

### 2. Accessibility Deep Dive
- Added `role="alert"` to global notification blocks (success/error) in `layouts/app.blade.php`.
- Implemented `aria-current="page"` for active navigation links in the sidebar.
- Conducted an audit and added missing `aria-label` and `title` attributes to search and action buttons.
- Removed redundant `role="button"` from elements that are already semantically buttons.

### 3. Visual Feedback for Forms
- Introduced `.form-label-required` class to provide a consistent visual indicator (red asterisk) for mandatory fields.
- Expanded focus visibility to `select` and `textarea` elements, ensuring full keyboard navigation support across all form components.

## Lessons Learned
- Centralizing styles early prevents "style soup" in Blade templates and improves maintainability.
- Keyboard navigation is often overlooked but easily improved with `:focus-visible` and event delegation in a core JS file.
