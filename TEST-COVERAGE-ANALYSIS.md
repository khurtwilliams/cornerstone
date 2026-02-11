# Test Coverage Analysis: Cornerstone WordPress Theme

## Executive Summary

The Cornerstone theme currently has **zero automated tests**. There are no test files, no testing framework configured (PHPUnit, Jest, or otherwise), no `composer.json`, no `package.json`, and no CI pipeline. All quality assurance relies entirely on manual testing.

This document identifies the most impactful areas to add test coverage, prioritized by risk and complexity.

---

## Codebase Inventory

| File | Lines | Testable Logic | Risk Level |
|------|-------|----------------|------------|
| `functions.php` | 367 | 11 functions with business logic | **High** |
| `single.php` | 271 | Raw SQL queries, conditional rendering | **High** |
| `comments.php` | 86 | Custom comment callback, conditional display | Medium |
| `header.php` | 106 | Author lookup fallback, h-card generation | Medium |
| `index.php` | 94 | Conditional excerpt vs. content | Low |
| `footer.php` | 52 | Conditional widget areas | Low |
| `page.php` | 37 | Minimal logic | Low |
| `js/navigation.js` | 56 | DOM event handling, aria state | Medium |
| `style.css` | 1152 | N/A (visual) | N/A |

---

## Priority 1: Pure Logic Functions (High Impact, Low Effort)

These functions in `functions.php` contain pure logic with clear inputs/outputs and no WordPress side effects. They are the easiest and most valuable to test first.

### `cornerstone_get_social_platform($url)` (line 164)

Maps a URL string to a social platform display name. Contains 21 platform mappings plus a fallback that parses the URL host.

**What to test:**
- Each known platform domain returns the correct name (e.g., `github.com/user` -> `"GitHub"`)
- URLs with `www.` prefixes
- The `parse_url` fallback for unknown domains
- Edge case: malformed URL returns `"Social Profile"` fallback
- Edge case: empty string input
- Precedence: `x.com` matches `"X"` but shouldn't match `"Pixelfed"` due to iteration order

### `cornerstone_get_social_icon($url)` (line 209)

Maps a URL to a Font Awesome icon class. Contains 24 domain-to-icon mappings.

**What to test:**
- Each domain returns the correct Font Awesome class
- The fallback returns `'fas fa-link'`
- **Potential bug**: The `.social` catch-all entry (line 238) matches any URL containing `.social` (e.g., `about.instagram.social` would match Mastodon icon). This broad match should be tested and possibly moved to the end of the array or made more specific.
- Mastodon instance variants (`mastodon.social`, `fosstodon.org`, `indieweb.social`) all resolve correctly

### `cornerstone_excerpt_length($length)` and `cornerstone_excerpt_more($more)` (lines 133, 139)

Trivial functions, but worth a quick test to prevent accidental regressions.

---

## Priority 2: Complex Logic with Multiple Code Paths (High Impact, Medium Effort)

### `cornerstone_get_activitypub_avatar($comment_id, $email, $size)` (line 263)

This is the most complex function in the theme with **6 sequential fallback checks** for retrieving an avatar URL. It interacts with WordPress comment meta, the ActivityPub plugin, and Gravatar.

**What to test (requires mocking `get_comment_meta`, `get_comment`, `get_avatar_url`):**
1. `activitypub_actor_icon` meta as an array with `url` key
2. `activitypub_actor_icon` meta as a direct string URL
3. `avatar_url` meta fallback
4. `protocol` = `activitypub` with nested `activitypub_actor` -> `icon` -> `url`
5. `protocol` = `activitypub` with `icon` as a string
6. `semantic_linkbacks_avatar` meta (Webmention plugin)
7. WordPress `get_avatar_url` filter fallback
8. Final Gravatar fallback when all else fails
9. Gravatar fallback triggers when URL contains `gravatar.com/avatar/?` (mystery person)
10. Default `$size` parameter (32)

### `cornerstone_get_related_posts($post_id, $number)` (line 145)

Contains fallback logic: tries category-based related posts first, then falls back to random posts.

**What to test (requires mocking `get_posts`, `wp_get_post_categories`):**
- Returns category-matched posts when available
- Falls back to random posts when no category matches exist
- Excludes the current post from results
- Respects the `$number` parameter
- Default `$number` is 3

---

## Priority 3: Template Output and Markup Correctness (Medium Impact, Medium Effort)

### Microformats2 Markup Validation

The theme's core value proposition is IndieWeb support via microformats2. Incorrect markup silently breaks interoperability with IndieWeb tools.

**What to test:**
- `h-entry` class present on articles in `index.php`, `single.php`, `page.php`
- `h-card` class present in `header.php` and author bio in `single.php`
- Required microformat properties exist: `p-name`, `e-content`, `dt-published`, `u-url`, `u-photo`, `p-author`
- `rel="me"` links in `cornerstone_add_rel_me()` output valid HTML

### ActivityPub Interactions in `single.php` (line 86-151)

Contains **raw `$wpdb->prepare()` queries** that fetch likes and reposts.

