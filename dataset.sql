-- Password Manager Database - Fake Data for Testing
-- WARNING: This contains FAKE data only - do not use real passwords or sensitive information

-- Clear existing data (optional - uncomment if needed)
-- DELETE FROM password_history;
-- DELETE FROM credentials;
-- DELETE FROM credit_cards;
-- DELETE FROM secure_notes;

-- Additional categories (beyond the default ones)
INSERT INTO categories (name, description, icon) VALUES
('Education', 'Schools, online courses, educational platforms', 'education'),
('Health & Fitness', 'Medical portals, fitness apps, health tracking', 'health'),
('Travel', 'Airline accounts, hotel bookings, travel sites', 'travel'),
('Utilities', 'Electric, gas, water, internet providers', 'utilities'),
('Insurance', 'Auto, home, life insurance portals', 'insurance');

-- Fake credentials data (passwords are intentionally fake/encrypted placeholders)
INSERT INTO credentials (category_id, service_name, username, email, password_encrypted, website_url, notes, is_favorite, last_used) VALUES
-- Social Media (category_id = 1)
(1, 'Facebook', 'sarah.johnson', 'sarah.johnson@email.com', 'ENC_fb_pass_2024_$#!', 'https://facebook.com', 'Personal account - backup codes in notes app', TRUE, '2024-07-03 14:30:00'),
(1, 'Instagram', 'sarahj_photos', 'sarah.johnson@email.com', 'ENC_insta_secure_789', 'https://instagram.com', 'Photography portfolio account', TRUE, '2024-07-03 09:15:00'),
(1, 'Twitter/X', 'sarah_tweets', 'sarah.johnson@email.com', 'ENC_twitter_x_pass_456', 'https://x.com', 'Professional networking', FALSE, '2024-07-01 16:45:00'),
(1, 'LinkedIn', 'sarah.johnson.dev', 'sarah.johnson@email.com', 'ENC_linkedin_career_123', 'https://linkedin.com', 'Career development profile', TRUE, '2024-07-02 11:20:00'),
(1, 'TikTok', 'sarahcreates', 'sarah.johnson@email.com', 'ENC_tiktok_fun_321', 'https://tiktok.com', 'Creative content account', FALSE, '2024-06-30 20:10:00'),
(1, 'Reddit', 'techsarah2024', 'sarah.johnson@email.com', 'ENC_reddit_discuss_987', 'https://reddit.com', 'Tech discussions and memes', FALSE, '2024-07-01 22:30:00'),

-- Email (category_id = 2)
(2, 'Gmail', 'sarah.johnson', 'sarah.johnson@gmail.com', 'ENC_gmail_primary_secure_2024', 'https://gmail.com', 'Primary email account with 2FA enabled', TRUE, '2024-07-03 08:00:00'),
(2, 'Outlook', 'sarah.johnson.work', 'sarah.johnson.work@outlook.com', 'ENC_outlook_work_email_456', 'https://outlook.com', 'Work-related communications', TRUE, '2024-07-03 09:30:00'),
(2, 'ProtonMail', 'sarahsecure', 'sarahsecure@protonmail.com', 'ENC_proton_privacy_789', 'https://protonmail.com', 'Secure email for sensitive communications', FALSE, '2024-07-01 15:20:00'),
(2, 'Yahoo Mail', 'sarah_backup', 'sarah_backup@yahoo.com', 'ENC_yahoo_backup_123', 'https://yahoo.com', 'Backup email account', FALSE, '2024-06-28 10:45:00'),

-- Banking (category_id = 3)
(3, 'Chase Bank', 'sarah.johnson.123', 'sarah.johnson@gmail.com', 'ENC_chase_banking_secure_2024', 'https://chase.com', 'Primary checking and savings account', TRUE, '2024-07-02 19:30:00'),
(3, 'Bank of America', 'sarahj_customer', 'sarah.johnson@gmail.com', 'ENC_bofa_account_789', 'https://bankofamerica.com', 'Secondary savings account', FALSE, '2024-06-29 14:15:00'),
(3, 'PayPal', 'sarah.johnson.pay', 'sarah.johnson@gmail.com', 'ENC_paypal_payments_456', 'https://paypal.com', 'Online payments and transfers', TRUE, '2024-07-03 12:45:00'),
(3, 'Venmo', 'sarahj24', 'sarah.johnson@gmail.com', 'ENC_venmo_social_pay_123', 'https://venmo.com', 'Social payments with friends', FALSE, '2024-07-01 18:20:00'),

