-- =====================================================================
-- VELEARN ENTERPRISE LMS & CRM PLATFORM - COMPLETE DATABASE SCHEMA DDL
-- Generated: October 12, 2026
-- Engine: MySQL 8.0+ / MariaDB (InnoDB, utf8mb4_unicode_ci)
-- Total Tables: 91
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


-- ---------------------------------------------------------------------
-- Table: `users`
-- Description: Primary user identity table for students, instructors, counselors, and system administrators.
-- Migration: database/migrations/0001_01_01_000000_create_users_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(255) NULL DEFAULT NULL,
  `last_name` VARCHAR(255) NULL DEFAULT NULL,
  `date_of_birth` DATE NULL DEFAULT NULL,
  `gender` VARCHAR(255) NULL DEFAULT NULL,
  `education` VARCHAR(255) NULL DEFAULT NULL,
  `designation` VARCHAR(255) NULL DEFAULT NULL,
  `address` TEXT NULL DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `email_verified_at` TIME NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phonenumber` VARCHAR(255) NULL DEFAULT NULL,
  `secondary_phone` VARCHAR(255) NULL DEFAULT NULL,
  `lead_source` VARCHAR(255) NULL DEFAULT NULL,
  `fcm_token` VARCHAR(500) NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `deleted_at` TIME NULL DEFAULT NULL,
  `state_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `city` VARCHAR(255) NULL DEFAULT NULL,
  `zip_code` VARCHAR(255) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `password_reset_tokens`
-- Description: Secure temporary tokens for account password resets.
-- Migration: database/migrations/0001_01_01_000000_create_users_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(500) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `sessions`
-- Description: HTTP database session store containing payload and user agent details.
-- Migration: database/migrations/0001_01_01_000000_create_users_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(500) NULL DEFAULT NULL,
  `user_agent` TEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `personal_access_tokens`
-- Description: Laravel Sanctum API tokens for mobile app and SPA client authorization.
-- Migration: database/migrations/2026_09_09_070845_create_personal_access_tokens_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tokenable` VARCHAR(255) NOT NULL,
  `name` TEXT NOT NULL,
  `token` VARCHAR(500) NOT NULL,
  `abilities` TEXT NULL DEFAULT NULL,
  `last_used_at` TIME NULL DEFAULT NULL,
  `expires_at` TIME NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `states`
-- Description: Geographical state lookup table for tax, location analytics, and CRM categorization.
-- Migration: database/migrations/2026_09_09_011000_create_states_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `state_name` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `staff`
-- Description: Extended staff profile linked to authentication accounts with employee code, department, and joining date.
-- Migration: database/migrations/2026_09_09_057000_create_staff_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `auth_id` BIGINT UNSIGNED NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `primary_phone` VARCHAR(255) NOT NULL,
  `secondary_phone` VARCHAR(255) NULL DEFAULT NULL,
  `gender` VARCHAR(255) NULL DEFAULT NULL,
  `role_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `date_of_birth` VARCHAR(255) NULL DEFAULT NULL,
  `education` VARCHAR(255) NULL DEFAULT NULL,
  `address` TEXT NULL DEFAULT NULL,
  `city` VARCHAR(255) NULL DEFAULT NULL,
  `pincode` VARCHAR(255) NULL DEFAULT NULL,
  `state_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `status` INT NOT NULL DEFAULT 1,
  `deleted_at` TIME NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `staff_schedules`
-- Description: Staff operational calendar storing weekly shifts and availability.
-- Migration: database/migrations/2026_09_09_058000_create_staff_schedules_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `staff_schedules`;
CREATE TABLE `staff_schedules` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `staff_id` BIGINT UNSIGNED NOT NULL,
  `day` VARCHAR(255) NOT NULL,
  `schedule_time` TIME NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'available',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `staff_id` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `staff_meetings`
-- Description: Internal employee team meetings, calendar invites, and video links.
-- Migration: database/migrations/2026_09_28_154946_create_staff_meetings_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `staff_meetings`;
CREATE TABLE `staff_meetings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `meeting_type` VARCHAR(255) NOT NULL DEFAULT 'Staff Meeting',
  `title` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `staff_id` BIGINT UNSIGNED NOT NULL,
  `agenda` TEXT NULL DEFAULT NULL,
  `meet_link` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `leads`
