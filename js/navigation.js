<?php
// =============================================================================
// NAVIGATION.JS - Mobile Menu JavaScript
// =============================================================================
?>

<script>
/**
 * Mobile Navigation Toggle
 */
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const primaryMenu = document.querySelector('.primary-menu');
    
    if (menuToggle && primaryMenu) {
        menuToggle.addEventListener('click', function() {
            const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
            
            menuToggle.setAttribute('aria-expanded', !expanded);
            primaryMenu.classList.toggle('toggled');
            
            // Update menu text
            const menuText = menuToggle.querySelector('.menu-text');
            if (menuText) {
                menuText.textContent = !expanded ? 'Close' : 'Menu';
            }
        });
    }
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.main-navigation')) {
            const primaryMenu = document.querySelector('.primary-menu');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (primaryMenu && menuToggle) {
                primaryMenu.classList.remove('toggled');
                menuToggle.setAttribute('aria-expanded', 'false');
                
                const menuText = menuToggle.querySelector('.menu-text');
                if (menuText) {
                    menuText.textContent = 'Menu';
                }
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        const primaryMenu = document.querySelector('.primary-menu');
        const menuToggle = document.querySelector('.menu-toggle');
        
        if (window.innerWidth > 782 && primaryMenu && menuToggle) {
            primaryMenu.classList.remove('toggled');
            menuToggle.setAttribute('aria-expanded', 'false');
            
            const menuText = menuToggle.querySelector('.menu-text');
            if (menuText) {
                menuText.textContent = 'Menu';
            }
        }
    });
});
</script>
