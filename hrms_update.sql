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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (1,'11626494-31e7-4c1d-aecb-0767e1d8b0bc','2025-12-12','ታኅሳስ 3, 2018 ዓ.ም','12:15:01','12:15:22','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'present',0,0,0,0,NULL,'2025-12-12 09:15:01','2025-12-12 09:15:22'),(2,'11626494-31e7-4c1d-aecb-0767e1d8b0bc','2025-12-12','ታኅሳስ 3, 2018 ዓ.ም','12:17:28',NULL,'127.0.0.1',NULL,'Web',NULL,9.03314200,38.76210800,'present',-197,0,0,0,NULL,'2025-12-12 09:17:28','2025-12-12 09:17:28'),(3,'100a39a3-0646-4da4-8977-6d8fee1d41b8','2025-12-12','ታኅሳስ 3, 2018 ዓ.ም','12:39:06','12:41:21','127.0.0.1','127.0.0.1','Web','Web',9.03314200,38.76210800,'half_day',399,79,0,2,NULL,'2025-12-12 09:39:06','2025-12-12 09:41:21'),(4,'100a39a3-0646-4da4-8977-6d8fee1d41b8','2025-12-12','ታኅሳስ 3, 2018 ዓ.ም','12:57:06',NULL,'127.0.0.1',NULL,'Web',NULL,9.03314200,38.76210800,'late',417,0,0,0,NULL,'2025-12-12 09:57:06','2025-12-12 09:57:06');
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
INSERT INTO `candidates` VALUES ('300fbe3a-0726-489b-b984-1d788d014d6a','826ce8ad-9dc3-43c4-aef2-d70fc0d150ae','genet solomon','abebe1@gmail.com','0911111111','candidates/cv/de3605d6-78fd-42bf-8fa0-e505db9e499e.pdf',NULL,'hired',NULL,NULL,'2025-12-15 09:15:57','2025-12-15 09:45:16'),('60a3ef54-97c1-4ce6-8faa-eee57965cac4','826ce8ad-9dc3-43c4-aef2-d70fc0d150ae','abebe kebede','abebe@gmail.com','0911111111','candidates/cv/ed8712cf-e7d3-450f-9f58-4e5bbf6c77cb.pdf',NULL,'new',NULL,NULL,'2025-12-15 05:14:40','2025-12-15 05:14:40');
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
INSERT INTO `departments` VALUES ('3f70cf6c-647e-481e-b28c-bb9d7962b924','Software','SWE-01','Handles Software operations','active','2025-12-12 08:56:43','2025-12-12 08:56:43'),('e91099be-8b05-4396-aec3-2cb276434966','Software2','SWE-02','Handles Software operations','active','2025-12-15 04:48:14','2025-12-15 04:48:14');
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
INSERT INTO `designations` VALUES ('3e3c4b27-6fdf-4412-a1f7-28546a985be8','test11','uthis is test designation','3f70cf6c-647e-481e-b28c-bb9d7962b924','active','2025-12-12 08:57:00','2025-12-12 08:57:00'),('6ec158bb-4987-44b7-b7c6-4675b792f0e1','test2','uthis is test designation','e91099be-8b05-4396-aec3-2cb276434966','active','2025-12-15 04:48:39','2025-12-15 04:48:39');
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
INSERT INTO `employee_personal_infos` VALUES ('18d9ce93-3b96-4fa1-ab40-5a96d48da299','100a39a3-0646-4da4-8977-6d8fee1d41b8','test','man','testm1aan11@company.com','+251911223105',NULL,'1990-05-15','male','single','Ethiopian','Bole, Addis Ababa','Addis Ababa','Addis Ababa','1000','2025-12-12 08:58:13','2025-12-12 08:58:13'),('291b94f5-9fb9-482d-8af7-daf2e6d16805','11626494-31e7-4c1d-aecb-0767e1d8b0bc','test','man','testm1aa1n11@company.com','+251911223115',NULL,'1990-05-15','male','single','Ethiopian','Bole, Addis Ababa','Addis Ababa','Addis Ababa','1000','2025-12-12 09:13:49','2025-12-12 09:13:49'),('f29adea5-30e4-4f29-9bb1-326b1eeab28b','7dcdce9b-2a16-4bba-a02e-19d58285c44d','genet','solomon','abebe1@gmail.com','0911111111',NULL,NULL,'female',NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-15 09:45:16','2025-12-15 09:51:47');
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
INSERT INTO `employee_professional_infos` VALUES ('1da1ab5e-ed4b-4f50-8b43-2ebcd54a44d1','11626494-31e7-4c1d-aecb-0767e1d8b0bc','3f70cf6c-647e-481e-b28c-bb9d7962b924','3e3c4b27-6fdf-4412-a1f7-28546a985be8','2025-01-01',NULL,'full-time',45000.00,'USD','Commercial Bank of Ethiopia','1000423416187','ET123456114','2025-12-12 09:13:49','2025-12-12 09:13:49'),('cf2b3ba2-c32d-4557-8f17-317de723bf8f','100a39a3-0646-4da4-8977-6d8fee1d41b8','3f70cf6c-647e-481e-b28c-bb9d7962b924','3e3c4b27-6fdf-4412-a1f7-28546a985be8','2025-01-01',NULL,'full-time',45000.00,'USD','Commercial Bank of Ethiopia','1000123416187','ET123456117','2025-12-12 08:58:13','2025-12-12 08:58:13');
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
INSERT INTO `employees` VALUES ('100a39a3-0646-4da4-8977-6d8fee1d41b8','EMP-0001','active','2025-12-12 08:58:13','2025-12-12 09:13:09',NULL,'42f5dd09-7ef4-40a8-ae68-92a3785e8e28'),('11626494-31e7-4c1d-aecb-0767e1d8b0bc','EMP-0002','active','2025-12-12 09:13:49','2025-12-12 09:13:49',NULL,NULL),('416aef0b-25e9-4b31-98ad-6331b57e03ff','EMP-0004','active','2025-12-15 09:47:18','2025-12-15 09:47:18',NULL,NULL),('7dcdce9b-2a16-4bba-a02e-19d58285c44d','EMP-0003','active','2025-12-15 09:45:16','2025-12-15 09:45:16',NULL,NULL),('8ea956b1-93be-4d84-90df-caca6416dd77','EMP-0005','active','2025-12-15 09:49:22','2025-12-15 09:49:22',NULL,NULL);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
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
INSERT INTO `holidays` VALUES ('167a9f74-b884-4fd0-ae1e-b998ad32f9eb','Adwa Victory Day','2025-03-02','የካቲት 23, 2017 ዓ.ም','national',1,1,'Victory of Adwa celebration','2025-12-12 06:00:39','2025-12-12 06:00:39'),('1c6899e2-45fe-44cb-a427-ac7b2e46d09e','Meskel','2025-09-27','መስከረም 17, 2018 ዓ.ም','religious',1,1,'Finding of the True Cross','2025-12-12 06:00:39','2025-12-12 06:00:39'),('24f16029-6725-4c32-b145-f77ff9e82333','Easter Sunday','2025-04-20','ሚያዝያ 12, 2017 ዓ.ም','religious',0,1,'Ethiopian Orthodox Easter','2025-12-12 06:00:40','2025-12-12 06:00:40'),('2b2406aa-7d8d-4c00-bd6c-434b66aa6eb0','Patriots Victory Day','2025-05-05','ሚያዝያ 27, 2017 ዓ.ም','national',1,1,'Liberation Day','2025-12-12 06:00:40','2025-12-12 06:00:40'),('2bfb8597-cc54-494f-8129-93e7db4b858d','Ethiopian Epiphany','2026-01-19','ጥር 11, 2018 ዓ.ም','religious',1,1,'Timket - Baptism of Jesus celebration','2025-12-12 06:00:39','2025-12-12 06:00:39'),('3bd5e981-0695-437c-85fc-eb1a0e7fdb5c','Derg Downfall Day','2025-05-28','ግንቦት 20, 2017 ዓ.ም','national',1,1,'End of Derg regime','2025-12-12 06:00:40','2025-12-12 06:00:40'),('58029c50-cacf-44e8-b385-bec125329a11','Ethiopian Christmas','2026-01-07','ታኅሳስ 29, 2018 ዓ.ም','religious',1,1,'Genna - Ethiopian Orthodox Christmas','2025-12-12 06:00:39','2025-12-12 06:00:39'),('bb1ebcbb-53c7-4690-a9c4-00d6ecb53829','Labour Day','2025-05-01','ሚያዝያ 23, 2017 ዓ.ም','national',1,1,'International Workers Day','2025-12-12 06:00:40','2025-12-12 06:00:40'),('cf184ad0-f96f-4a3f-bb37-178be8994e5f','Ethiopian New Year','2025-09-11','መስከረም 1, 2018 ዓ.ም','national',1,1,'Enkutatash - Ethiopian New Year celebration','2025-12-12 06:00:39','2025-12-12 06:00:39'),('ffc12669-3aa3-490c-8b64-e2d840ab441a','Good Friday','2025-04-18','ሚያዝያ 10, 2017 ዓ.ም','religious',0,1,'Ethiopian Orthodox Good Friday','2025-12-12 06:00:39','2025-12-12 06:00:39');
/*!40000 ALTER TABLE `holidays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
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
INSERT INTO `job_postings` VALUES ('4f7a7ebe-0702-40c1-ae1e-6474423985c4','e91099be-8b05-4396-aec3-2cb276434966','6ec158bb-4987-44b7-b7c6-4675b792f0e1','Senior Django Developer','Expert needed...',2,15000.00,25000.00,'ETB',1,1,'2025-12-30','open',1,'2025-12-15 05:52:52','2025-12-15 05:52:52'),('5f75a5c4-da14-4937-8cbb-10e5ec3a17ec','e91099be-8b05-4396-aec3-2cb276434966','6ec158bb-4987-44b7-b7c6-4675b792f0e1','Senior Django Developer','Expert needed...',2,NULL,NULL,'ETB',1,1,'2025-12-30','open',1,'2025-12-15 05:53:15','2025-12-15 05:53:15'),('60096426-10d5-4ccc-a981-ab8e7dcf4d0c','3f70cf6c-647e-481e-b28c-bb9d7962b924','3e3c4b27-6fdf-4412-a1f7-28546a985be8','Senior Laravel Developer 2','We are looking for a Laravel expert...',2,NULL,NULL,'ETB',0,1,'2025-12-30','open',1,'2025-12-15 04:37:06','2025-12-15 04:37:06'),('826ce8ad-9dc3-43c4-aef2-d70fc0d150ae','3f70cf6c-647e-481e-b28c-bb9d7962b924','3e3c4b27-6fdf-4412-a1f7-28546a985be8','Senior Django Developer','We are looking for a Laravel expert...',2,NULL,NULL,'ETB',0,1,'2025-12-30','open',0,'2025-12-15 04:38:30','2025-12-15 05:18:53'),('b2662a38-134d-445c-93d4-b48b50e51b40','e91099be-8b05-4396-aec3-2cb276434966','6ec158bb-4987-44b7-b7c6-4675b792f0e1','Senior Django Developer','Expert needed...',2,15000.00,25000.00,'ETB',1,1,'2025-12-30','open',1,'2025-12-15 05:53:31','2025-12-15 05:54:57'),('d52e8794-5a02-4f34-bf07-439feec23288','3f70cf6c-647e-481e-b28c-bb9d7962b924','3e3c4b27-6fdf-4412-a1f7-28546a985be8','Senior Laravel Developer','We are looking for a Laravel expert...',2,NULL,NULL,'ETB',0,1,'2025-12-30','open',1,'2025-12-15 04:33:43','2025-12-15 04:33:43');
/*!40000 ALTER TABLE `job_postings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
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
INSERT INTO `leave_types` VALUES ('06b31442-c00a-49c4-a253-a64421711566','Paternity Leave',5,1,1,1,'2025-12-12 06:00:40','2025-12-12 06:00:40'),('074f6a27-f063-4d10-970b-bb6cd56a2414','Study Leave',10,1,1,1,'2025-12-12 06:00:40','2025-12-12 06:00:40'),('3811d2e3-b9c6-4fe1-b245-c7cd70e43ff6','Unpaid Leave',30,1,0,1,'2025-12-12 06:00:40','2025-12-12 06:00:40'),('44efaab7-d0f7-4a22-ab6f-419c2a867330','Bereavement Leave',7,0,1,1,'2025-12-12 06:00:40','2025-12-12 06:00:40'),('6b91b8b8-1d7f-4c53-95df-c4994f9df80b','Annual Leave',20,1,1,1,'2025-12-12 06:00:40','2025-12-12 06:00:40'),('c4620f3d-d3a0-4592-9def-ee0148c84bfe','Maternity Leave',120,1,1,1,'2025-12-12 06:00:40','2025-12-12 06:00:40'),('e19a1fb3-7ea0-437d-9ee0-242f1a4f0a2f','Sick Leave',15,0,1,1,'2025-12-12 06:00:40','2025-12-12 06:00:40'),('f98c26d4-b581-4066-a709-fdf306e6f946','Compassionate Leave',5,1,1,1,'2025-12-12 06:00:40','2025-12-12 06:00:40');
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
INSERT INTO `leaves` VALUES ('ddb878a6-a3ef-450d-bc80-1df8516e97a9','100a39a3-0646-4da4-8977-6d8fee1d41b8','6b91b8b8-1d7f-4c53-95df-c4994f9df80b','2025-12-25','2025-12-30',6,'Family visit to Bahir Dar','pending',NULL,NULL,'2025-12-12 09:43:31','2025-12-12 09:43:31');
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_11_27_074106_create_personal_access_tokens_table',1),(5,'2025_11_28_114251_create_departments_table',1),(6,'2025_11_28_114300_create_designations_table',1),(7,'2025_11_28_123541_create_employees_table',1),(8,'2025_11_28_123556_create_employee_personal_infos_table',1),(9,'2025_11_28_123602_create_employee_professional_infos_table',1),(10,'2025_12_01_131349_add_department_id_to_designations_table',1),(11,'2025_12_01_151100_add_department_id_to_designations_table',1),(12,'2025_12_07_125627_create_attendances_table',1),(13,'2025_12_11_080039_create_leave_types_table',1),(14,'2025_12_12_120001_create_shifts_table',1),(15,'2025_12_12_120002_create_holidays_table',1),(16,'2025_12_12_120003_create_leaves_table',1),(17,'2025_12_12_120004_add_shift_id_to_employees_table',1),(18,'2025_12_15_103000_create_job_postings_table',2),(19,'2025_12_15_103100_create_candidates_table',2),(20,'2025_12_15_084949_add_salary_to_job_postings_table',3);
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
INSERT INTO `personal_access_tokens` VALUES (23,'App\\Models\\User',1,'auth_token','24f4e0ae2c750c4f4540d4efdd75e1ce23f1356c4da7e57cd817f60fd8cebb96','[\"*\"]','2025-12-15 09:56:29','2025-12-16 05:30:55','2025-12-15 05:30:55','2025-12-15 09:56:29'),(24,'App\\Models\\User',1,'refresh_token','fb05ee4c43f235bfa7df4c98a2057d5594ac9dbf9cc5d60c21a83597e5ef2401','[\"*\"]',NULL,'2026-12-15 05:30:56','2025-12-15 05:30:56','2025-12-15 05:30:56');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
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
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `late_threshold_minutes` int NOT NULL DEFAULT '15',
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
INSERT INTO `shifts` VALUES ('42f5dd09-7ef4-40a8-ae68-92a3785e8e28','Morning Shift','06:00:00','14:00:00',15,240,1.50,0,1,'2025-12-12 06:00:39','2025-12-12 06:00:39'),('a5508eb8-37a2-4ab8-9a5e-872f8c98132f','Night Shift','22:00:00','06:00:00',15,240,2.00,0,1,'2025-12-12 06:00:39','2025-12-12 06:00:39'),('afcae9e0-6e83-4b42-92b8-8362bd1e220d','Evening Shift','14:00:00','22:00:00',15,240,1.75,0,1,'2025-12-12 06:00:39','2025-12-12 06:00:39'),('b3b54b78-21e5-43f5-9b54-ccc632429332','Day Shift','09:00:00','17:30:00',15,240,1.50,1,1,'2025-12-12 06:00:39','2025-12-12 06:00:39');
/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
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
INSERT INTO `users` VALUES (1,'System Admin','admin@hrm.com',NULL,'$2y$12$yBWGPtzvHWnSDznQphKW8OWz.I3wQhh1BDduvU5yjeLtiQJ0TvGnC',NULL,'2025-12-12 06:00:39','2025-12-12 06:00:39'),(2,'Test User','test@example.com','2025-12-12 06:00:42','$2y$12$m1PScJdRBlHFq1VW1Hawj.GXeHDJcut5X9AW7HgZbg.QmhmZFAkJW','RVU3goM01L','2025-12-12 06:00:42','2025-12-12 06:00:42');
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

-- Dump completed on 2025-12-15 16:06:55