-- Description: Core sales inquiry repository storing contact info, status, priority, and telecaller assignment.
-- Migration: database/migrations/2026_09_15_143913_create_leads_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NULL DEFAULT NULL,
  `whatsapp` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `location` VARCHAR(255) NULL DEFAULT NULL,
  `lead_source_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `lead_status_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `priority` VARCHAR(255) NULL DEFAULT 'Medium',
  `assigned_to` BIGINT UNSIGNED NULL DEFAULT NULL,
  `expected_close_date` DATE NULL DEFAULT NULL,
  `converted_date` DATE NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_sources`
-- Description: Acquisition channels (e.g., Google Ads, Meta Ads, Organic, Campus Walk-in).
-- Migration: database/migrations/2026_09_09_012000_create_lead_sources_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_sources`;
CREATE TABLE `lead_sources` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_statuses`
-- Description: Multi-stage sales pipeline status stages (New, Contacted, Demo, Followup, Converted, Lost).
-- Migration: database/migrations/2026_09_15_143912_create_lead_statuses_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_statuses`;
CREATE TABLE `lead_statuses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `color` VARCHAR(255) NOT NULL DEFAULT '#000000',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_tags`
-- Description: Custom tags for lead qualification and behavioral segmentation.
-- Migration: database/migrations/2026_09_09_013000_create_lead_tags_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_tags`;
CREATE TABLE `lead_tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_source_role`
-- Description: Role-based access rules determining which staff roles can view specific lead sources.
-- Migration: database/migrations/2026_09_09_044000_create_lead_source_role_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_source_role`;
CREATE TABLE `lead_source_role` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lead_source_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_status_role`
-- Description: Visibility permissions determining which staff roles can access specific lead stages.
-- Migration: database/migrations/2026_09_16_164238_create_lead_status_role_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_status_role`;
CREATE TABLE `lead_status_role` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lead_status_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_tag_role`
-- Description: Role-scoped access permissions for lead tags.
-- Migration: database/migrations/2026_09_09_045000_create_lead_tag_role_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_tag_role`;
CREATE TABLE `lead_tag_role` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lead_tag_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_lead_tag`
-- Description: Many-to-Many pivot table associating tags with leads.
-- Migration: database/migrations/2026_09_15_143928_create_lead_lead_tag_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_lead_tag`;
CREATE TABLE `lead_lead_tag` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lead_id` BIGINT UNSIGNED NOT NULL,
  `lead_tag_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_lead`
-- Description: Many-to-Many pivot table linking prospective leads to courses of interest.
-- Migration: database/migrations/2026_09_19_145842_create_course_lead_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_lead`;
CREATE TABLE `course_lead` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `lead_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `call_logs`
-- Description: Voice call activity records storing call type, duration, status, audio recording URL, and telecaller notes.
-- Migration: database/migrations/2026_09_09_026000_create_call_logs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `call_logs`;
CREATE TABLE `call_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `contact_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `caller_number` VARCHAR(255) NULL DEFAULT NULL,
  `call_duration` VARCHAR(255) NULL DEFAULT NULL,
  `department` VARCHAR(255) NULL DEFAULT NULL,
  `recording_url` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NULL DEFAULT NULL,
  `call_start` VARCHAR(255) NULL DEFAULT NULL,
  `call_end` VARCHAR(255) NULL DEFAULT NULL,
  `full_log` LONGTEXT NULL DEFAULT NULL,
  `note` TEXT NULL DEFAULT NULL,
  `zoom_meet_link` TEXT NULL DEFAULT NULL,
  `zoom_meeting_id` VARCHAR(255) NULL DEFAULT NULL,
  `followup_note` TEXT NULL DEFAULT NULL,
  `demo_date` VARCHAR(255) NULL DEFAULT NULL,
  `demo_time` VARCHAR(255) NULL DEFAULT NULL,
  `demo_status` VARCHAR(255) NULL DEFAULT NULL,
  `followup_date` VARCHAR(255) NULL DEFAULT NULL,
  `followup_time` VARCHAR(255) NULL DEFAULT NULL,
  `assigned_staff_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `staff_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `followup_status` VARCHAR(255) NULL DEFAULT NULL,
  `direction` VARCHAR(255) NULL DEFAULT NULL,
  `lead_tag` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `lead_id` BIGINT UNSIGNED NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_activities`
-- Description: Historical log of counselor interactions, note taking, and scheduled follow-up dates.
-- Migration: database/migrations/2026_09_15_143914_create_lead_activities_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_activities`;
CREATE TABLE `lead_activities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lead_id` BIGINT UNSIGNED NOT NULL,
  `staff_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `type` VARCHAR(255) NOT NULL,
  `direction` VARCHAR(255) NULL DEFAULT NULL,
  `outcome` VARCHAR(255) NULL DEFAULT NULL,
  `note` TEXT NOT NULL,
  `next_action` VARCHAR(255) NULL DEFAULT NULL,
  `next_followup_date` DATE NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `tag_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `status_id` BIGINT UNSIGNED NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `lead_pipeline_logs`
-- Description: Automated audit trail logging lead pipeline stage transitions over time.
-- Migration: database/migrations/2026_09_19_162845_create_lead_pipeline_logs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `lead_pipeline_logs`;
CREATE TABLE `lead_pipeline_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lead_id` BIGINT UNSIGNED NOT NULL,
  `changed_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `old_status_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `new_status_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `old_tag_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `new_tag_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `followups`
-- Description: Multi-target follow-up task engine coordinating outbound calls for both student prospects and corporate HRs.
-- Migration: database/migrations/2026_10_12_114000_create_followups_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `followups`;
CREATE TABLE `followups` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `followup_code` VARCHAR(255) NOT NULL,
  `type` VARCHAR(255) NOT NULL DEFAULT 'student',
  `student_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `student_name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NULL DEFAULT NULL,
  `course` VARCHAR(255) NULL DEFAULT NULL,
  `company_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `company_name` VARCHAR(255) NOT NULL,
  `hr_name` VARCHAR(255) NULL DEFAULT NULL,
  `hr_phone` VARCHAR(255) NULL DEFAULT NULL,
  `role` VARCHAR(255) NULL DEFAULT NULL,
  `purpose` VARCHAR(255) NOT NULL,
  `call_date` DATE NOT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'completed',
  `created_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `price_approval_requests`
-- Description: Formal discount approval workflow where counselors request management authorization for price concessions.
-- Migration: database/migrations/2026_09_09_055000_create_price_approval_requests_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `price_approval_requests`;
CREATE TABLE `price_approval_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `requested_by` BIGINT UNSIGNED NOT NULL,
  `requested_price` VARCHAR(255) NOT NULL,
  `reason` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `approved_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `courses`
