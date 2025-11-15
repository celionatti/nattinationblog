// ========== SETTINGS MANAGER (Using Global Helpers) ==========

class SettingsManager {
  constructor() {
    this.apiUrl = "/api/settings";
    this.currentTab = "general";
    this.setupEventListeners();
  }

  // Setup event listeners for all buttons
  setupEventListeners() {
    // Save buttons
    document.querySelectorAll(".btn-primary").forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        this.saveSettings();
      });
    });

    // Reset button
    document.querySelectorAll(".btn-secondary").forEach((button) => {
      const text = button.textContent.toLowerCase();
      if (text.includes("reset")) {
        button.addEventListener("click", (e) => {
          e.preventDefault();
          this.resetToDefaults();
        });
      }
    });
  }

  // Load settings for current tab
  async loadSettings() {
    try {
      const response = await ApiHelper.get(this.apiUrl, {
        group: this.currentTab,
      });

      if (response.success && response.data) {
        FormHelper.populateForm(response.data, StringHelper.snakeToCamel);
      } else {
        console.error("Failed to load settings:", response.message);
        NotificationHelper.error("Failed to load settings");
      }
    } catch (error) {
      console.error("Error loading settings:", error);
      NotificationHelper.error("Error loading settings: " + error.message);
    }
  }

  // Save settings for current tab
  async saveSettings() {
    const formData = this.collectFormData();

    try {
      const response = await ApiHelper.post(this.apiUrl, {
        group: this.currentTab,
        settings: formData,
      });

      if (response.success) {
        NotificationHelper.success("Settings saved successfully!");
        console.log("Settings saved for:", this.currentTab);
      } else {
        NotificationHelper.error(
          "Failed to save settings: " + response.message
        );
        console.error("Failed to save settings:", response.message);
      }
    } catch (error) {
      NotificationHelper.error("Error saving settings: " + error.message);
      console.error("Error saving settings:", error);
    }
  }

  // Collect form data based on current tab
  collectFormData() {
    const data = {};

    switch (this.currentTab) {
      case "general":
        data.site_title = FormHelper.getValue("siteTitle");
        data.site_tagline = FormHelper.getValue("siteTagline");
        data.site_url = FormHelper.getValue("siteUrl");
        data.admin_email = FormHelper.getValue("adminEmail");
        data.timezone = FormHelper.getValue("timezone");
        data.membership = FormHelper.getChecked("membership");
        break;

      case "writing":
        data.default_category = parseInt(
          FormHelper.getValue("defaultCategory")
        );
        data.default_post_format = FormHelper.getValue("defaultPostFormat");
        data.mail_server = FormHelper.getValue("mailServer");
        data.mail_port = parseInt(FormHelper.getValue("mailPort")) || 110;
        data.mail_login = FormHelper.getValue("mailLogin");
        break;

      case "reading":
        data.homepage_display =
          FormHelper.getRadioValue("homepageDisplay") || "latest";
        data.posts_per_page =
          parseInt(FormHelper.getValue("blogPagesShow")) || 10;
        data.feed_show = FormHelper.getRadioValue("feedShow") || "full_text";
        data.search_engine_visibility = FormHelper.getChecked(
          "searchEngineVisibility"
        );
        break;

      case "discussion":
        data.allow_pings = FormHelper.getChecked("allowPings");
        data.allow_comments = FormHelper.getChecked("allowComments");
        data.comment_registration = FormHelper.getChecked(
          "commentRegistration"
        );
        data.comment_moderation = FormHelper.getChecked("commentModeration");
        data.comment_nesting = FormHelper.getChecked("commentNesting");
        data.comment_levels =
          parseInt(FormHelper.getValue("commentLevels")) || 5;
        data.comments_per_page =
          parseInt(FormHelper.getValue("commentsPerPage")) || 50;
        break;
    }

    return data;
  }

  // Reset to defaults
  async resetToDefaults() {
    if (
      confirm(
        "Are you sure you want to reset all settings to defaults? This action cannot be undone."
      )
    ) {
      try {
        const response = await ApiHelper.post("/api/settings/reset");

        if (response.success) {
          NotificationHelper.success(
            "Settings reset to defaults successfully!"
          );
          this.loadSettings(); // Reload the form with default values
        } else {
          NotificationHelper.error(
            "Failed to reset settings: " + response.message
          );
        }
      } catch (error) {
        NotificationHelper.error("Error resetting settings: " + error.message);
      }
    }
  }
}
