# Changelog

All notable changes to this project will be documented in this file.

## [1.1.0] - 2025-12-27

### Added
- New `cornerstone_get_activitypub_avatar()` helper function to retrieve Fediverse avatars
- Support for ActivityPub plugin's cached actor icons in Fediverse Interactions section

### Fixed
- Fediverse Interactions now display actual Fediverse avatars instead of Gravatar fallbacks
- Avatars now properly show the user's Fediverse profile picture from Mastodon, Pixelfed, etc.

### Changed
- Fediverse reactions section now uses direct `<img>` tags with lazy loading for better performance
- Avatar images include proper `width`, `height`, and `loading="lazy"` attributes
- Improved fallback chain: ActivityPub actor icon → avatar_url meta → Webmention avatar → WordPress get_avatar_url → Gravatar

### Technical
- The avatar helper checks multiple comment meta keys: `activitypub_actor_icon`, `avatar_url`, `activitypub_actor`, `semantic_linkbacks_avatar`
- Uses WordPress `get_avatar_url()` with comment object to allow ActivityPub plugin filters to work
- Graceful fallback to Gravatar with mystery person default if no Fediverse avatar found

## [1.0.9] - 2025-12-26

### Added
- Dark mode styles for post navigation
- Dark mode styles for social icons
- Dark mode styles for related post cards

### Fixed
- Removed duplicate CSS declarations in dark mode section
- Fixed duplicate `rel` attribute on social icon links
- Dynamic version string for stylesheet cache busting

### Removed
- Backup files (.bak, .old, .new) from theme directory

## [1.0.8] - 2025-12-26

### Added
- Table styling with visible borders for both standard and dark modes
- Alternating row colours for improved table readability
- Hover effects on table rows for better visual feedback
- Proper padding and spacing for table cells and headers

### Fixed
- Tables now display visible borders in both light and dark colour schemes
- Improved table contrast and readability across all colour modes

## [1.0.7] - 2025-11-21

## Added
- Social media icons in author bio section (displays as circular icon buttons instead of raw URLs)
- Automatic platform detection for 23+ social platforms (Mastodon, Bluesky, Twitter/X, LinkedIn, Instagram, GitHub, YouTube, and more)
- Support for Mastodon instances and fediverse platforms

## Changed
- Upgraded Font Awesome from 6.4.0 to 6.6.0 for Bluesky icon support
- Author bio social links now render as styled icon buttons with hover effects
- Added dark mode support for social icon styling

## Technical
- Added `cornerstone_get_social_platform()` function to detect social platforms from URLs
- Added `cornerstone_get_social_icon()` function to return Font Awesome icon classes
- Updated `single.php` to use helper functions for icon rendering
- Enhanced CSS with `.social-icon` and `.author-social` styling

## [1.0.6] - 2025-10-16

### Added
- Native featured image caption support (removes dependency on FSM Custom Featured Image Caption plugin)
- HTML formatting support in featured image captions

### Changed
- Increased base font size from 16px to 18px for improved readability
- Consolidated duplicate dark mode CSS blocks

### Fixed
- Dark mode contrast issues in navigation menu (mobile and desktop)
- Dark mode contrast for dropdown sub-menus
- Author bio text and name link visibility in dark mode
- Related Posts heading visibility in dark mode
- Fediverse Reactions now only display on single post pages
- Comment form now works properly using WordPress native functionality

### Credits
- Special thanks to Daniel Brinneman for reporting accessibility and display issues

## [1.0.5] - [previous date]

Update CHANGELOG for version 1.0.6

## [1.0.0] - 2025-10-02

### Added
- Initial release
- Full microformats2 support (h-card, h-entry)
- Representative h-card with author information
- ActivityPub likes and boosts display
- Webmentions integration
- Responsive mobile-first design
- Dark mode support
- Accessibility features
- Translation ready
- Footer widget areas
- Custom logo and header support
- Threaded comments
- Sticky posts
- Featured images
- Print styles
- Custom menus
