// ========== SETTINGS MANAGER ==========
class SettingsManager {
    constructor() {
        this.apiUrl = '/api/settings';
        this.currentTab = 'general';
    }

    // Load settings for current tab
    async loadSettings() {
        try {
            const response = await this.apiCall('GET', null, { group: this.currentTab });

            if (response.success && response.data) {
                this.populateForm(response.data);
            } else {
                console.error('Failed to load settings:', response.message);
                this.showNotification('Failed to load settings', 'error');
            }
        } catch (error) {
            console.error('Error loading settings:', error);
            this.showNotification('Error loading settings', 'error');
        }
    }

    // Save settings for current tab
    async saveSettings() {
        const formData = this.collectFormData();
        
        try {
            const response = await this.apiCall('POST', {
                group: this.currentTab,
                settings: formData
            });

            if (response.success) {
                this.showNotification('Settings saved successfully!', 'success');
                console.log('Settings saved for:', this.currentTab);
            } else {
                this.showNotification('Failed to save settings: ' + response.message, 'error');
                console.error('Failed to save settings:', response.message);
            }
        } catch (error) {
            this.showNotification('Error saving settings: ' + error.message, 'error');
            console.error('Error saving settings:', error);
        }
    }

    // Generic API call method for PSR compliance
    async apiCall(method = 'GET', data = null, queryParams = null) {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        };

        // Add body for non-GET requests
        if (method !== 'GET' && data) {
            options.body = JSON.stringify(data);
        }

        // Build URL with query params
        let url = this.apiUrl;
        if (queryParams) {
            const params = new URLSearchParams(queryParams);
            url += '?' + params.toString();
        }

        const response = await fetch(url, options);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    }

    // Collect form data based on current tab
    collectFormData() {
        const data = {};

        switch (this.currentTab) {
            case 'general':
                data.site_title = this.getValue('siteTitle');
                data.site_tagline = this.getValue('siteTagline');
                data.site_url = this.getValue('siteUrl');
                data.admin_email = this.getValue('adminEmail');
                data.timezone = this.getValue('timezone');
                data.membership = this.getChecked('membership');
                break;

            case 'writing':
                data.default_category = parseInt(this.getValue('defaultCategory'));
                data.default_post_format = this.getValue('defaultPostFormat');
                data.mail_server = this.getValue('mailServer');
                data.mail_port = parseInt(this.getValue('mailPort')) || 110;
                data.mail_login = this.getValue('mailLogin');
                break;

            case 'reading':
                data.homepage_display = this.getRadioValue('homepageDisplay') || 'latest';
                data.posts_per_page = parseInt(this.getValue('blogPagesShow')) || 10;
                data.feed_show = this.getRadioValue('feedShow') || 'full_text';
                data.search_engine_visibility = this.getChecked('searchEngineVisibility');
                break;

            case 'discussion':
                data.allow_pings = this.getChecked('allowPings');
                data.allow_comments = this.getChecked('allowComments');
                data.comment_registration = this.getChecked('commentRegistration');
                data.comment_moderation = this.getChecked('commentModeration');
                data.comment_nesting = this.getChecked('commentNesting');
                data.comment_levels = parseInt(this.getValue('commentLevels')) || 5;
                data.comments_per_page = parseInt(this.getValue('commentsPerPage')) || 50;
                break;
        }

        return data;
    }

    // Helper methods for form data extraction
    getValue(elementId) {
        const element = document.getElementById(elementId);
        return element ? element.value : '';
    }

    getChecked(elementId) {
        const element = document.getElementById(elementId);
        return element ? element.checked : false;
    }

    getRadioValue(name) {
        const element = document.querySelector(`input[name="${name}"]:checked`);
        return element ? element.value : null;
    }

    // Populate form with settings data
    populateForm(settings) {
        for (const [key, value] of Object.entries(settings)) {
            const element = document.getElementById(this.kebabToCamel(key));
            
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = Boolean(value);
                } else if (element.type === 'radio') {
                    const radio = document.querySelector(`input[name="${element.name}"][value="${value}"]`);
                    if (radio) radio.checked = true;
                } else {
                    element.value = value;
                }
            }
        }
    }

    // Convert kebab-case to camelCase for element IDs
    kebabToCamel(str) {
        return str.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });
    }

    // Show notification
    showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.custom-notification').forEach(el => el.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `custom-notification alert alert-${type} alert-dismissible fade show`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        `;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Reset to defaults
    async resetToDefaults() {
        if (confirm('Are you sure you want to reset all settings to defaults? This action cannot be undone.')) {
            try {
                const response = await this.apiCall('POST', { action: 'reset' });

                if (response.success) {
                    this.showNotification('Settings reset to defaults successfully!', 'success');
                    this.loadSettings(); // Reload the form with default values
                } else {
                    this.showNotification('Failed to reset settings: ' + response.message, 'error');
                }
            } catch (error) {
                this.showNotification('Error resetting settings: ' + error.message, 'error');
            }
        }
    }
}