-- Description: Master course catalog holding titles, pricing, course types (live/recorded/hybrid), and syllabus assets.
-- Migration: database/migrations/2026_09_09_016000_create_courses_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_code` VARCHAR(255) NULL DEFAULT NULL,
  `syllabus_pdf` VARCHAR(255) NULL DEFAULT NULL,
  `release_date` VARCHAR(255) NULL DEFAULT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `course_pdf` VARCHAR(255) NULL DEFAULT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `sub_description` TEXT NULL DEFAULT NULL,
  `with_certificate` INT NOT NULL DEFAULT 0,
  `recorded_content` INT NOT NULL DEFAULT 0,
  `language` VARCHAR(255) NULL DEFAULT NULL,
  `cour_language` VARCHAR(255) NULL DEFAULT NULL,
  `categories` TEXT NULL DEFAULT NULL,
  `about_course` LONGTEXT NULL DEFAULT NULL,
  `mrp_price` VARCHAR(255) NOT NULL,
  `buy_price` VARCHAR(255) NOT NULL,
  `single_student_price` VARCHAR(255) NOT NULL,
  `group_student_price` VARCHAR(255) NOT NULL,
  `offer_price` VARCHAR(255) NOT NULL,
  `course_type` VARCHAR(255) NOT NULL,
  `combo_course_type` VARCHAR(255) NULL DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `duration` VARCHAR(255) NULL DEFAULT NULL,
  `end_card_title` VARCHAR(255) NULL DEFAULT NULL,
  `end_card_description` TEXT NULL DEFAULT NULL,
  `live_course_type` VARCHAR(255) NULL DEFAULT NULL,
  `split_count` INT NULL DEFAULT NULL,
  `combo_type` VARCHAR(255) NULL DEFAULT NULL,
  `learning_title` VARCHAR(255) NULL DEFAULT NULL,
  `learning_description` TEXT NULL DEFAULT NULL,
  `why_us_title` VARCHAR(255) NULL DEFAULT NULL,
  `why_us_description` TEXT NULL DEFAULT NULL,
  `why_us_heading_1` VARCHAR(255) NULL DEFAULT NULL,
  `why_us_desc_1` TEXT NULL DEFAULT NULL,
  `why_us_heading_2` VARCHAR(255) NULL DEFAULT NULL,
  `why_us_desc_2` TEXT NULL DEFAULT NULL,
  `why_us_heading_3` VARCHAR(255) NULL DEFAULT NULL,
  `why_us_desc_3` TEXT NULL DEFAULT NULL,
  `why_us_heading_4` VARCHAR(255) NULL DEFAULT NULL,
  `why_us_desc_4` TEXT NULL DEFAULT NULL,
  `skills_title` VARCHAR(255) NULL DEFAULT NULL,
  `skills_description` TEXT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_curricula`
-- Description: Level 1 curriculum modules organizing the subject matter.
-- Migration: database/migrations/2026_09_09_029000_create_course_curricula_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_curricula`;
CREATE TABLE `course_curricula` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `curriculum_descriptions`
-- Description: Level 2 curriculum topics and chapters within modules.
-- Migration: database/migrations/2026_09_09_030000_create_curriculum_descriptions_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `curriculum_descriptions`;
CREATE TABLE `curriculum_descriptions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_curriculum_id` BIGINT UNSIGNED NOT NULL,
  `description` TEXT NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_videos`
-- Description: Level 3 video lecture entities containing video URLs, duration in seconds, and free preview flags.
-- Migration: database/migrations/2026_09_09_038000_create_course_videos_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_videos`;
CREATE TABLE `course_videos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `video` TEXT NOT NULL,
  `subtitle_en` VARCHAR(255) NULL DEFAULT NULL,
  `subtitle_ta` VARCHAR(255) NULL DEFAULT NULL,
  `script` LONGTEXT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_video_progress`
-- Description: Student video playback telemetry tracking watched seconds and completion milestones.
-- Migration: database/migrations/2026_09_09_040000_create_course_video_progress_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_video_progress`;
CREATE TABLE `course_video_progress` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `auth_id` BIGINT UNSIGNED NOT NULL,
  `course_video_id` BIGINT UNSIGNED NOT NULL,
  `last_position` VARCHAR(255) NOT NULL DEFAULT 0,
  `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `student_video_notes`
-- Description: Timestamped bookmark notes taken by students while watching video lectures.
-- Migration: database/migrations/2026_10_06_124938_create_student_video_notes_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `student_video_notes`;
CREATE TABLE `student_video_notes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `batch_topic_log_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_video_summeries`
-- Description: Structured summary notes and cheat sheets for lecture videos.
-- Migration: database/migrations/2026_09_09_041000_create_course_video_summeries_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_video_summeries`;
CREATE TABLE `course_video_summeries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_video_id` BIGINT UNSIGNED NOT NULL,
  `summary` TEXT NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_combos`
-- Description: Special bundled course packages sold together.
-- Migration: database/migrations/2026_09_09_028000_create_course_combos_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_combos`;
CREATE TABLE `course_combos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `class_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_benefits`
-- Description: Highlight points and key benefits of enrolling in the course.
-- Migration: database/migrations/2026_09_09_027000_create_course_benefits_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_benefits`;
CREATE TABLE `course_benefits` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `description` TEXT NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_faqs`
-- Description: Frequently asked questions and answers for course detail pages.
-- Migration: database/migrations/2026_09_09_031000_create_course_faqs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_faqs`;
CREATE TABLE `course_faqs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `question` TEXT NOT NULL,
  `answer` TEXT NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_leans`
-- Description: Key learning outcomes and takeaways acquired from the course.
-- Migration: database/migrations/2026_09_09_032000_create_course_leans_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_leans`;
CREATE TABLE `course_leans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_practices`
-- Description: Practical hands-on lab exercises and software tools taught.
-- Migration: database/migrations/2026_09_09_033000_create_course_practices_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_practices`;
CREATE TABLE `course_practices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_title` VARCHAR(255) NOT NULL,
  `language` VARCHAR(255) NULL DEFAULT NULL,
  `level` VARCHAR(255) NULL DEFAULT NULL,
  `question` TEXT NOT NULL,
  `input` TEXT NULL DEFAULT NULL,
  `actual_output` TEXT NULL DEFAULT NULL,
  `status` INT NOT NULL DEFAULT 1,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_requirements`
-- Description: Prerequisites and required skills prior to course enrollment.
-- Migration: database/migrations/2026_09_09_034000_create_course_requirements_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_requirements`;
CREATE TABLE `course_requirements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_skills`
-- Description: Specific technical and soft skills gained by completing the course.
-- Migration: database/migrations/2026_09_09_035000_create_course_skills_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_skills`;
CREATE TABLE `course_skills` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_testimonials`
-- Description: Student reviews, testimonials, ratings, and social proof.
-- Migration: database/migrations/2026_09_09_036000_create_course_testimonials_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_testimonials`;
CREATE TABLE `course_testimonials` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `content` TEXT NOT NULL,
  `rating` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_users`
-- Description: Direct user-course access and enrollment associations.
-- Migration: database/migrations/2026_09_09_037000_create_course_users_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_users`;
CREATE TABLE `course_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `auth_id` BIGINT UNSIGNED NOT NULL,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `batch_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'ongoing',
  `completed_at` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `batches`
