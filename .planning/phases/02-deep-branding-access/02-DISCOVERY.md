# Discovery: Phase 02 - Deep Branding & Access

## Objective
Transform the pre-authentication and authentication flow to feel like a proprietary service.

## Requirements Addressed
- **BRAND-05**: Custom Login page with branded background and slogan.
- **EXP-03**: Proprietary pre-login landing page implemented.

## Technical Findings

### 1. Login Page Customization (BRAND-05)
Nextcloud provides built-in theming capabilities via the `occ` CLI and the Theming app.
- **Background Image**: Can be set using `occ theming:config background /path/to/image.jpg`.
- **Slogan**: Can be set using `occ theming:config slogan "Slogan text"`.
- **Deep Visuals**: While `occ` covers the basics, "Deep Branding" requires CSS overrides. The planned **Custom Branding App** will be used to inject custom CSS into the login page to control:
  - Exact positioning of the login box.
  - Custom fonts and colors not covered by primary theming.
  - Removal of "Nextcloud" branding remnants on the login screen.

### 2. Pre-Login Landing Page (EXP-03)
Nextcloud does not have a native "Marketing Landing Page" feature. To implement a proprietary entry point:
- **Approach**: Create a minimal Nextcloud app (`escardar_landing`) that implements a public page.
- **Implementation**:
  - Use `#[PublicPage]` attribute in a controller to serve a branded HTML/CSS landing page.
  - The landing page will include a "Client Login" button that redirects to `/index.php/login`.
  - **Server Integration**: Configure the web server (Nginx/Apache) or use a simple `index.html` redirect to ensure users land on this page first.
- **Alternative**: A static HTML page in the web root. However, an app is preferred for consistency with the project's "Custom App" strategy.

## Implementation Strategy

### Wave 1: Base Configuration
- Use `occ` to set the background image and slogan for the login page.
- Verify the basic themed login screen.

### Wave 2: Landing Page Implementation
- Create the `escardar_landing` app.
- Implement the `PublicPage` controller to serve the landing page.
- Wire the "Login" button to the Nextcloud login flow.

### Wave 3: Deep Visual Polish
- Use the **Custom Branding App** to apply high-fidelity CSS overrides to both the landing page and the login screen.
- Ensure the transition from Landing Page $\rightarrow$ Login Page is visually seamless.

## Verification Plan
- [ ] `curl` or Browser check: visiting the root domain leads to the Landing Page.
- [ ] Landing Page "Login" button leads to the branded Login Screen.
- [ ] Login Screen displays the correct background image and slogan.
- [ ] UI conforms to the HEX palette defined in `REQUIREMENTS.md`.
