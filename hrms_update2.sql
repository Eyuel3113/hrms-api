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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (1,'4376bf20-1c4e-44d3-be5d-86f9a573dec1','2025-12-17','ታኅሳስ 8, 2018 ዓ.ም','13:03:56','13:09:44','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'half_day',0,1010,0,6,NULL,'2025-12-17 10:03:56','2025-12-17 10:09:44'),(2,'c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','2025-12-17','ታኅሳስ 8, 2018 ዓ.ም','13:05:26','13:06:43','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'half_day',245,263,0,1,NULL,'2025-12-17 10:05:26','2025-12-17 10:06:43'),(4,'afc0a87b-6de2-4535-8945-0324473e47f1','2025-12-17','ታኅሳስ 8, 2018 ዓ.ም','08:00:00','12:00:00',NULL,NULL,NULL,NULL,NULL,NULL,'present',0,0,0,240,NULL,'2025-12-17 10:32:29','2025-12-17 10:32:30'),(5,'afc0a87b-6de2-4535-8945-0324473e47f1','2025-12-17','ታኅሳስ 8, 2018 ዓ.ም','14:00:00','18:00:00',NULL,NULL,NULL,NULL,NULL,NULL,'present',0,0,0,240,NULL,'2025-12-17 10:32:30','2025-12-17 10:32:30'),(6,'c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','2025-12-18','ታኅሳስ 9, 2018 ዓ.ም','07:24:13','07:25:43','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'half_day',0,604,0,2,NULL,'2025-12-18 04:24:13','2025-12-18 04:25:43'),(7,'c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','2025-12-18','ታኅሳስ 9, 2018 ዓ.ም','11:47:26','11:52:36','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'half_day',167,337,0,5,NULL,'2025-12-18 08:47:26','2025-12-18 08:52:36'),(8,'c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','2025-12-18','ታኅሳስ 9, 2018 ዓ.ም','11:52:40','14:53:45','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'half_day',173,156,0,181,NULL,'2025-12-18 08:52:40','2025-12-18 11:53:45'),(9,'c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','2025-12-18','ታኅሳስ 9, 2018 ዓ.ም','14:53:55',NULL,'127.0.0.1',NULL,'Web',NULL,9.03314200,38.76210800,'late',354,0,0,0,NULL,'2025-12-18 11:53:55','2025-12-18 11:53:55');
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
INSERT INTO `employee_personal_infos` VALUES ('e0109c97-f3c6-4681-b834-bfe7f2957778','4376bf20-1c4e-44d3-be5d-86f9a573dec1','Night','Employee','night.employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-17 09:56:14','2025-12-17 09:56:14'),('e731200e-a355-4874-871d-a87cf32aa3a8','c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','Regular','Employee','regular.employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-17 09:56:13','2025-12-17 09:56:13'),('fff26f91-2efc-4919-a3dd-46778504e587','afc0a87b-6de2-4535-8945-0324473e47f1','Split','Employee','split.employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-17 09:56:14','2025-12-17 09:56:14');
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
INSERT INTO `employees` VALUES ('4376bf20-1c4e-44d3-be5d-86f9a573dec1','EMP-0003','active','2025-12-17 09:56:14','2025-12-17 09:56:14',NULL,'74c3ee80-8b03-4a54-8abe-73d6da1729a7'),('afc0a87b-6de2-4535-8945-0324473e47f1','EMP-0002','active','2025-12-17 09:56:13','2025-12-17 09:56:13',NULL,'49d0abc9-f1b3-422e-875c-7043149a70e7'),('c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','EMP-0001','active','2025-12-17 09:56:13','2025-12-17 09:56:13',NULL,'62f64b3d-616e-465f-bbf0-2da9f7761e55');
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
INSERT INTO `leaves` VALUES ('16ab1a5a-1f57-42d2-b7aa-97b5987eae64','c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','668c49d5-9f69-4571-b5ba-3c70ecfabf6e','2025-12-25','2025-12-30',6,'Family visit to Bahir Dar','approved',NULL,'2025-12-18 08:08:35','2025-12-18 07:58:52','2025-12-18 08:08:35'),('9f0a8cf4-c67b-4e39-a2da-b4b3c8ba263f','c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','668c49d5-9f69-4571-b5ba-3c70ecfabf6e','2025-12-25','2026-01-05',12,'Family visit Mekelle','pending',NULL,NULL,'2025-12-18 08:45:13','2025-12-18 08:45:13'),('afbf0a5f-040f-407e-a14a-bb911c0bc61c','c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','668c49d5-9f69-4571-b5ba-3c70ecfabf6e','2025-12-25','2025-12-31',7,'Family visit Mekelle2','pending',NULL,NULL,'2025-12-18 08:45:56','2025-12-18 08:45:56'),('cd0a409e-88e7-4225-aa42-0a7d33ee736d','c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','668c49d5-9f69-4571-b5ba-3c70ecfabf6e','2025-12-25','2026-01-05',12,'Family visit Mekelle2','pending',NULL,NULL,'2025-12-18 08:45:36','2025-12-18 08:45:36'),('edb72dc1-66e6-4e23-a9c3-33f64ad6926e','c0eb9a36-bcac-41ca-a4f6-32df2d4868b0','668c49d5-9f69-4571-b5ba-3c70ecfabf6e','2025-12-25','2026-01-05',12,'Family visit Mekelle','approved',NULL,'2025-12-18 08:07:35','2025-12-18 08:05:20','2025-12-18 08:07:35');
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'2025_11_27_074106_create_personal_access_tokens_table',1),(4,'2025_11_28_114251_create_departments_table',1),(5,'2025_11_28_114300_create_designations_table',1),(6,'2025_11_28_123541_create_employees_table',1),(7,'2025_11_28_123556_create_employee_personal_infos_table',1),(8,'2025_11_28_123602_create_employee_professional_infos_table',1),(9,'2025_12_01_131349_add_department_id_to_designations_table',1),(10,'2025_12_01_151100_add_department_id_to_designations_table',1),(11,'2025_12_07_125627_create_attendances_table',1),(12,'2025_12_11_080039_create_leave_types_table',1),(13,'2025_12_12_120001_create_shifts_table',1),(14,'2025_12_12_120002_create_holidays_table',1),(15,'2025_12_12_120003_create_leaves_table',1),(16,'2025_12_12_120004_add_shift_id_to_employees_table',1),(17,'2025_12_15_103000_create_job_postings_table',1),(18,'2025_12_15_103100_create_candidates_table',1),(19,'2025_12_15_104500_add_salary_to_job_postings_table',1),(20,'2025_12_15_133744_create_trainings_table',1),(21,'2025_12_16_070458_create_training_attendees_table',1),(22,'2025_12_16_113304_create_projects_table',1),(23,'2025_12_16_113338_create_project_members_table',1),(24,'2025_12_18_154800_update_project_members_rating_to_decimal',2),(25,'2025_12_18_154900_add_payroll_fields_to_employee_professional_infos',2),(26,'2025_12_18_155000_create_payrolls_table',2),(27,'2025_12_19_100819_create_payrolls_table',3),(28,'2025_12_19_104317_change_rating_to_decimal_in_project_members_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (5,'App\\Models\\User',1,'auth_token','80850025d804a4fdbdb6246516f2f745d3b9f7660ecbd78d86b80686161395c9','[\"*\"]','2025-12-19 08:02:31','2025-12-20 06:51:36','2025-12-19 06:51:36','2025-12-19 08:02:31'),(6,'App\\Models\\User',1,'refresh_token','8dd5f0af63abd56c92aa694ee1ad0a9b5ce083864b1e4b4f50d433492bca5f90','[\"*\"]',NULL,'2026-12-19 06:51:37','2025-12-19 06:51:37','2025-12-19 06:51:37');
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
INSERT INTO `project_members` VALUES ('9678df8a-70c9-42b9-ad07-3126719f8d21','1859679b-5aac-4116-a9f0-0680ba558fb3','4376bf20-1c4e-44d3-be5d-86f9a573dec1',2.25,'Excellent performance, delivered ahead of schedule!','2025-12-18 12:41:15','2025-12-18 05:58:51','2025-12-18 12:41:15'),('b3e9f98f-f81b-4908-957d-e1daa4d674e9','1859679b-5aac-4116-a9f0-0680ba558fb3','afc0a87b-6de2-4535-8945-0324473e47f1',NULL,NULL,NULL,'2025-12-18 05:58:51','2025-12-18 05:58:51'),('eacc7091-d011-4eeb-80b4-c345b59377c1','1859679b-5aac-4116-a9f0-0680ba558fb3','c0eb9a36-bcac-41ca-a4f6-32df2d4868b0',1.25,'Excellent performance, delivered ahead of schedule!','2025-12-19 08:02:22','2025-12-18 05:58:51','2025-12-19 08:02:22');
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
INSERT INTO `projects` VALUES ('1859679b-5aac-4116-a9f0-0680ba558fb3','New VPMS Mobile App','Developing mobile version of VPMS','2025-12-01','2026-03-31','planning',1,'2025-12-18 05:56:31','2025-12-18 05:56:31');
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
INSERT INTO `shifts` VALUES ('49d0abc9-f1b3-422e-875c-7043149a70e7','Split Shift','split','08:00:00','18:00:00','12:00:00','14:00:00',15,15,240,1.50,0,1,'2025-12-17 09:15:10','2025-12-17 09:15:10'),('62f64b3d-616e-465f-bbf0-2da9f7761e55','Day Shift','regular','09:00:00','17:30:00','12:00:00','13:00:00',15,15,240,1.50,1,1,'2025-12-17 09:15:10','2025-12-17 09:15:10'),('74c3ee80-8b03-4a54-8abe-73d6da1729a7','Night Shift','regular','22:00:00','06:00:00',NULL,NULL,15,15,240,2.00,0,1,'2025-12-17 09:15:10','2025-12-17 09:15:10');
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
INSERT INTO `trainings` VALUES ('abebb2ac-eb62-486b-848c-efe9e6068600','Laravel Advanced Training 2','Deep dive into Laravel 2','2025-12-20','2025-12-22',NULL,NULL,0.00,0,'internal',0,1,'2025-12-18 12:23:53','2025-12-18 12:23:53');
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

-- Dump completed on 2025-12-19 11:05:51