-- Description: Cohort batches with scheduled dates, times, instructor mapping, capacity limits, and video conference links.
-- Migration: database/migrations/2026_09_09_017000_create_batches_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `batches`;
CREATE TABLE `batches` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `batch_name` VARCHAR(255) NOT NULL,
  `batch_code` VARCHAR(255) NOT NULL,
  `batch_type` VARCHAR(255) NOT NULL DEFAULT 'Weekday',
  `batch_track` VARCHAR(255) NOT NULL DEFAULT 'Normal',
  `instructor_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `start_date` VARCHAR(255) NOT NULL,
  `end_date` VARCHAR(255) NULL DEFAULT NULL,
  `batch_from_time` VARCHAR(255) NOT NULL,
  `batch_to_time` VARCHAR(255) NOT NULL,
  `class_duration` VARCHAR(255) NULL DEFAULT NULL,
  `join_deadline` VARCHAR(255) NULL DEFAULT NULL,
  `joined_student_count` INT NOT NULL DEFAULT 0,
  `meeting_link` VARCHAR(255) NULL DEFAULT NULL,
  `max_students` INT NOT NULL DEFAULT 50,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `batch_status` VARCHAR(255) NOT NULL DEFAULT 'Running'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `batch_schedules`
-- Description: Recurring weekly timetable slots for batches.
-- Migration: database/migrations/2026_09_09_023000_create_batch_schedules_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `batch_schedules`;
CREATE TABLE `batch_schedules` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `batch_id` BIGINT UNSIGNED NOT NULL,
  `date` VARCHAR(255) NOT NULL,
  `start_time` VARCHAR(255) NOT NULL,
  `end_time` VARCHAR(255) NOT NULL,
  `meeting_link` TEXT NULL DEFAULT NULL,
  `zoom_meeting_id` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `batch_topic_logs`
-- Description: Daily classroom teaching log recording topics covered, session recording links, class notes, and dates.
-- Migration: database/migrations/2026_10_02_171023_create_batch_topic_logs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `batch_topic_logs`;
CREATE TABLE `batch_topic_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `batch_id` BIGINT UNSIGNED NOT NULL,
  `batch_schedule_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `course_curriculum_id` BIGINT UNSIGNED NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `is_completed` TINYINT(1) NOT NULL DEFAULT 'false',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `status` VARCHAR(255) NULL DEFAULT 'Pending',
  `date` DATE NULL DEFAULT NULL,
  `video` VARCHAR(255) NULL DEFAULT NULL,
  `note` TEXT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `batch_attendances`
-- Description: Daily attendance registry recording student presence, absence, or tardiness per cohort session.
-- Migration: database/migrations/2026_10_11_223000_create_batch_attendances_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `batch_attendances`;
CREATE TABLE `batch_attendances` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `batch_id` BIGINT UNSIGNED NOT NULL,
  `batch_schedule_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `student_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `student_name` VARCHAR(255) NULL DEFAULT NULL,
  `student_email` VARCHAR(255) NOT NULL,
  `zoom_meeting_id` VARCHAR(255) NULL DEFAULT NULL,
  `date` DATE NOT NULL,
  `meeting_duration_minutes` INT NOT NULL DEFAULT 0,
  `attended_duration_minutes` INT NOT NULL DEFAULT 0,
  `attended_duration_seconds` INT NOT NULL DEFAULT 0,
  `attendance_percentage` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(255) NOT NULL DEFAULT 'Leave',
  `remarks` TEXT NULL DEFAULT NULL,
  `join_time` TIME NULL DEFAULT NULL,
  `leave_time` TIME NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `session_bookings`
-- Description: 1-on-1 mentoring and doubt-clearing sessions with Zoom integration.
-- Migration: database/migrations/2026_09_09_059000_create_session_bookings_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `session_bookings`;
CREATE TABLE `session_bookings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` VARCHAR(255) NOT NULL,
  `staff_id` BIGINT UNSIGNED NOT NULL,
  `admission_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `date` VARCHAR(255) NOT NULL,
  `time` VARCHAR(255) NOT NULL,
  `topic` VARCHAR(255) NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `zoom_meeting_id` VARCHAR(255) NULL DEFAULT NULL,
  `zoom_meet_link` TEXT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_quizzes`
-- Description: Timed objective multiple-choice quizzes linked to courses.
-- Migration: database/migrations/2026_09_09_039000_create_course_quizzes_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_quizzes`;
CREATE TABLE `course_quizzes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_video_id` BIGINT UNSIGNED NOT NULL,
  `question` TEXT NOT NULL,
  `option1` VARCHAR(255) NOT NULL,
  `option2` VARCHAR(255) NOT NULL,
  `option3` VARCHAR(255) NULL DEFAULT NULL,
  `answer` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `quiz_results`
-- Description: Student quiz attempts, score percentages, and pass/fail statuses.
-- Migration: database/migrations/2026_10_01_133931_create_quiz_results_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `quiz_results`;
CREATE TABLE `quiz_results` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `auth_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `course_video_id` BIGINT UNSIGNED NOT NULL,
  `total_questions` INT NOT NULL,
  `correct_answers` INT NOT NULL,
  `score` DECIMAL(10, 2) NOT NULL,
  `answers` JSON NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_evaluation_questions`
-- Description: Course and mentor evaluation feedback survey questions.
-- Migration: database/migrations/2026_10_03_105507_create_course_evaluation_questions_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_evaluation_questions`;
CREATE TABLE `course_evaluation_questions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `question` TEXT NOT NULL,
  `marks` INT NOT NULL DEFAULT 1,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `course_evaluation_options`