-- Shopping (category_id = 4)
(4, 'Amazon', 'sarah.johnson.shop', 'sarah.johnson@gmail.com', 'ENC_amazon_prime_secure_2024', 'https://amazon.com', 'Prime member - saved payment methods', TRUE, '2024-07-03 16:30:00'),
(4, 'eBay', 'sarahseller2024', 'sarah.johnson@gmail.com', 'ENC_ebay_buyer_seller_789', 'https://ebay.com', 'Buying and selling account', FALSE, '2024-06-30 13:10:00'),
(4, 'Etsy', 'sarahcrafts', 'sarah.johnson@gmail.com', 'ENC_etsy_handmade_456', 'https://etsy.com', 'Handmade crafts and gifts', FALSE, '2024-06-27 11:30:00'),
(4, 'Target', 'sarah.johnson.target', 'sarah.johnson@gmail.com', 'ENC_target_redcard_123', 'https://target.com', 'RedCard rewards member', TRUE, '2024-07-02 14:45:00'),
(4, 'Best Buy', 'sarahtech2024', 'sarah.johnson@gmail.com', 'ENC_bestbuy_tech_789', 'https://bestbuy.com', 'Electronics and tech purchases', FALSE, '2024-06-25 09:20:00'),

-- Work (category_id = 5)
(5, 'Slack', 'sarah.johnson', 'sarah.johnson.work@outlook.com', 'ENC_slack_team_comm_2024', 'https://company.slack.com', 'Team communication - TechCorp workspace', TRUE, '2024-07-03 17:00:00'),
(5, 'Microsoft Teams', 'sarah.johnson', 'sarah.johnson.work@outlook.com', 'ENC_teams_meetings_456', 'https://teams.microsoft.com', 'Video calls and collaboration', TRUE, '2024-07-03 15:30:00'),
(5, 'Zoom', 'sarah.johnson.zoom', 'sarah.johnson.work@outlook.com', 'ENC_zoom_meetings_789', 'https://zoom.us', 'Client meetings and webinars', FALSE, '2024-07-02 10:15:00'),
(5, 'Jira', 'sarah.johnson.dev', 'sarah.johnson.work@outlook.com', 'ENC_jira_project_mgmt_123', 'https://company.atlassian.net', 'Project management and bug tracking', TRUE, '2024-07-03 16:45:00'),

-- Entertainment (category_id = 6)
(6, 'Netflix', 'sarah.johnson.flix', 'sarah.johnson@gmail.com', 'ENC_netflix_streaming_2024', 'https://netflix.com', 'Premium subscription - 4 screens', TRUE, '2024-07-03 21:30:00'),
(6, 'Spotify', 'sarahmusic2024', 'sarah.johnson@gmail.com', 'ENC_spotify_premium_789', 'https://spotify.com', 'Premium music streaming', TRUE, '2024-07-03 08:30:00'),
(6, 'YouTube Premium', 'sarah.johnson', 'sarah.johnson@gmail.com', 'ENC_youtube_premium_456', 'https://youtube.com', 'Ad-free videos and music', FALSE, '2024-07-01 19:20:00'),
(6, 'Disney+', 'sarahfamily', 'sarah.johnson@gmail.com', 'ENC_disney_plus_123', 'https://disneyplus.com', 'Family entertainment subscription', FALSE, '2024-06-28 15:45:00'),
(6, 'Steam', 'sarahgamer2024', 'sarah.johnson@gmail.com', 'ENC_steam_gaming_789', 'https://store.steampowered.com', 'PC gaming library', TRUE, '2024-07-02 23:15:00'),
(6, 'PlayStation Network', 'sarah_ps_gamer', 'sarah.johnson@gmail.com', 'ENC_psn_console_456', 'https://playstation.com', 'PS5 online gaming', FALSE, '2024-06-29 20:30:00'),

