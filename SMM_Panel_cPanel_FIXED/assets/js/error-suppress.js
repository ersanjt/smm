// Global Error Suppression Script
// This file suppresses non-critical errors in the console

(function() {
    'use strict';
    
    // Suppress favicon and placeholder image errors
    window.addEventListener('error', function(e) {
        const errorMessage = e.message || '';
        const errorTarget = e.target || {};
        
        // Suppress favicon errors
        if (errorMessage.includes('favicon') || 
            errorMessage.includes('Failed to load resource') && 
            (errorTarget.tagName === 'LINK' || errorTarget.tagName === 'IMG')) {
            e.preventDefault();
            return false;
        }
        
        // Suppress placeholder errors
        if (errorMessage.includes('placeholder') || 
            errorMessage.includes('via.placeholder')) {
            e.preventDefault();
            return false;
        }
        
        // Suppress runtime.lastError
        if (errorMessage.includes('runtime.lastError') || 
            errorMessage.includes('port closed') ||
            errorMessage.includes('The message port closed')) {
            e.preventDefault();
            return false;
        }
        
        return true;
    }, true);
    
    // Suppress console errors for non-critical issues
    const originalConsoleError = console.error;
    console.error = function(...args) {
        const message = args.join(' ').toLowerCase();
        
        // Suppress these specific errors
        const suppressedErrors = [
            'favicon',
            'placeholder',
            'runtime.lastError',
            'port closed',
            'message port closed',
            'failed to load resource',
            'net::err_name_not_resolved'
        ];
        
        const shouldSuppress = suppressedErrors.some(error => message.includes(error));
        
        if (!shouldSuppress) {
            originalConsoleError.apply(console, args);
        }
    };
    
    console.log('✅ Error suppression loaded successfully');
})();