-- Description: Multi-choice feedback options for evaluation surveys.
-- Migration: database/migrations/2026_10_03_105508_create_course_evaluation_options_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `course_evaluation_options`;
CREATE TABLE `course_evaluation_options` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_evaluation_question_id` BIGINT UNSIGNED NOT NULL,
  `option` VARCHAR(255) NOT NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 'false',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `assignments`
-- Description: Weekly assignment specifications with descriptions and deadlines.
-- Migration: database/migrations/2026_09_09_019000_create_assignments_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `assignments`;
CREATE TABLE `assignments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `batch_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `module` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `document` VARCHAR(255) NULL DEFAULT NULL,
  `due_date` VARCHAR(255) NULL DEFAULT NULL,
  `reveal_date` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `assignment_criteria`
-- Description: Rubric evaluation criteria defining maximum marks per grading dimension.
-- Migration: database/migrations/2026_09_09_020000_create_assignment_criteria_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `assignment_criteria`;
CREATE TABLE `assignment_criteria` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `assignment_id` BIGINT UNSIGNED NOT NULL,
  `criterion` VARCHAR(255) NOT NULL,
  `maximum_mark` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `assignment_submissions`
-- Description: Student assignment code/asset submissions and overall scores.
-- Migration: database/migrations/2026_09_09_021000_create_assignment_submissions_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `assignment_submissions`;
CREATE TABLE `assignment_submissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `assignment_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'uploaded',
  `file_path` VARCHAR(255) NULL DEFAULT NULL,
  `attempts` INT NOT NULL DEFAULT 1,
  `score` INT NULL DEFAULT NULL,
  `attempts_history` LONGTEXT NULL DEFAULT NULL,
  `reupload_approved` VARCHAR(255) NOT NULL DEFAULT 0,
  `grade` VARCHAR(255) NULL DEFAULT NULL,
  `feedback` TEXT NULL DEFAULT NULL,
  `evaluated_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `assignment_score_breakdowns`
-- Description: Granular marks awarded and written feedback per assignment rubric criterion.
-- Migration: database/migrations/2026_09_09_022000_create_assignment_score_breakdowns_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `assignment_score_breakdowns`;
CREATE TABLE `assignment_score_breakdowns` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `assignment_submission_id` BIGINT UNSIGNED NOT NULL,
  `criterion` VARCHAR(255) NOT NULL,
  `max_score` INT NOT NULL,
  `obtained_score` INT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `mini_projects`
-- Description: Mid-course practical project specifications.
-- Migration: database/migrations/2026_09_09_050000_create_mini_projects_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `mini_projects`;
CREATE TABLE `mini_projects` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `batch_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `module` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `document` VARCHAR(255) NULL DEFAULT NULL,
  `due_date` VARCHAR(255) NULL DEFAULT NULL,
  `reveal_date` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'active',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `mini_project_criteria`
-- Description: Rubric evaluation criteria for mini-projects.
-- Migration: database/migrations/2026_09_09_051000_create_mini_project_criteria_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `mini_project_criteria`;
CREATE TABLE `mini_project_criteria` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `mini_project_id` BIGINT UNSIGNED NOT NULL,
  `criterion` VARCHAR(255) NOT NULL,
  `maximum_mark` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `mini_project_submissions`
-- Description: Student project submissions containing GitHub repositories and live URLs.
-- Migration: database/migrations/2026_09_09_052000_create_mini_project_submissions_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `mini_project_submissions`;
CREATE TABLE `mini_project_submissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `mini_project_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'uploaded',
  `file_path` VARCHAR(255) NULL DEFAULT NULL,
  `attempts` INT NOT NULL DEFAULT 1,
  `score` INT NULL DEFAULT NULL,
  `attempts_history` LONGTEXT NULL DEFAULT NULL,
  `reupload_approved` VARCHAR(255) NOT NULL DEFAULT 0,
  `grade` VARCHAR(255) NULL DEFAULT NULL,
  `feedback` TEXT NULL DEFAULT NULL,
  `evaluated_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `mini_project_score_breakdowns`
-- Description: Itemized rubric score breakdowns for mini-project evaluations.
-- Migration: database/migrations/2026_09_09_053000_create_mini_project_score_breakdowns_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `mini_project_score_breakdowns`;
CREATE TABLE `mini_project_score_breakdowns` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `mini_project_submission_id` BIGINT UNSIGNED NOT NULL,
  `criterion` VARCHAR(255) NOT NULL,
  `max_score` INT NOT NULL,
  `obtained_score` INT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `main_projects`
-- Description: Capstone final project specifications bound to course cohorts.
-- Migration: database/migrations/2026_09_09_046000_create_main_projects_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `main_projects`;
CREATE TABLE `main_projects` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `batch_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `module` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `document` VARCHAR(255) NULL DEFAULT NULL,
  `review_1_title` VARCHAR(255) NULL DEFAULT NULL,
  `review_1_deadline` VARCHAR(255) NULL DEFAULT NULL,
  `review_2_title` VARCHAR(255) NULL DEFAULT NULL,
  `review_2_deadline` VARCHAR(255) NULL DEFAULT NULL,
  `review_3_title` VARCHAR(255) NULL DEFAULT NULL,
  `review_3_deadline` VARCHAR(255) NULL DEFAULT NULL,
  `reveal_date` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'active',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `main_project_criteria`
-- Description: Comprehensive enterprise grading rubrics for capstone projects.
-- Migration: database/migrations/2026_09_09_047000_create_main_project_criteria_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `main_project_criteria`;
CREATE TABLE `main_project_criteria` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_project_id` BIGINT UNSIGNED NOT NULL,
  `criterion` VARCHAR(255) NOT NULL,
  `maximum_mark` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `main_project_submissions`
-- Description: Student capstone project codebases and deployment links.
-- Migration: database/migrations/2026_09_09_048000_create_main_project_submissions_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `main_project_submissions`;
CREATE TABLE `main_project_submissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_project_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `review_number` VARCHAR(255) NOT NULL DEFAULT 1,
  `status` VARCHAR(255) NOT NULL DEFAULT 'uploaded',
  `file_path` VARCHAR(255) NULL DEFAULT NULL,
  `attempts` INT NOT NULL DEFAULT 1,
  `score` INT NULL DEFAULT NULL,
  `attempts_history` LONGTEXT NULL DEFAULT NULL,
  `reupload_approved` VARCHAR(255) NOT NULL DEFAULT 0,
  `grade` VARCHAR(255) NULL DEFAULT NULL,
  `feedback` TEXT NULL DEFAULT NULL,
  `evaluated_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `main_project_score_breakdowns`