-- Cloud Storage (category_id = 7)
(7, 'Google Drive', 'sarah.johnson', 'sarah.johnson@gmail.com', 'ENC_gdrive_storage_2024', 'https://drive.google.com', '2TB storage plan - work and personal files', TRUE, '2024-07-03 11:15:00'),
(7, 'Dropbox', 'sarah.johnson.db', 'sarah.johnson@gmail.com', 'ENC_dropbox_sync_789', 'https://dropbox.com', 'File synchronization across devices', FALSE, '2024-07-01 13:20:00'),
(7, 'OneDrive', 'sarah.johnson', 'sarah.johnson.work@outlook.com', 'ENC_onedrive_office_456', 'https://onedrive.com', 'Microsoft Office integration', TRUE, '2024-07-03 10:45:00'),
(7, 'iCloud', 'sarah.johnson.apple', 'sarah.johnson@gmail.com', 'ENC_icloud_apple_123', 'https://icloud.com', 'Apple device backup and sync', FALSE, '2024-06-30 16:10:00'),

-- Development (category_id = 8)
(8, 'GitHub', 'sarah-johnson-dev', 'sarah.johnson.work@outlook.com', 'ENC_github_repos_2024', 'https://github.com', 'Personal and work repositories', TRUE, '2024-07-03 18:30:00'),
(8, 'GitLab', 'sarah.johnson.gl', 'sarah.johnson.work@outlook.com', 'ENC_gitlab_cicd_789', 'https://gitlab.com', 'CI/CD pipelines and private repos', FALSE, '2024-07-02 12:20:00'),
(8, 'Stack Overflow', 'sarah_codes', 'sarah.johnson@gmail.com', 'ENC_stackoverflow_dev_456', 'https://stackoverflow.com', 'Developer Q&A and reputation building', FALSE, '2024-07-01 14:30:00'),
(8, 'CodePen', 'sarahcreates', 'sarah.johnson@gmail.com', 'ENC_codepen_demos_123', 'https://codepen.io', 'Frontend demos and experiments', FALSE, '2024-06-26 17:45:00'),

-- VPN/Security (category_id = 9)
(9, 'NordVPN', 'sarah.johnson.vpn', 'sarah.johnson@gmail.com', 'ENC_nordvpn_secure_2024', 'https://nordvpn.com', '3-year subscription - 6 devices', TRUE, '2024-07-02 08:15:00'),
(9, 'LastPass', 'sarah.johnson.lp', 'sarah.johnson@gmail.com', 'ENC_lastpass_manager_789', 'https://lastpass.com', 'Password manager backup', FALSE, '2024-06-20 09:30:00'),
(9, 'Authy', 'sarah.johnson', 'sarah.johnson@gmail.com', 'ENC_authy_2fa_456', 'https://authy.com', 'Two-factor authentication app', TRUE, '2024-07-03 07:45:00'),

-- Education (category_id = 11)
(11, 'Coursera', 'sarah.learner', 'sarah.johnson@gmail.com', 'ENC_coursera_courses_2024', 'https://coursera.org', 'Online courses and certificates', FALSE, '2024-06-28 19:20:00'),
(11, 'Khan Academy', 'sarah_student', 'sarah.johnson@gmail.com', 'ENC_khan_learning_789', 'https://khanacademy.org', 'Free educational content', FALSE, '2024-06-25 15:30:00'),
(11, 'Udemy', 'sarah.johnson.learn', 'sarah.johnson@gmail.com', 'ENC_udemy_skills_456', 'https://udemy.com', 'Professional skill development', FALSE, '2024-06-22 11:45:00'),

-- Health & Fitness (category_id = 12)
(12, 'MyFitnessPal', 'sarahfit2024', 'sarah.johnson@gmail.com', 'ENC_myfitnesspal_health_123', 'https://myfitnesspal.com', 'Nutrition and exercise tracking', TRUE, '2024-07-03 06:30:00'),
(12, 'Strava', 'sarah_runs', 'sarah.johnson@gmail.com', 'ENC_strava_fitness_789', 'https://strava.com', 'Running and cycling activities', FALSE, '2024-07-02 17:15:00'),
(12, 'Teladoc', 'sarah.johnson.health', 'sarah.johnson@gmail.com', 'ENC_teladoc_medical_456', 'https://teladoc.com', 'Online medical consultations', FALSE, '2024-06-15 10:20:00'),

