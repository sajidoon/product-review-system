/**
 * Advanced Product Display Scripts
 * Handles collapsible sections, ratings animations, and interactive features
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

// Animate Rating Bars on Load
document.addEventListener('DOMContentLoaded', function() {
    
    // Animate rating bars
    const ratingFills = document.querySelectorAll('.rating-fill');
    ratingFills.forEach(function(fill, index) {
        // Get the target width from data attribute or style
        const targetWidth = fill.getAttribute('data-width') || fill.style.width;
        
        if (targetWidth && parseFloat(targetWidth) > 0) {
            // Start from 0
            fill.style.width = '0%';
            
            // Animate to target width
            setTimeout(function() {
                fill.style.width = targetWidth + (targetWidth.includes('%') ? '' : '%');
            }, 100 + (index * 150));
        }
    });
    
    // Animate overall score
    const scoreNumber = document.querySelector('.score-number');
    if (scoreNumber) {
        const targetScore = parseFloat(scoreNumber.textContent);
        let currentScore = 0;
        const increment = targetScore / 30;
        
        const counter = setInterval(function() {
            currentScore += increment;
            if (currentScore >= targetScore) {
                scoreNumber.textContent = targetScore.toFixed(1);
                clearInterval(counter);
            } else {
                scoreNumber.textContent = currentScore.toFixed(1);
            }
        }, 30);
    }
    
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

// Copy to clipboard functionality (optional for specs)
function copyToClipboard(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    
    // Show copied notification
    const notification = document.createElement('div');
    notification.textContent = 'Copied to clipboard!';
    notification.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background: #28a745; color: white; padding: 15px 25px; border-radius: 5px; z-index: 9999;';
    document.body.appendChild(notification);
    
    setTimeout(function() {
        notification.remove();
    }, 2000);
}

// Track Buy Now clicks (for analytics)
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('button') && e.target.closest('.buy-now')) {
        const storeName = e.target.closest('.price-item').querySelector('.price-store').textContent;
        console.log('Buy Now clicked for:', storeName);
        
        // You can add analytics tracking here
        // Example: gtag('event', 'click', { 'event_category': 'buy_now', 'event_label': storeName });
    }
});