-- Description: Detailed scoring breakdowns per rubric dimension for capstone projects.
-- Migration: database/migrations/2026_09_09_049000_create_main_project_score_breakdowns_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `main_project_score_breakdowns`;
CREATE TABLE `main_project_score_breakdowns` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_project_submission_id` BIGINT UNSIGNED NOT NULL,
  `criterion` VARCHAR(255) NOT NULL,
  `max_score` INT NOT NULL,
  `obtained_score` INT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `admissions`
-- Description: Formal student admission contracts linking lead, student user, course, and batch.
-- Migration: database/migrations/2026_09_09_018000_create_admissions_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `admissions`;
CREATE TABLE `admissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `contact_id` BIGINT UNSIGNED NOT NULL,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `batch_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `batch_type` VARCHAR(255) NULL DEFAULT NULL,
  `course_fees` VARCHAR(255) NOT NULL,
  `payment_mode` VARCHAR(255) NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'Active',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `lead_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `lead_id` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `payments`
-- Description: Ledger of installment payments, discounts, balance dues, payment methods, and receipt codes.
-- Migration: database/migrations/2026_09_09_054000_create_payments_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `admission_id` BIGINT UNSIGNED NOT NULL,
  `amount` VARCHAR(255) NOT NULL,
  `payment_date` VARCHAR(255) NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'Pending',
  `razorpay_order_id` VARCHAR(255) NULL DEFAULT NULL,
  `razorpay_payment_id` VARCHAR(255) NULL DEFAULT NULL,
  `razorpay_signature` VARCHAR(255) NULL DEFAULT NULL,
  `payment_method` VARCHAR(255) NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `razorpay_payment_link_id` VARCHAR(255) NULL DEFAULT NULL,
  `payment_link_url` VARCHAR(500) NULL DEFAULT NULL,
  `payment_link_expires_at` VARCHAR(255) NULL DEFAULT NULL,
  `payment_link_sent_at` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `razorpay_webhook_logs`
-- Description: Idempotent capture of raw Razorpay webhook events to prevent double processing.
-- Migration: database/migrations/2026_09_09_056000_create_razorpay_webhook_logs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `razorpay_webhook_logs`;
CREATE TABLE `razorpay_webhook_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `event` VARCHAR(255) NOT NULL,
  `razorpay_order_id` VARCHAR(255) NULL DEFAULT NULL,
  `razorpay_payment_id` VARCHAR(255) NULL DEFAULT NULL,
  `razorpay_refund_id` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'received',
  `payload` LONGTEXT NOT NULL,
  `signature` VARCHAR(255) NULL DEFAULT NULL,
  `signature_valid` TINYINT(1) NOT NULL DEFAULT 0,
  `error_message` TEXT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `companies`
-- Description: Corporate partner directory with HR contact details and company profiles.
-- Migration: database/migrations/2026_09_16_162459_create_companies_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `company_name` VARCHAR(255) NOT NULL,
  `city_location` VARCHAR(255) NOT NULL,
  `industry_sector` VARCHAR(255) NOT NULL,
  `company_website` VARCHAR(255) NULL DEFAULT NULL,
  `company_mail_id` VARCHAR(255) NOT NULL,
  `hr_name` VARCHAR(255) NOT NULL,
  `hr_contact_number` VARCHAR(255) NOT NULL,
  `hr_email_id` VARCHAR(255) NOT NULL,
  `linkedin_url` VARCHAR(500) NULL DEFAULT NULL,
  `source_id` BIGINT UNSIGNED NOT NULL,
  `current_openings` TINYINT(1) NOT NULL DEFAULT 0,
  `lead_tag_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `company_activities`
-- Description: Corporate relationship interactions and outreach history.
-- Migration: database/migrations/2026_09_28_182706_create_company_activities_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `company_activities`;
CREATE TABLE `company_activities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `company_id` BIGINT UNSIGNED NOT NULL,
  `staff_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `tag_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `type` VARCHAR(255) NOT NULL DEFAULT 'Call',
  `direction` VARCHAR(255) NULL DEFAULT NULL,
  `outcome` VARCHAR(255) NULL DEFAULT NULL,
  `note` TEXT NOT NULL,
  `next_followup_date` DATE NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `current_openings`
-- Description: Public employment vacancies and career opportunities.
-- Migration: database/migrations/2026_09_09_042000_create_current_openings_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `current_openings`;
CREATE TABLE `current_openings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `company_id` BIGINT UNSIGNED NOT NULL,
  `job_role` VARCHAR(255) NOT NULL,
  `company_mail_id` VARCHAR(255) NULL DEFAULT NULL,
  `hr_name` VARCHAR(255) NULL DEFAULT NULL,
  `hr_contact_number` VARCHAR(255) NULL DEFAULT NULL,
  `hr_email_id` VARCHAR(255) NULL DEFAULT NULL,
  `job_location` VARCHAR(255) NOT NULL,
  `skills_required` VARCHAR(255) NULL DEFAULT NULL,
  `minimum_qualification` VARCHAR(255) NOT NULL,
  `experience_required` VARCHAR(255) NOT NULL,
  `ctc_salary` VARCHAR(255) NOT NULL,
  `number_of_openings` INT NOT NULL DEFAULT 1,
  `application_deadline` VARCHAR(255) NULL DEFAULT NULL,
  `interview_mode` VARCHAR(255) NOT NULL DEFAULT 'Online',
  `interview_process` VARCHAR(255) NULL DEFAULT NULL,
  `bond_service_agmt` VARCHAR(255) NULL DEFAULT NULL,
  `opening_status` VARCHAR(255) NOT NULL DEFAULT 'Active',
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `job_description_type` VARCHAR(255) NULL DEFAULT NULL,
  `job_description_file` VARCHAR(255) NULL DEFAULT NULL,
  `job_description_text` TEXT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_jobs`
-- Description: Exclusive campus recruitment drives and placement openings.
-- Migration: database/migrations/2026_10_06_145126_create_placement_jobs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_jobs`;
CREATE TABLE `placement_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_profiles`
-- Description: Student placement resumes, LinkedIn profiles, and verified GitHub audit records.
-- Migration: database/migrations/2026_10_07_100115_create_placement_profiles_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_profiles`;
CREATE TABLE `placement_profiles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `upload_resume` VARCHAR(255) NULL DEFAULT NULL,
  `resume_status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `resume_feedback` TEXT NULL DEFAULT NULL,
  `portfolio_url` VARCHAR(500) NULL DEFAULT NULL,
  `portfolio_status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `portfolio_feedback` TEXT NULL DEFAULT NULL,
  `upload_project_docs` VARCHAR(255) NULL DEFAULT NULL,
  `project_docs_status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `project_docs_feedback` TEXT NULL DEFAULT NULL,
  `overall_status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `reviewed_at` TIME NULL DEFAULT NULL,
  `reviewed_by` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `github_url` VARCHAR(500) NULL DEFAULT NULL,
  `github_status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `github_feedback` TEXT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_classes`
