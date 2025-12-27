-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: hrms
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES ('019b5590-190a-72e9-ba10-64b8355efeb9','default','Project has been created','Project','101d2dd2-f52c-4cbe-b18c-add5a76bea21','created','User','1','{\"attributes\": {\"title\": \"New VPMS Mobile App\", \"status\": \"planning\", \"end_date\": \"2026-03-30T21:00:00.000000Z\", \"is_active\": true, \"start_date\": \"2025-11-30T21:00:00.000000Z\"}}',NULL,'2025-12-25 12:51:05','2025-12-25 12:51:05'),('019b559b-7f3a-7164-9dd1-0cea846489e1','default','Project has been created','Project','921bc0aa-e195-494a-95dc-063ed4f0fe68','created','User','1','{\"attributes\": {\"title\": \"ERP SYSTEM\", \"status\": \"planning\", \"end_date\": \"2026-03-30T21:00:00.000000Z\", \"is_active\": true, \"start_date\": \"2025-11-30T21:00:00.000000Z\"}}',NULL,'2025-12-25 13:03:32','2025-12-25 13:03:32'),('019b55c1-19f4-71c3-9aac-69080fe3f3ce','default','Employee has been updated','Employee','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','updated','User','1','{\"old\": {\"status\": \"active\", \"shift_id\": null, \"employee_code\": \"EMP-0004\"}, \"attributes\": {\"status\": \"active\", \"shift_id\": \"113d982a-174d-4940-957d-6ba7d7a0eae1\", \"employee_code\": \"EMP-0004\"}}',NULL,'2025-12-25 13:44:37','2025-12-25 13:44:37'),('019b55c1-cea0-735b-9587-ae9c472bdd12','default','Employee has been updated','Employee','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','updated','User','1','{\"old\": {\"status\": \"active\", \"shift_id\": \"113d982a-174d-4940-957d-6ba7d7a0eae1\", \"employee_code\": \"EMP-0004\"}, \"attributes\": {\"status\": \"active\", \"shift_id\": \"41270e6e-5b26-411a-952a-95c0ab41345c\", \"employee_code\": \"EMP-0004\"}}',NULL,'2025-12-25 13:45:23','2025-12-25 13:45:23'),('019b55c3-246b-722c-8336-bf59c02ee5e7','default','Employee has been updated','Employee','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','updated','User','1','{\"old\": {\"status\": \"active\", \"shift_id\": \"41270e6e-5b26-411a-952a-95c0ab41345c\", \"employee_code\": \"EMP-0004\"}, \"attributes\": {\"status\": \"active\", \"shift_id\": \"113d982a-174d-4940-957d-6ba7d7a0eae1\", \"employee_code\": \"EMP-0004\"}}',NULL,'2025-12-25 13:46:50','2025-12-25 13:46:50'),('019b55d1-c1a2-713a-883b-fa2d02492055','default','Leave request has been created','Leave','192c0480-c642-43ea-9316-51a77428e3ee','created','User','1','{\"attributes\": {\"status\": \"pending\", \"end_date\": \"2026-01-14T21:00:00.000000Z\", \"start_date\": \"2025-12-26T21:00:00.000000Z\", \"total_days\": 20, \"approved_by\": null}}',NULL,'2025-12-25 14:02:48','2025-12-25 14:02:48'),('019b55d4-c2ea-728e-a8eb-432023100bcc','default','Leave request has been updated','Leave','192c0480-c642-43ea-9316-51a77428e3ee','updated','User','1','{\"old\": {\"status\": \"pending\", \"end_date\": \"2026-01-14T21:00:00.000000Z\", \"start_date\": \"2025-12-26T21:00:00.000000Z\", \"total_days\": 20, \"approved_by\": null}, \"attributes\": {\"status\": \"approved\", \"end_date\": \"2026-01-14T21:00:00.000000Z\", \"start_date\": \"2025-12-26T21:00:00.000000Z\", \"total_days\": 20, \"approved_by\": null}}',NULL,'2025-12-25 14:06:05','2025-12-25 14:06:05');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `ethiopian_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `check_in_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_device` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_device` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status` enum('present','absent','late','half_day','holiday','leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'absent',
  `late_minutes` int NOT NULL DEFAULT '0',
  `early_leave_minutes` int NOT NULL DEFAULT '0',
  `overtime_minutes` int NOT NULL DEFAULT '0',
  `worked_minutes` int NOT NULL DEFAULT '0',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_employee_id_foreign` (`employee_id`),
  KEY `attendances_date_index` (`date`),
  CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (13,'aaa262c7-5c75-405a-ac43-aade32a9f7be','2025-12-22','ታኅሳስ 13, 2018 ዓ.ም','15:49:35','15:50:09','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'half_day',410,100,0,1,NULL,'2025-12-22 12:49:35','2025-12-22 12:50:09'),(14,'e8a270d3-8294-4b42-ae25-5d9d48447c6c','2025-12-22','ታኅሳስ 13, 2018 ዓ.ም','15:51:10','15:51:29','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'half_day',411,99,0,0,NULL,'2025-12-22 12:51:10','2025-12-22 12:51:29');
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `candidates` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cv_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_letter` text COLLATE utf8mb4_unicode_ci,
  `status` enum('new','reviewed','interview','shortlisted','rejected','hired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `hired_at` timestamp NULL DEFAULT NULL,
  `hired_as_employee_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `candidates_email_unique` (`email`),
  KEY `candidates_job_id_foreign` (`job_id`),
  CONSTRAINT `candidates_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidates`
