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
-- Table structure for table `muzicaProiect`
--

DROP TABLE IF EXISTS `muzicaProiect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `muzicaProiect` (
  `id_produs` int NOT NULL,
  `id_album` int NOT NULL AUTO_INCREMENT,
  `gen` varchar(30) DEFAULT NULL,
  `subgen` varchar(30) DEFAULT NULL,
  `artist` varchar(30) DEFAULT NULL,
  `artist_2` varchar(30) DEFAULT NULL,
  `casa_discuri` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_album`),
  KEY `id_produs` (`id_produs`),
  CONSTRAINT `muzica_ibfk_1` FOREIGN KEY (`id_produs`) REFERENCES `produseProiect` (`id_produs`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `muzicaProiect`
--

LOCK TABLES `muzicaProiect` WRITE;
/*!40000 ALTER TABLE `muzicaProiect` DISABLE KEYS */;
INSERT INTO `muzicaProiect` VALUES (27,1,'Metal','Nu-Metal','Linkin Park',NULL,'Warner Bros. Records'),(28,2,'Metal','Nu-Metal','Linkin Park',NULL,'Warner Bros. Records'),(29,3,'Metal','Nu-Metal','Linkin Park','Jay-Z','Roc-A-Fella'),(30,4,'Hip-Hop','Rap','Kanye West','Jay-Z','Roc-A-Fella'),(31,5,'Hip-Hop','Memphis Rap','Freddie Dredd',NULL,'RCA Records'),(32,6,'Metal','Deathcore','Slaughter To Prevail',NULL,'Sumerian Records'),(33,7,'R&B','Synthwave','The Weeknd',NULL,'Republic Records'),(34,8,'Hip-Hop','Industrial Hip-Hop','Yeat',NULL,'Capitol Records'),(35,9,'Pop','Dance Pop','Irina Rimes',NULL,'Global Records'),(36,10,'Pop','Disco','Michael Jackson',NULL,'Epic Records');
/*!40000 ALTER TABLE `muzicaProiect` ENABLE KEYS */;
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
