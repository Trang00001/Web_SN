/**
 * Simple Settings Manager - Chỉ quản lý Theme & Color Scheme
 */
class SettingsManager {
    constructor() {
        this.defaultSettings = {
            theme: 'light',
            colorScheme: 'default'
        };
        this.settings = this.loadSettings();
        this.applySettings();
        this.setupModal();
    }

    loadSettings() {
        try {
            const saved = localStorage.getItem('app_settings');
            if (saved) return { ...this.defaultSettings, ...JSON.parse(saved) };
        } catch (e) {
            console.error('Error loading settings:', e);
        }
        return { ...this.defaultSettings };
    }

    saveSettings() {
        try {
            localStorage.setItem('app_settings', JSON.stringify(this.settings));
        } catch (e) {
            console.error('Error saving settings:', e);
        }
    }

    applySettings() {
        this.applyTheme();
        this.applyColorScheme();
    }

    applyTheme() {
        const root = document.documentElement;
        root.classList.remove('theme-light', 'theme-dark');
        root.classList.add(`theme-${this.settings.theme}`);
    }

    applyColorScheme() {
        const root = document.documentElement;
        root.classList.remove('color-default', 'color-blue', 'color-green', 'color-red', 'color-orange');
        root.classList.add(`color-${this.settings.colorScheme}`);
    }

    setupModal() {
        const modal = document.getElementById('settingsModal');
        if (!modal) return;

        modal.addEventListener('show.bs.modal', () => {
            document.getElementById('themeSelect').value = this.settings.theme;
            document.getElementById('colorSchemeSelect').value = this.settings.colorScheme;
        });

        const saveBtn = document.getElementById('saveSettingsBtn');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                this.settings.theme = document.getElementById('themeSelect').value;
                this.settings.colorScheme = document.getElementById('colorSchemeSelect').value;
                this.saveSettings();
                this.applySettings();
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) modalInstance.hide();
            });
        }
    }
}

// Khởi tạo khi DOM sẵn sàng
document.addEventListener('DOMContentLoaded', () => {
    window.settingsManager = new SettingsManager();
});