--

LOCK TABLES `candidates` WRITE;
/*!40000 ALTER TABLE `candidates` DISABLE KEYS */;
/*!40000 ALTER TABLE `candidates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES ('1405f4e2-db1d-4110-a5e6-1d2ca513a8ca','Mechanical2','Mec-02','Handles mechanical operations','active','2025-12-25 12:46:33','2025-12-25 12:46:33'),('1e8ae0d5-5054-4e98-ab75-ebcffa205a29','Mechanical4','Mec-04','Handles mechanical operations','active','2025-12-25 12:48:43','2025-12-25 12:48:43'),('927e471a-1728-4699-b3db-34887ed535ec','Mechanical3','Mec-03','Handles mechanical operations','active','2025-12-25 12:46:55','2025-12-25 12:46:55'),('e31ffda6-f00f-49c1-81fa-12f1d45cb299','Software','SWE-01','Handles Software operations','active','2025-12-19 08:28:01','2025-12-19 08:28:01'),('ef6c7d28-644f-400a-99d9-95a740b3a306','Mechanical','Mec-01','Handles mechanical operations','active','2025-12-25 12:44:58','2025-12-25 12:44:58');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `designations`
--

DROP TABLE IF EXISTS `designations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `designations` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `department_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designations_department_id_foreign` (`department_id`),
  CONSTRAINT `designations_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designations`
--

LOCK TABLES `designations` WRITE;
/*!40000 ALTER TABLE `designations` DISABLE KEYS */;
INSERT INTO `designations` VALUES ('fd51312b-711c-4b0a-aab5-03e4793cffa4','test2','uthis is test designation','e31ffda6-f00f-49c1-81fa-12f1d45cb299','active','2025-12-19 08:28:18','2025-12-19 08:28:18');
/*!40000 ALTER TABLE `designations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_personal_infos`
--

DROP TABLE IF EXISTS `employee_personal_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_personal_infos` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_personal_infos_employee_id_unique` (`employee_id`),
  UNIQUE KEY `employee_personal_infos_email_unique` (`email`),
  CONSTRAINT `employee_personal_infos_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_personal_infos`
--

LOCK TABLES `employee_personal_infos` WRITE;
/*!40000 ALTER TABLE `employee_personal_infos` DISABLE KEYS */;
INSERT INTO `employee_personal_infos` VALUES ('40c9b066-55b4-454a-87a1-114b0cdead64','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','simon','si','galedes623@m3player.com','+251911223114',NULL,'1990-05-15','female','single','Ethiopian','Bole, Addis Ababa','Addis Ababa','Addis Ababa','1000','2025-12-25 12:07:16','2025-12-25 13:43:44'),('91c385c3-3453-4f00-9a03-ed511f6e160c','aaa262c7-5c75-405a-ac43-aade32a9f7be','Ketsebaou','Test','testm1aa1n@company.com','+251911223111',NULL,'1990-05-15','male','single','Ethiopian','Bole, Addis Ababa','Addis Ababa','Addis Ababa','1000','2025-12-22 06:42:22','2025-12-22 06:42:22'),('b6eab936-c9c0-4876-a9ed-48c68ea4ac44','445b130f-03ec-4c77-a7b0-be34bc5036a9','Eyuel','Endale','testm1aa1n11@company.com','+251911223115',NULL,'1990-05-15','male','single','Ethiopian','Bole, Addis Ababa','Addis Ababa','Addis Ababa','1000','2025-12-22 06:40:30','2025-12-22 06:40:30'),('d1888559-0586-40b5-859d-a09eabca6170','e8a270d3-8294-4b42-ae25-5d9d48447c6c','Daniel','Yohaness','testm1aa1n1@company.com','+251911223116',NULL,'1990-05-15','male','single','Ethiopian','Bole, Addis Ababa','Addis Ababa','Addis Ababa','1000','2025-12-22 06:41:35','2025-12-22 06:41:35');
/*!40000 ALTER TABLE `employee_personal_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_professional_infos`
--

DROP TABLE IF EXISTS `employee_professional_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_professional_infos` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joining_date` date NOT NULL,
  `ending_date` date DEFAULT NULL,
  `employment_type` enum('full-time','part-time','contract','freelance','intern') COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `transport_allowance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `has_pension` tinyint(1) NOT NULL DEFAULT '1',
  `salary_currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_professional_infos_employee_id_unique` (`employee_id`),
  KEY `employee_professional_infos_department_id_foreign` (`department_id`),
  KEY `employee_professional_infos_designation_id_foreign` (`designation_id`),
  CONSTRAINT `employee_professional_infos_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `employee_professional_infos_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`),
  CONSTRAINT `employee_professional_infos_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_professional_infos`
--

LOCK TABLES `employee_professional_infos` WRITE;
/*!40000 ALTER TABLE `employee_professional_infos` DISABLE KEYS */;
INSERT INTO `employee_professional_infos` VALUES ('0609853f-9740-4458-b02b-027a1fea7cd7','e8a270d3-8294-4b42-ae25-5d9d48447c6c','e31ffda6-f00f-49c1-81fa-12f1d45cb299','fd51312b-711c-4b0a-aab5-03e4793cffa4','2025-01-01',NULL,'full-time',50000.00,0.00,1,'ETB','Commercial Bank of Ethiopia','1000423416186','ET123456113','2025-12-22 06:41:35','2025-12-22 06:41:35'),('4ce2460d-776f-4c60-86a9-621947929d53','445b130f-03ec-4c77-a7b0-be34bc5036a9','e31ffda6-f00f-49c1-81fa-12f1d45cb299','fd51312b-711c-4b0a-aab5-03e4793cffa4','2025-01-01',NULL,'full-time',45000.00,0.00,1,'ETB','Commercial Bank of Ethiopia','1000423416187','ET123456114','2025-12-22 06:40:30','2025-12-22 06:40:30'),('6cdcff2c-d5bd-46d6-96da-162ee4e7a158','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','e31ffda6-f00f-49c1-81fa-12f1d45cb299','fd51312b-711c-4b0a-aab5-03e4793cffa4','2025-01-01',NULL,'full-time',10000.00,0.00,1,'ETB','Commercial Bank of Ethiopia','1000423416185','ET123456115','2025-12-25 12:07:17','2025-12-25 12:07:17'),('c335e666-b9bb-44c3-8858-f3b5c6f433c9','aaa262c7-5c75-405a-ac43-aade32a9f7be','e31ffda6-f00f-49c1-81fa-12f1d45cb299','fd51312b-711c-4b0a-aab5-03e4793cffa4','2025-01-01',NULL,'full-time',10000.00,0.00,1,'ETB','Commercial Bank of Ethiopia','1000423416184','ET123456111','2025-12-22 06:42:22','2025-12-22 06:42:22');
/*!40000 ALTER TABLE `employee_professional_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive','terminated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `shift_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  KEY `employees_shift_id_foreign` (`shift_id`),
  CONSTRAINT `employees_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES ('445b130f-03ec-4c77-a7b0-be34bc5036a9','EMP-0001','active','2025-12-22 06:40:30','2025-12-22 06:40:30',NULL,NULL),('6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','EMP-0004','active','2025-12-25 12:07:16','2025-12-25 13:46:50',NULL,'113d982a-174d-4940-957d-6ba7d7a0eae1'),('aaa262c7-5c75-405a-ac43-aade32a9f7be','EMP-0003','active','2025-12-22 06:42:22','2025-12-25 07:04:47',NULL,NULL),('e8a270d3-8294-4b42-ae25-5d9d48447c6c','EMP-0002','active','2025-12-22 06:41:35','2025-12-22 12:45:41',NULL,'113d982a-174d-4940-957d-6ba7d7a0eae1');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `holidays` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `ethiopian_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('national','religious','company') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'national',
  `is_recurring` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holidays`
--

LOCK TABLES `holidays` WRITE;
/*!40000 ALTER TABLE `holidays` DISABLE KEYS */;
INSERT INTO `holidays` VALUES ('023fca4d-6550-4329-9f61-0fe4294d1575','Meskel','2025-09-27','መስከረም 17, 2018 ዓ.ም','religious',1,1,'Finding of the True Cross','2025-12-17 09:15:10','2025-12-17 09:15:10'),('2ca25cde-ace9-4d2c-a519-09fea9f0ae3b','Ethiopian New Year','2025-09-11','መስከረም 1, 2018 ዓ.ም','national',1,1,'Enkutatash - Ethiopian New Year celebration','2025-12-17 09:15:10','2025-12-17 09:15:10'),('3e98f955-4982-4a62-a12a-41b9a1f5fe46','Patriots Victory Day','2025-05-05','ሚያዝያ 27, 2017 ዓ.ም','national',1,1,'Liberation Day','2025-12-17 09:15:10','2025-12-17 09:15:10'),('6f534b27-c25c-4ab9-95aa-0bc81e68a258','Easter Sunday','2025-04-20','ሚያዝያ 12, 2017 ዓ.ም','religious',0,1,'Ethiopian Orthodox Easter','2025-12-17 09:15:10','2025-12-17 09:15:10'),('b05d2a96-d5db-4d20-a76c-14b4e01d7072','Adwa Victory Day','2025-03-02','የካቲት 23, 2017 ዓ.ም','national',1,1,'Victory of Adwa celebration','2025-12-17 09:15:10','2025-12-17 09:15:10'),('cd9a8e2e-5edf-47e3-8a97-08d9dea21ad1','Ethiopian Christmas','2026-01-07','ታኅሳስ 29, 2018 ዓ.ም','religious',1,1,'Genna - Ethiopian Orthodox Christmas','2025-12-17 09:15:10','2025-12-17 09:15:10'),('d04e22b9-2ed4-4489-85da-49468a868cba','Derg Downfall Day','2025-05-28','ግንቦት 20, 2017 ዓ.ም','national',1,1,'End of Derg regime','2025-12-17 09:15:10','2025-12-17 09:15:10'),('d17f4e05-a677-4c8e-89ba-9ac91d61f93e','Good Friday','2025-04-18','ሚያዝያ 10, 2017 ዓ.ም','religious',0,1,'Ethiopian Orthodox Good Friday','2025-12-17 09:15:10','2025-12-17 09:15:10'),('d6908383-45df-4a28-ad84-a2ed28b81cc0','Ethiopian Epiphany','2026-01-19','ጥር 11, 2018 ዓ.ም','religious',1,1,'Timket - Baptism of Jesus celebration','2025-12-17 09:15:10','2025-12-17 09:15:10'),('fcc770f6-9fa1-4a12-a1f0-bfe87ef0ea73','Labour Day','2025-05-01','ሚያዝያ 23, 2017 ዓ.ም','national',1,1,'International Workers Day','2025-12-17 09:15:10','2025-12-17 09:15:10');
/*!40000 ALTER TABLE `holidays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_postings`
--

DROP TABLE IF EXISTS `job_postings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_postings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `vacancy` int NOT NULL DEFAULT '1',
  `min_salary` decimal(12,2) DEFAULT NULL,
  `max_salary` decimal(12,2) DEFAULT NULL,
  `salary_currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ETB',
  `salary_negotiable` tinyint(1) NOT NULL DEFAULT '0',
  `show_salary` tinyint(1) NOT NULL DEFAULT '1',
  `deadline` date NOT NULL,
  `status` enum('open','closed','on_hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_postings_department_id_foreign` (`department_id`),
  KEY `job_postings_designation_id_foreign` (`designation_id`),
  CONSTRAINT `job_postings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_postings_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_postings`
--

LOCK TABLES `job_postings` WRITE;
/*!40000 ALTER TABLE `job_postings` DISABLE KEYS */;
INSERT INTO `job_postings` VALUES ('86ebc2e1-0deb-4c36-999e-b98618ceaf0c','e31ffda6-f00f-49c1-81fa-12f1d45cb299','fd51312b-711c-4b0a-aab5-03e4793cffa4','Senior Django Developer','Expert needed...',2,NULL,NULL,'ETB',1,1,'2025-12-30','open',1,'2025-12-25 12:57:12','2025-12-25 12:57:12');
/*!40000 ALTER TABLE `job_postings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_types`
--

DROP TABLE IF EXISTS `leave_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leave_types` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_days` int NOT NULL,
  `requires_approval` tinyint(1) NOT NULL DEFAULT '1',
  `is_paid` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_types`
--

LOCK TABLES `leave_types` WRITE;
/*!40000 ALTER TABLE `leave_types` DISABLE KEYS */;
INSERT INTO `leave_types` VALUES ('08c6e194-d5f3-44f2-ac9f-366f73896c37','Unpaid Leave',30,1,0,1,'2025-12-17 09:15:11','2025-12-17 09:15:11'),('0dc29f89-fed0-43d0-bc75-6da238598ff4','Study Leave',10,1,1,1,'2025-12-17 09:15:11','2025-12-17 09:15:11'),('423c724d-d9bb-478d-becb-93e9e121f65a','Compassionate Leave',5,1,1,1,'2025-12-17 09:15:11','2025-12-17 09:15:11'),('57223771-cae9-45ac-8817-88672d0e3bce','Maternity Leave',120,1,1,1,'2025-12-17 09:15:10','2025-12-17 09:15:10'),('650a15d5-b29c-45ae-ba8d-9a0ba033c324','Annual Leave',20,1,1,1,'2025-12-17 09:15:10','2025-12-17 09:15:10'),('668c49d5-9f69-4571-b5ba-3c70ecfabf6e','Bereavement Leave',7,0,1,1,'2025-12-17 09:15:11','2025-12-17 09:15:11'),('79a97be0-e510-42db-b6e5-2a38783839e8','Paternity Leave',5,1,1,1,'2025-12-17 09:15:10','2025-12-17 09:15:10'),('8f098580-94f8-4da5-8a23-39aa04e058f7','Sick Leave',15,0,1,1,'2025-12-17 09:15:10','2025-12-17 09:15:10');
/*!40000 ALTER TABLE `leave_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaves` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leaves_employee_id_foreign` (`employee_id`),
  KEY `leaves_leave_type_id_foreign` (`leave_type_id`),
  KEY `leaves_approved_by_foreign` (`approved_by`),
  CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leaves_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaves`
--

LOCK TABLES `leaves` WRITE;
/*!40000 ALTER TABLE `leaves` DISABLE KEYS */;
INSERT INTO `leaves` VALUES ('192c0480-c642-43ea-9316-51a77428e3ee','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','650a15d5-b29c-45ae-ba8d-9a0ba033c324','2025-12-27','2026-01-15',20,'Family visit Mekelle2','approved',NULL,'2025-12-25 14:06:05','2025-12-25 14:02:48','2025-12-25 14:06:05'),('a8525747-d3ea-45dd-9647-99f2f038c76e','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','650a15d5-b29c-45ae-ba8d-9a0ba033c324','2025-12-27','2026-01-15',20,'Family visit Mekelle2','approved',NULL,'2025-12-25 12:24:53','2025-12-25 12:15:35','2025-12-25 12:24:53');
/*!40000 ALTER TABLE `leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'2025_11_27_074106_create_personal_access_tokens_table',1),(4,'2025_11_28_114251_create_departments_table',1),(5,'2025_11_28_114300_create_designations_table',1),(6,'2025_11_28_123541_create_employees_table',1),(7,'2025_11_28_123556_create_employee_personal_infos_table',1),(8,'2025_11_28_123602_create_employee_professional_infos_table',1),(9,'2025_12_01_131349_add_department_id_to_designations_table',1),(10,'2025_12_01_151100_add_department_id_to_designations_table',1),(11,'2025_12_07_125627_create_attendances_table',1),(12,'2025_12_11_080039_create_leave_types_table',1),(13,'2025_12_12_120001_create_shifts_table',1),(14,'2025_12_12_120002_create_holidays_table',1),(15,'2025_12_12_120003_create_leaves_table',1),(16,'2025_12_12_120004_add_shift_id_to_employees_table',1),(17,'2025_12_15_103000_create_job_postings_table',1),(18,'2025_12_15_103100_create_candidates_table',1),(19,'2025_12_15_104500_add_salary_to_job_postings_table',1),(20,'2025_12_15_133744_create_trainings_table',1),(21,'2025_12_16_070458_create_training_attendees_table',1),(22,'2025_12_16_113304_create_projects_table',1),(23,'2025_12_16_113338_create_project_members_table',1),(24,'2025_12_18_154800_update_project_members_rating_to_decimal',2),(25,'2025_12_18_154900_add_payroll_fields_to_employee_professional_infos',2),(26,'2025_12_18_155000_create_payrolls_table',2),(27,'2025_12_19_100819_create_payrolls_table',3),(28,'2025_12_19_104317_change_rating_to_decimal_in_project_members_table',3),(29,'2025_12_23_145000_create_notifications_table',4),(31,'2025_12_23_153533_add_event_column_to_activity_log_table',5),(32,'2025_12_23_153534_add_batch_uuid_column_to_activity_log_table',5),(34,'2025_12_23_153532_create_activity_log_table',6),(35,'2025_12_25_145000_fix_activity_log_table_id',7),(36,'2025_12_25_155500_fix_notifications_table_notifiable_id',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('0b91218c-d504-4a7e-b5c9-b0efe9ed9306','App\\Notifications\\SystemNotification','Employee','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','{\"title\":\"Leave Approved\",\"message\":\"Your leave request from 2025-12-27 00:00:00 has been approved.\",\"type\":\"success\",\"link\":null}',NULL,'2025-12-25 14:06:27','2025-12-25 14:06:27'),('0f045ebf-035a-4e76-8ed2-e0e2bfc7f62d','App\\Notifications\\SystemNotification','User','1','{\"title\":\"New Leave Request\",\"message\":\"A new leave request from simon is pending approval.\",\"type\":\"info\",\"link\":\"\\/leaves\\/192c0480-c642-43ea-9316-51a77428e3ee\"}','2025-12-25 14:08:53','2025-12-25 14:02:48','2025-12-25 14:08:53'),('17a7e6ef-492f-40c8-ba30-79fb7fc41c50','App\\Notifications\\SystemNotification','Employee','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','{\"title\":\"Project Rated\",\"message\":\"You have been rated 1.25\\/5 for project: New VPMS Mobile App\",\"type\":\"success\",\"link\":\"\\/projects\\/101d2dd2-f52c-4cbe-b18c-add5a76bea21\"}',NULL,'2025-12-25 13:00:25','2025-12-25 13:00:25'),('23ca9700-3306-4b02-bc3a-4a942dd01a3e','App\\Notifications\\SystemNotification','User','1','{\"title\":\"Leave Approved\",\"message\":\"A leave request from simon has been approved.\",\"type\":\"success\",\"link\":\"\\/leaves\\/192c0480-c642-43ea-9316-51a77428e3ee\"}','2025-12-25 14:08:53','2025-12-25 14:06:05','2025-12-25 14:08:53'),('d29146b5-713e-4fcc-b87b-bb42203612ed','App\\Notifications\\SystemNotification','User','1','{\"title\":\"New Job Posted\",\"message\":\"A new job posted Senior Django Developer.\",\"type\":\"info\",\"link\":\"\\/recruitment\\/jobs\\/86ebc2e1-0deb-4c36-999e-b98618ceaf0c\"}','2025-12-25 14:08:53','2025-12-25 12:57:12','2025-12-25 14:08:53'),('d93f91bf-c5ee-4c8d-9bd7-1d1c4f78d81f','App\\Notifications\\SystemNotification','User','1','{\"title\":\"Leave Approved\",\"message\":\"A leave request from simon has been approved.\",\"type\":\"success\",\"link\":\"\\/leaves\\/192c0480-c642-43ea-9316-51a77428e3ee\"}','2025-12-25 14:08:53','2025-12-25 14:06:36','2025-12-25 14:08:53');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payrolls`
--

DROP TABLE IF EXISTS `payrolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payrolls` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `month` int NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `overtime_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `holiday_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `training_incentive` decimal(12,2) NOT NULL DEFAULT '0.00',
  `performance_bonus` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gross_salary` decimal(12,2) NOT NULL,
  `late_deduction` decimal(12,2) NOT NULL DEFAULT '0.00',
  `absent_deduction` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unpaid_leave_deduction` decimal(12,2) NOT NULL DEFAULT '0.00',
  `taxable_income` decimal(12,2) NOT NULL,
  `income_tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `pension_employee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(12,2) NOT NULL,
  `status` enum('draft','locked','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payrolls_employee_id_year_month_unique` (`employee_id`,`year`,`month`),
  CONSTRAINT `payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payrolls`
--

LOCK TABLES `payrolls` WRITE;
/*!40000 ALTER TABLE `payrolls` DISABLE KEYS */;
INSERT INTO `payrolls` VALUES ('be9c39c3-a15c-4b50-9dd4-208c8c7d37f0','aaa262c7-5c75-405a-ac43-aade32a9f7be',2025,12,192.31,0.00,0.00,0.00,0.00,192.31,197.12,0.00,0.00,-4.81,0.00,-0.34,-4.47,'draft',NULL,'2025-12-22 13:24:45','2025-12-22 13:24:45'),('e467c879-ced3-4860-bb19-7b0f516e8efd','e8a270d3-8294-4b42-ae25-5d9d48447c6c',2025,12,961.54,0.00,0.00,0.00,0.00,961.54,988.00,0.00,0.00,-26.46,0.00,-1.85,-24.61,'draft',NULL,'2025-12-22 13:24:45','2025-12-22 13:24:45'),('f74af8ae-ee3f-43d7-ad15-c424bc0d2399','445b130f-03ec-4c77-a7b0-be34bc5036a9',2025,12,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'draft',NULL,'2025-12-22 13:24:44','2025-12-22 13:24:44');
/*!40000 ALTER TABLE `payrolls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (17,'App\\Models\\User',1,'auth_token','3197fc843238b86ea82889ac470d0bfcaf4cebb185d7f6351b637c3bbce07103','[\"*\"]','2025-12-24 09:06:11','2025-12-25 06:34:14','2025-12-24 06:34:14','2025-12-24 09:06:11'),(18,'App\\Models\\User',1,'refresh_token','b3345074533e213681d90a151173227845ac8a1b9ac68677fd495ed75b67192d','[\"*\"]',NULL,'2026-12-24 06:34:14','2025-12-24 06:34:14','2025-12-24 06:34:14'),(23,'User',1,'auth_token','9516f54681b2b00a5c240e50e70eba93be9d89040e078ff0be820484193af3ef','[\"*\"]','2025-12-25 14:09:02','2025-12-26 14:02:39','2025-12-25 14:02:39','2025-12-25 14:09:02'),(24,'User',1,'refresh_token','0e72fc568ce99da204cc71307ddc3ba96274352fb26d71842fdd2bf32b3b9ca8','[\"*\"]',NULL,'2026-12-25 14:02:39','2025-12-25 14:02:39','2025-12-25 14:02:39');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_members`
--

DROP TABLE IF EXISTS `project_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_members` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` decimal(3,2) DEFAULT NULL COMMENT '0.25 to 5.00 in 0.25 steps',
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `rated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_members_project_id_employee_id_unique` (`project_id`,`employee_id`),
  KEY `project_members_employee_id_foreign` (`employee_id`),
  CONSTRAINT `project_members_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_members_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_members`
--

LOCK TABLES `project_members` WRITE;
/*!40000 ALTER TABLE `project_members` DISABLE KEYS */;
INSERT INTO `project_members` VALUES ('6e38d21f-92a7-44c5-a5fe-0f3306762d47','101d2dd2-f52c-4cbe-b18c-add5a76bea21','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022',1.25,'Excellent performance, delivered ahead of schedule!','2025-12-25 13:00:25','2025-12-25 12:51:29','2025-12-25 13:00:25');
/*!40000 ALTER TABLE `project_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('planning','in_progress','completed','on_hold','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planning',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES ('101d2dd2-f52c-4cbe-b18c-add5a76bea21','New VPMS Mobile App','Developing mobile version of VPMS','2025-12-01','2026-03-31','planning',1,'2025-12-25 12:51:05','2025-12-25 12:51:05'),('921bc0aa-e195-494a-95dc-063ed4f0fe68','ERP SYSTEM','Developing modern ERP','2025-12-01','2026-03-31','planning',1,'2025-12-25 13:03:32','2025-12-25 13:03:32');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shifts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `break_start_time` time DEFAULT NULL,
  `break_end_time` time DEFAULT NULL,
  `late_threshold_minutes` int NOT NULL DEFAULT '15',
  `grace_period_minutes` int NOT NULL DEFAULT '15',
  `half_day_minutes` int NOT NULL DEFAULT '240',
  `overtime_rate` decimal(5,2) NOT NULL DEFAULT '1.50',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
INSERT INTO `shifts` VALUES ('113d982a-174d-4940-957d-6ba7d7a0eae1','Day Shift','regular','09:00:00','17:30:00','12:00:00','13:30:00',30,15,350,2.00,1,1,'2025-12-22 06:39:58','2025-12-22 06:39:58'),('41270e6e-5b26-411a-952a-95c0ab41345c','Day2 Shift','regular','09:00:00','17:30:00','12:00:00','13:30:00',30,15,350,2.00,0,1,'2025-12-25 13:45:00','2025-12-25 13:45:00');
/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_attendees`
--

DROP TABLE IF EXISTS `training_attendees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_attendees` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `training_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('registered','attended','absent','certified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'registered',
  `attended_at` timestamp NULL DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `training_attendees_training_id_employee_id_unique` (`training_id`,`employee_id`),
  KEY `training_attendees_employee_id_foreign` (`employee_id`),
  CONSTRAINT `training_attendees_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `training_attendees_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_attendees`
--

LOCK TABLES `training_attendees` WRITE;
/*!40000 ALTER TABLE `training_attendees` DISABLE KEYS */;
INSERT INTO `training_attendees` VALUES ('f9422985-740c-4fcd-9ff5-ad69cdaab4be','abebb2ac-eb62-486b-848c-efe9e6068600','6e7e7e73-80d4-4cdd-afaf-5ca025e4d022','registered',NULL,NULL,'2025-12-25 12:53:22','2025-12-25 12:53:22');
/*!40000 ALTER TABLE `training_attendees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainings`
--

DROP TABLE IF EXISTS `trainings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trainings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `trainer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incentive_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `has_incentive` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('internal','external','certification') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'internal',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trainings`
--

LOCK TABLES `trainings` WRITE;
/*!40000 ALTER TABLE `trainings` DISABLE KEYS */;
INSERT INTO `trainings` VALUES ('abebb2ac-eb62-486b-848c-efe9e6068600','Laravel Advanced Training 2','Deep dive into Laravel 2','2025-12-20','2025-12-22',NULL,NULL,0.00,0,'internal',0,0,'2025-12-18 12:23:53','2025-12-23 12:05:00');
/*!40000 ALTER TABLE `trainings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Admin','admin@hrm.com',NULL,'$2y$12$j.9Nq2/fUL6Pmee0U2Ax6OnBNbxU.Mwgrubq6RG00mMo806g3lKCy',NULL,'2025-12-17 09:15:10','2025-12-17 09:15:10'),(2,'Test User','test@example.com','2025-12-17 09:15:11','$2y$12$I.2QQ05lEGE.tyxr1F6ap.xzamWM6/KbyZyg6l7qhwxic0O6BTd5i','B9LFOc1F0i','2025-12-17 09:15:11','2025-12-17 09:15:11');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-26 11:09:26