**What to test:**
- SQL queries return correct results for posts with likes/reposts
- Empty state: no interactions renders no markup
- Likes-only and reposts-only states render correctly
- Avatar URLs are properly escaped with `esc_url()`
- Author names are properly escaped with `esc_attr()`

### `cornerstone_comment()` callback in `comments.php` (line 54)

Custom comment rendering function passed to `wp_list_comments`.

**What to test:**
- Outputs correct microformat classes (`h-entry`, `h-card`, `p-name`, `e-content`, `dt-published`)
- Comment author link is rendered
- Reply link respects depth settings

---

## Priority 4: JavaScript Behavior (Medium Impact, Low-Medium Effort)

### `navigation.js`

Three event listeners handling mobile menu state.

**What to test:**
- Click toggle: `aria-expanded` flips between `"true"` and `"false"`
- Click toggle: `.toggled` class toggles on `.primary-menu`
- Menu text changes between "Menu" and "Close"
- Click outside `.main-navigation` closes the menu
- Window resize above 782px closes the menu
- Graceful handling when `.menu-toggle` or `.primary-menu` don't exist in DOM

---

## Priority 5: WordPress Integration Tests (Lower Impact, Higher Effort)

### Theme Setup (`cornerstone_setup`)

- All declared theme supports are registered
- Navigation menus `primary` and `footer` are registered
- Custom image sizes `featured-image` and `related-post-thumb` are registered

### Script/Style Enqueuing (`cornerstone_scripts`)

- `cornerstone-style` enqueued with correct version
- `font-awesome` CDN URL is valid and loads
- `comment-reply` script only enqueued when conditions are met (`is_singular() && comments_open() && get_option('thread_comments')`)

### Widget Registration (`cornerstone_widgets_init`)

- Three footer widget areas (`footer-1`, `footer-2`, `footer-3`) are registered
- Widget markup uses correct HTML wrapper elements

### Theme Customizer (`cornerstone_customize_register`)

- `social_links` section registered with 5 URL controls
- `author_bio` section registered with boolean toggle
- Sanitize callbacks are correctly assigned (`esc_url_raw`, `wp_validate_boolean`)

---

## Recommended Testing Infrastructure

### PHP Testing (PHPUnit + WordPress Test Suite)

```json
// composer.json
{
    "require-dev": {
        "phpunit/phpunit": "^9.6",
        "wp-phpunit/wp-phpunit": "^6.5",
        "yoast/phpunit-polyfills": "^2.0"
    }
}
```

**Suggested directory structure:**
```
tests/
  bootstrap.php          # WordPress test suite bootstrap
  phpunit.xml            # PHPUnit configuration
  unit/                  # Pure unit tests (no WP dependencies)
    SocialPlatformTest.php
    SocialIconTest.php
  integration/           # Tests requiring WordPress
    ActivityPubAvatarTest.php
    RelatedPostsTest.php
    ThemeSetupTest.php
    ScriptEnqueueTest.php
    MicroformatsOutputTest.php
```

### JavaScript Testing (Jest + jsdom)

```json
// package.json
{
    "devDependencies": {
        "jest": "^29.0",
        "jest-environment-jsdom": "^29.0"
    }
}
```

### CI Pipeline (GitHub Actions)

A workflow that runs PHPUnit on each push/PR and optionally validates the CSS and microformats output.

---

## Potential Bugs Found During Analysis

1. **`.social` catch-all in `cornerstone_get_social_icon()`** (functions.php:238): The entry `'.social' => 'fab fa-mastodon'` will match ANY URL containing `.social`, not just Mastodon instances. For example, a hypothetical `photos.social` or `about.instagram.social` would incorrectly get the Mastodon icon.

2. **Hardcoded author lookup in `header.php`** (line 31): `get_user_by('login', 'khurtwilliams')` is hardcoded to a specific username. This makes the theme non-portable for other users. The fallback to user ID 1 partially mitigates this, but a test should verify both paths work.

3. **No input validation in `cornerstone_get_social_platform()`**: If `$url` is `null` or not a string, `strtolower()` on line 165 would produce a PHP warning in PHP 8.1+.

---

## Recommended Implementation Order

| Step | Area | Framework | Files to Create |
|------|------|-----------|----------------|
| 1 | Social platform/icon mapping | PHPUnit (unit) | `tests/unit/SocialPlatformTest.php` |
| 2 | ActivityPub avatar retrieval | PHPUnit (integration) | `tests/integration/ActivityPubAvatarTest.php` |
| 3 | Related posts logic | PHPUnit (integration) | `tests/integration/RelatedPostsTest.php` |
| 4 | Navigation JS | Jest + jsdom | `tests/js/navigation.test.js` |
| 5 | Microformats output | PHPUnit (integration) | `tests/integration/MicroformatsOutputTest.php` |
| 6 | Theme setup & registration | PHPUnit (integration) | `tests/integration/ThemeSetupTest.php` |
| 7 | CI pipeline | GitHub Actions | `.github/workflows/tests.yml` |
