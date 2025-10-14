-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: ac790.cti.ugal.ro    Database: ac790_proiect
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `jocProiect`
--

DROP TABLE IF EXISTS `jocProiect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jocProiect` (
  `id_produs` int NOT NULL,
  `id_joc` int NOT NULL AUTO_INCREMENT,
  `platforma` enum('PC','PS4','PS5','Xbox Series') DEFAULT NULL,
  `studio` varchar(40) DEFAULT NULL,
  `gen_1` varchar(20) DEFAULT NULL,
  `gen_2` varchar(20) DEFAULT NULL,
  `gen_3` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_joc`),
  KEY `id_produs` (`id_produs`),
  CONSTRAINT `joc_ibfk_1` FOREIGN KEY (`id_produs`) REFERENCES `produseProiect` (`id_produs`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jocProiect`
--

LOCK TABLES `jocProiect` WRITE;
/*!40000 ALTER TABLE `jocProiect` DISABLE KEYS */;
INSERT INTO `jocProiect` VALUES (1,1,'PC','FromSoftware','Action','RPG','Souls-like'),(2,2,'PS4','FromSoftware','Action','RPG','Souls-like'),(3,3,'PC','id Software','First Person Shooter','Platformer','Adventure'),(4,4,'PS4','Square Enix','Action','RPG','Adventure'),(5,5,'PC','Rockstar Games','Action','Adventure','Open-World'),(6,6,'Xbox Series','Rockstar Games','Action','Adventure','Open-World'),(7,7,'PS5','Shift UP','Action','RPG','Hack and Slash'),(8,8,'PS5','Team ASOBI','Platformer',NULL,NULL),(9,9,'PC','Bandai Namco','Fighting','Action','Multiplayer'),(10,10,'PS4','Capcom','Fighting','Action','Multiplayer'),(11,11,'Xbox Series','Playground Games','Racing','Arcade','Multiplayer'),(12,12,'Xbox Series','Rare','Action','Adventure','Multiplayer'),(13,13,'PS5','CD Projekt Red','RPG','Adventure','First Person Shooter');
/*!40000 ALTER TABLE `jocProiect` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-01 17:53:15
