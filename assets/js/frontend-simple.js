/**
 * Advanced Product Display Scripts - Simple Version (No Animation)
 * Use this if you want rating bars to show immediately without animation
 */

// Toggle Collapsible Sections
function toggleCollapsible(element) {
    element.classList.toggle('collapsed');
    
    const content = element.nextElementSibling;
    
    if (content.classList.contains('collapsed')) {
        content.classList.remove('collapsed');
    } else {
        content.classList.add('collapsed');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    
    // NO ANIMATION - Rating bars show immediately at correct width
    // If you want animation, use advanced-product-scripts.js instead
    
    // Add smooth scroll to sections
    const collapsibleHeaders = document.querySelectorAll('.collapsible-header');
    collapsibleHeaders.forEach(function(header) {
        header.addEventListener('click', function() {
            setTimeout(function() {
                header.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 300);
        });
    });
});

// Track Buy Now clicks (for analytics)
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('button') && e.target.closest('.buy-now')) {
        const storeName = e.target.closest('.price-item').querySelector('.price-store').textContent;
        console.log('Buy Now clicked for:', storeName);
    }
});