-- Description: Placement training sessions and interview preparation video lectures.
-- Migration: database/migrations/2026_10_06_231357_create_placement_classes_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_classes`;
CREATE TABLE `placement_classes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `day` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `video_url` VARCHAR(500) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `video_path` VARCHAR(255) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_class_questions`
-- Description: Technical and aptitude screening questions for placement training.
-- Migration: database/migrations/2026_10_07_002148_create_placement_class_questions_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_class_questions`;
CREATE TABLE `placement_class_questions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `placement_class_id` BIGINT UNSIGNED NOT NULL,
  `question` TEXT NOT NULL,
  `marks` INT NOT NULL DEFAULT 1,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_class_options`
-- Description: Multiple choice options for placement class questions.
-- Migration: database/migrations/2026_10_07_002150_create_placement_class_options_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_class_options`;
CREATE TABLE `placement_class_options` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `placement_class_question_id` BIGINT UNSIGNED NOT NULL,
  `option` VARCHAR(255) NOT NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 'false',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_evaluations`
-- Description: Faculty evaluations of student placement readiness.
-- Migration: database/migrations/2026_10_06_145129_create_placement_evaluations_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_evaluations`;
CREATE TABLE `placement_evaluations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `test_timing` INT NOT NULL,
  `correction_type` VARCHAR(255) NOT NULL DEFAULT 'auto',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_evaluation_questions`
-- Description: Readiness evaluation rubric questions.
-- Migration: database/migrations/2026_10_06_145129_create_placement_evaluations_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_evaluation_questions`;
CREATE TABLE `placement_evaluation_questions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `placement_evaluation_id` BIGINT UNSIGNED NOT NULL,
  `question` TEXT NOT NULL,
  `type` VARCHAR(255) NOT NULL DEFAULT 'mcq',
  `marks` INT NOT NULL DEFAULT 1,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_evaluation_answers`
-- Description: Grading scale benchmarks for evaluation questions.
-- Migration: database/migrations/2026_10_06_145129_create_placement_evaluations_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_evaluation_answers`;
CREATE TABLE `placement_evaluation_answers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `placement_evaluation_question_id` BIGINT UNSIGNED NOT NULL,
  `answer` TEXT NOT NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 'false',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `placement_evaluation_question_id` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_evaluation_student_answers`
-- Description: Student responses to placement evaluation questions.
-- Migration: database/migrations/2026_10_06_145129_create_placement_evaluations_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_evaluation_student_answers`;
CREATE TABLE `placement_evaluation_student_answers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `placement_evaluation_id` BIGINT UNSIGNED NOT NULL,
  `placement_evaluation_question_id` BIGINT UNSIGNED NOT NULL,
  `selected_answer_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `text_answer` TEXT NULL DEFAULT NULL,
  `marks_obtained` INT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `placement_evaluation_id` VARCHAR(255) NOT NULL,
  `placement_evaluation_question_id` VARCHAR(255) NOT NULL,
  `selected_answer_id` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `placement_applications`
-- Description: Student job drive applications with status workflow.
-- Migration: database/migrations/2026_10_06_145128_create_placement_applications_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `placement_applications`;
CREATE TABLE `placement_applications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `opening_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `course_user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'applied',
  `is_new` TINYINT(1) NOT NULL DEFAULT 'true',
  `sent_to_hr_at` TIME NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `schedule_interviews`
-- Description: Individual recruitment interview rounds with dates, interviewer notes, and outcomes.
-- Migration: database/migrations/2026_10_07_160525_create_schedule_interviews_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `schedule_interviews`;
CREATE TABLE `schedule_interviews` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `course` VARCHAR(255) NOT NULL,
  `duration` VARCHAR(255) NOT NULL,
  `batch_code` VARCHAR(255) NOT NULL,
  `batch_type` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NULL DEFAULT NULL,
  `location` VARCHAR(255) NULL DEFAULT NULL,
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `interview_mode` VARCHAR(255) NOT NULL,
  `meeting_link` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'scheduled',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `attendance` VARCHAR(255) NULL DEFAULT NULL,
  `reason` TEXT NULL DEFAULT NULL,
  `result` VARCHAR(255) NULL DEFAULT NULL,
  `hr_feedback` TEXT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `support_tickets`
-- Description: Student support and grievance tickets categorized by priority and subject.
-- Migration: database/migrations/2026_09_09_060000_create_support_tickets_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `support_tickets`;
CREATE TABLE `support_tickets` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `reference_id` VARCHAR(255) NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `category` VARCHAR(255) NULL DEFAULT NULL,
  `subject` VARCHAR(255) NULL DEFAULT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `description` TEXT NOT NULL,
  `preferred_date` VARCHAR(255) NULL DEFAULT NULL,
  `preferred_slot` VARCHAR(255) NULL DEFAULT NULL,
  `attachment` VARCHAR(255) NULL DEFAULT NULL,
  `assigned_staff_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `allocated_date` VARCHAR(255) NULL DEFAULT NULL,
  `allocated_slot` VARCHAR(255) NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `support_ticket_allocations`