-- Travel (category_id = 13)
(13, 'Expedia', 'sarah.traveler', 'sarah.johnson@gmail.com', 'ENC_expedia_bookings_2024', 'https://expedia.com', 'Flight and hotel bookings', FALSE, '2024-06-30 14:45:00'),
(13, 'Airbnb', 'sarah.johnson.bnb', 'sarah.johnson@gmail.com', 'ENC_airbnb_stays_789', 'https://airbnb.com', 'Vacation rental bookings', FALSE, '2024-06-20 16:30:00'),
(13, 'American Airlines', 'sarah.johnson.aa', 'sarah.johnson@gmail.com', 'ENC_aa_frequent_flyer_456', 'https://aa.com', 'AAdvantage frequent flyer program', TRUE, '2024-07-01 12:10:00'),

-- Utilities (category_id = 14)
(14, 'Pacific Gas & Electric', 'sarah.johnson.pge', 'sarah.johnson@gmail.com', 'ENC_pge_utility_2024', 'https://pge.com', 'Electricity and gas service', FALSE, '2024-06-28 20:15:00'),
(14, 'Comcast Xfinity', 'sarah.johnson.xfinity', 'sarah.johnson@gmail.com', 'ENC_xfinity_internet_789', 'https://xfinity.com', 'Internet and cable service', FALSE, '2024-06-25 18:30:00'),
(14, 'T-Mobile', 'sarah.johnson.tmobile', 'sarah.johnson@gmail.com', 'ENC_tmobile_wireless_456', 'https://t-mobile.com', 'Wireless phone service', TRUE, '2024-07-02 09:45:00'),

-- Insurance (category_id = 15)
(15, 'State Farm', 'sarah.johnson.sf', 'sarah.johnson@gmail.com', 'ENC_statefarm_auto_home_123', 'https://statefarm.com', 'Auto and home insurance', FALSE, '2024-06-18 13:20:00'),
(15, 'Blue Cross Blue Shield', 'sarah.johnson.bcbs', 'sarah.johnson@gmail.com', 'ENC_bcbs_health_789', 'https://bcbs.com', 'Health insurance coverage', FALSE, '2024-06-12 11:15:00');

-- Fake credit cards data
INSERT INTO credit_cards (card_name, cardholder_name, card_number_encrypted, expiry_month, expiry_year, cvv_encrypted, bank_name, card_type, billing_address, notes) VALUES
('Primary Visa', 'Sarah Johnson', 'ENC_VISA_****_****_****_1234', 8, 2028, 'ENC_123', 'Chase Bank', 'visa', '123 Main Street, San Francisco, CA 94102', 'Primary card for daily expenses'),
('Rewards Mastercard', 'Sarah Johnson', 'ENC_MC_****_****_****_5678', 12, 2027, 'ENC_456', 'Bank of America', 'mastercard', '123 Main Street, San Francisco, CA 94102', 'Cashback rewards card'),
('Business Amex', 'Sarah Johnson', 'ENC_AMEX_****_****_****_9012', 6, 2029, 'ENC_789', 'American Express', 'amex', '123 Main Street, San Francisco, CA 94102', 'Business expenses and travel'),
('Discover Cash', 'Sarah Johnson', 'ENC_DISC_****_****_****_3456', 3, 2028, 'ENC_321', 'Discover Bank', 'discover', '123 Main Street, San Francisco, CA 94102', 'Quarterly rotating cashback categories'),
('Emergency Visa', 'Sarah Johnson', 'ENC_VISA_****_****_****_7890', 11, 2026, 'ENC_654', 'Wells Fargo', 'visa', '123 Main Street, San Francisco, CA 94102', 'Emergency backup card - keep in wallet');

