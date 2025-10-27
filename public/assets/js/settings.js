/**
 * Settings Page JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Settings page loaded');
    
    // Initialize forms
    initProfileForm();
    initSecurityForm();
});

function initProfileForm() {
    const form = document.getElementById('profileForm');
    if (!form) return;
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = {
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            fullName: document.getElementById('fullName').value,
            phone: document.getElementById('phone').value,
            country: document.getElementById('country').value,
            accountType: document.getElementById('accountType').value
        };
        
        console.log('Updating profile:', data);
        
        // Simulate API call
        try {
            // In production, this would be an actual API call
            // const response = await fetch('../api/user.php?action=update_profile', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify(data)
            // });
            
            alert('Profile updated successfully!');
            
            // Update UI with new data
            console.log('Profile updated:', data);
        } catch (error) {
            console.error('Error updating profile:', error);
            alert('Failed to update profile. Please try again.');
        }
    });
}

function initSecurityForm() {
    const form = document.getElementById('securityForm');
    if (!form) return;
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        // Validation
        if (newPassword !== confirmPassword) {
            alert('New password and confirm password do not match!');
            return;
        }
        
        if (newPassword.length < 8) {
            alert('New password must be at least 8 characters long!');
            return;
        }
        
        const data = {
            currentPassword: currentPassword,
            newPassword: newPassword
        };
        
        console.log('Updating password');
        
        // Simulate API call
        try {
            // In production, this would be an actual API call
            // const response = await fetch('../api/user.php?action=change_password', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify(data)
            // });
            
            alert('Password updated successfully!');
            
            // Clear form
            form.reset();
        } catch (error) {
            console.error('Error updating password:', error);
            alert('Failed to update password. Please try again.');
        }
    });
}

function saveNotificationSettings() {
    const settings = {
        email: document.getElementById('emailNotifications').checked,
        orders: document.getElementById('orderNotifications').checked,
        payments: document.getElementById('paymentNotifications').checked,
        promotions: document.getElementById('promoNotifications').checked
    };
    
    console.log('Saving notification settings:', settings);
    
    // Simulate API call
    // In production, this would be an actual API call
    // fetch('../api/user.php?action=update_notifications', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify(settings)
    // });
    
    alert('Notification settings saved successfully!');
}

// Switch Settings Tabs
function switchSettingsTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.settings-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    const selectedTab = document.getElementById(tabName + 'Tab');
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // Add active class to clicked button
    event.target.closest('.settings-tab-btn')?.classList.add('active');
}

// Handle 2FA toggle
document.getElementById('enable2FA')?.addEventListener('change', (e) => {
    if (e.target.checked) {
        if (confirm('Do you want to enable Two-Factor Authentication? You will need to set it up after enabling.')) {
            alert('2FA setup will open in a new window.');
        } else {
            e.target.checked = false;
        }
    } else {
        if (confirm('Are you sure you want to disable Two-Factor Authentication?')) {
            console.log('2FA disabled');
        } else {
            e.target.checked = true;
        }
    }
});

// Save Preferences
function savePreferences() {
    const prefs = {
        language: document.getElementById('language').value,
        timezone: document.getElementById('timezone').value,
        dateFormat: document.getElementById('dateFormat').value,
        darkMode: document.getElementById('darkModeToggle').checked,
        compactView: document.getElementById('compactView').checked
    };
    
    console.log('Saving preferences:', prefs);
    
    // Simulate API call
    alert('Preferences saved successfully!');
}

// Billing functions
function downloadInvoice(invoiceId) {
    console.log('Downloading invoice:', invoiceId);
    alert('Invoice download started...');
}

function removePaymentMethod(method) {
    if (confirm('Are you sure you want to remove this payment method?')) {
        console.log('Removing payment method:', method);
        alert('Payment method removed successfully!');
    }
}

function addPaymentMethod() {
    alert('Add payment method modal would open here.');
}

// Dark Mode Toggle
document.getElementById('darkModeToggle')?.addEventListener('change', (e) => {
    toggleTheme();
});