-- Description: Routing and assignment of support tickets to staff members.
-- Migration: database/migrations/2026_09_09_061000_create_support_ticket_allocations_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `support_ticket_allocations`;
CREATE TABLE `support_ticket_allocations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `support_ticket_id` BIGINT UNSIGNED NOT NULL,
  `staff_id` BIGINT UNSIGNED NOT NULL,
  `allocated_date` VARCHAR(255) NOT NULL,
  `allocated_slot` VARCHAR(255) NOT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'Scheduled',
  `zoom_meet_link` TEXT NULL DEFAULT NULL,
  `zoom_meeting_id` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `contacts`
-- Description: General inquiries submitted through website contact forms.
-- Migration: database/migrations/2026_09_09_015000_create_contacts_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `register_number` VARCHAR(255) NULL DEFAULT NULL,
  `my_operator_id` VARCHAR(255) NULL DEFAULT NULL,
  `caller_number` VARCHAR(255) NULL DEFAULT NULL,
  `secondary_number` VARCHAR(255) NULL DEFAULT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `location` VARCHAR(255) NULL DEFAULT NULL,
  `source` VARCHAR(255) NULL DEFAULT NULL,
  `conversation_start_date` VARCHAR(255) NULL DEFAULT NULL,
  `conversation_end_date` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `ebooks`
-- Description: Downloadable technical ebooks and career preparation guides.
-- Migration: database/migrations/2026_09_09_043000_create_ebooks_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `ebooks`;
CREATE TABLE `ebooks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(255) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `thumbnail` VARCHAR(255) NULL DEFAULT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `is_free` TINYINT(1) NOT NULL DEFAULT 0,
  `price` VARCHAR(255) NOT NULL,
  `status` INT NOT NULL DEFAULT 1,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `blogs`
-- Description: Public technical articles and SEO marketing posts.
-- Migration: database/migrations/2026_09_09_024000_create_blogs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(255) NULL DEFAULT NULL,
  `published_date` VARCHAR(255) NULL DEFAULT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `blog_infos`
-- Description: Metadata, author details, and read counts for blogs.
-- Migration: database/migrations/2026_09_09_025000_create_blog_infos_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `blog_infos`;
CREATE TABLE `blog_infos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `blog_id` BIGINT UNSIGNED NOT NULL,
  `descriptions` LONGTEXT NULL DEFAULT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `webinars`
-- Description: Live workshops and masterclasses with Zoom video integration.
-- Migration: database/migrations/2026_09_09_062000_create_webinars_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `webinars`;
CREATE TABLE `webinars` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `category` VARCHAR(255) NULL DEFAULT NULL,
  `course_level` VARCHAR(255) NULL DEFAULT NULL,
  `date` VARCHAR(255) NULL DEFAULT NULL,
  `from_time` VARCHAR(255) NULL DEFAULT NULL,
  `to_time` VARCHAR(255) NULL DEFAULT NULL,
  `meeting_link` TEXT NULL DEFAULT NULL,
  `seats` INT NOT NULL DEFAULT 0,
  `description` TEXT NULL DEFAULT NULL,
  `professor` VARCHAR(255) NULL DEFAULT NULL,
  `professor_position` VARCHAR(255) NULL DEFAULT NULL,
  `professor_experience` VARCHAR(255) NULL DEFAULT NULL,
  `requirements` TEXT NULL DEFAULT NULL,
  `status` INT NOT NULL DEFAULT 1,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL,
  `zoom_meeting_id` VARCHAR(255) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `webinar_titles`
-- Description: Key highlight topics covered in webinars.
-- Migration: database/migrations/2026_09_09_063000_create_webinar_titles_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `webinar_titles`;
CREATE TABLE `webinar_titles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `webinar_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `webinar_descriptions`
-- Description: Detailed session descriptions and agendas for webinars.
-- Migration: database/migrations/2026_09_09_064000_create_webinar_descriptions_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `webinar_descriptions`;
CREATE TABLE `webinar_descriptions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `webinar_id` BIGINT UNSIGNED NOT NULL,
  `webinar_title_id` BIGINT UNSIGNED NOT NULL,
  `description` TEXT NOT NULL,
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `webinar_users`
-- Description: Student registrations and attendance tracking for webinars.
-- Migration: database/migrations/2026_09_09_065000_create_webinar_users_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `webinar_users`;
CREATE TABLE `webinar_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `webinar_id` BIGINT UNSIGNED NOT NULL,
  `auth_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'joined',
  `created_at` TIME NULL DEFAULT NULL,
  `updated_at` TIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `jobs`
-- Description: Asynchronous background worker queue table for emails, notifications, and heavy jobs.
-- Migration: database/migrations/0001_01_01_000002_create_jobs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` INT NOT NULL,
  `reserved_at` INT NULL DEFAULT NULL,
  `available_at` INT NOT NULL,
  `created_at` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `job_batches`
-- Description: Batch job tracking and completion metrics.
-- Migration: database/migrations/0001_01_01_000002_create_jobs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` TEXT NULL DEFAULT NULL,
  `cancelled_at` INT NULL DEFAULT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `failed_jobs`
-- Description: Log of failed queue executions with exception stack traces.
-- Migration: database/migrations/0001_01_01_000002_create_jobs_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` VARCHAR(255) NOT NULL,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `cache`
-- Description: Atomic key-value data cache store.
-- Migration: database/migrations/0001_01_01_000001_create_cache_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: `cache_locks`
-- Description: Atomic lock mutexes for concurrency control.
-- Migration: database/migrations/0001_01_01_000001_create_cache_table.php
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- RECOMMENDED HIGH-THROUGHPUT COMPOSITE INDEXES
-- =====================================================================

CREATE INDEX IF NOT EXISTS idx_leads_assignee_status ON leads (assigned_to, lead_status_id);
CREATE INDEX IF NOT EXISTS idx_attendance_batch_date ON batch_attendances (batch_id, date);
CREATE INDEX IF NOT EXISTS idx_video_progress_lookup ON course_video_progress (auth_id, course_video_id);
CREATE INDEX IF NOT EXISTS idx_student_video_notes ON student_video_notes (user_id, video_id, timestamp_seconds);
CREATE INDEX IF NOT EXISTS idx_call_logs_lead_date ON call_logs (lead_id, call_start DESC);
CREATE INDEX IF NOT EXISTS idx_placement_applications_job_student ON placement_applications (job_id, student_id);
CREATE INDEX IF NOT EXISTS idx_followups_date_status ON followups (call_date, status, created_by);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