-- Fake secure notes
INSERT INTO secure_notes (title, content_encrypted, category, tags) VALUES
('WiFi Passwords', 'ENC_Home_WiFi_Password_SuperSecure2024! | Guest_WiFi_GuestAccess123 | Office_WiFi_TechCorp_Secure_789', 'Network', 'wifi,passwords,home,office'),
('Software License Keys', 'ENC_Adobe_Creative_Suite_XXXX-XXXX-XXXX-XXXX | Windows_11_Pro_XXXXX-XXXXX-XXXXX-XXXXX | Office_365_XXXX-XXXX-XXXX-XXXX', 'Software', 'licenses,software,keys'),
('Emergency Contacts', 'ENC_Mom_555-0123 | Dad_555-0124 | Sister_555-0125 | Doctor_555-0126 | Insurance_Agent_555-0127', 'Personal', 'contacts,emergency,family'),
('Bank Account Numbers', 'ENC_Chase_Checking_****1234 | Chase_Savings_****5678 | BofA_Savings_****9012', 'Financial', 'banking,accounts,numbers'),
('Passport & ID Info', 'ENC_Passport_Number_123456789 | Drivers_License_CA_D1234567 | SSN_***-**-1234', 'Identity', 'passport,id,personal,documents'),
('Home Security Codes', 'ENC_Alarm_System_1234 | Garage_Door_5678 | Safe_Combination_12-34-56', 'Security', 'home,security,codes,alarm'),
('Travel Itinerary Notes', 'ENC_Flight_Confirmation_ABC123 | Hotel_Reservation_DEF456 | Car_Rental_GHI789', 'Travel', 'travel,bookings,confirmations'),
('Medical Information', 'ENC_Blood_Type_O+ | Allergies_Peanuts_Shellfish | Emergency_Contact_Dr_Smith_555-0128', 'Health', 'medical,health,allergies,doctor'),
('Investment Account Info', 'ENC_401k_Account_Fidelity_****1234 | IRA_Vanguard_****5678 | Brokerage_Schwab_****9012', 'Financial', 'investments,retirement,accounts'),
('Backup Recovery Codes', 'ENC_Google_2FA_Backup_12345-67890 | GitHub_Recovery_ABCDE-FGHIJ | PayPal_Backup_98765-43210', 'Security', 'backup,2fa,recovery,codes');

-- Password history entries (showing password changes over time)
INSERT INTO password_history (credential_id, old_password_encrypted, changed_at) VALUES
-- Gmail password changes
(1, 'ENC_gmail_old_password_2023', '2024-01-15 10:30:00'),
(1, 'ENC_gmail_older_password_2023', '2023-06-20 14:45:00'),
-- Facebook password changes
(2, 'ENC_fb_old_pass_2023', '2024-02-28 16:20:00'),
-- PayPal password changes
(11, 'ENC_paypal_old_payments_2023', '2024-03-10 11:15:00'),
-- Amazon password changes
(13, 'ENC_amazon_old_secure_2023', '2024-04-05 09:30:00'),
-- Netflix password changes
(21, 'ENC_netflix_old_streaming_2023', '2024-05-12 20:45:00'),
-- GitHub password changes
(29, 'ENC_github_old_repos_2023', '2024-06-18 13:20:00');

-- Update some last_used timestamps to show recent activity
UPDATE credentials SET last_used = '2024-07-03 19:45:00' WHERE service_name = 'Gmail';
UPDATE credentials SET last_used = '2024-07-03 18:30:00' WHERE service_name = 'Slack';
UPDATE credentials SET last_used = '2024-07-03 17:15:00' WHERE service_name = 'GitHub';
UPDATE credentials SET last_used = '2024-07-03 16:00:00' WHERE service_name = 'Amazon';
UPDATE credentials SET last_used = '2024-07-03 15:30:00' WHERE service_name = 'PayPal';

-- Sample queries to test the data
/*
-- View all credentials with categories
SELECT c.service_name, c.username, c.email, cat.name as category, c.is_favorite, c.last_used
FROM credentials c
JOIN categories cat ON c.category_id = cat.id
ORDER BY c.last_used DESC;

-- Count credentials by category
SELECT cat.name, COUNT(c.id) as credential_count
FROM categories cat
LEFT JOIN credentials c ON cat.id = c.category_id
GROUP BY cat.id, cat.name
ORDER BY credential_count DESC;

-- Find favorite credentials
SELECT c.service_name, c.username, cat.name as category
FROM credentials c
JOIN categories cat ON c.category_id = cat.id
WHERE c.is_favorite = TRUE
ORDER BY c.service_name;

-- Get recent password changes
SELECT c.service_name, ph.changed_at, ph.old_password_encrypted
FROM password_history ph
JOIN credentials c ON ph.credential_id = c.id
ORDER BY ph.changed_at DESC;

-- Search for specific services
SELECT * FROM credentials WHERE service_name LIKE '%google%' OR service_name LIKE '%amazon%';
*/