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
-- Table structure for table `filmProiect`
--

DROP TABLE IF EXISTS `filmProiect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filmProiect` (
  `id_produs` int NOT NULL,
  `id_film` int NOT NULL AUTO_INCREMENT,
  `regizor` varchar(30) DEFAULT NULL,
  `gen_1` varchar(20) DEFAULT NULL,
  `gen_2` varchar(20) DEFAULT NULL,
  `gen_3` varchar(20) DEFAULT NULL,
  `actor_cunoscut_1` varchar(30) DEFAULT NULL,
  `actor_cunoscut_2` varchar(30) DEFAULT NULL,
  `actor_cunoscut_3` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_film`),
  KEY `id_produs` (`id_produs`),
  CONSTRAINT `film_ibfk_1` FOREIGN KEY (`id_produs`) REFERENCES `produseProiect` (`id_produs`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filmProiect`
--

LOCK TABLES `filmProiect` WRITE;
/*!40000 ALTER TABLE `filmProiect` DISABLE KEYS */;
INSERT INTO `filmProiect` VALUES (14,1,'Denis Villeneuve','Actiune','Sci-Fi','Cyberpunk','Ryan Gosling','Ana de Armas','Harrison Ford'),(15,2,'Denis Villeneuve','Actiune','Sci-Fi','Drama','Timothee Chalamet','Rebecca Ferguson','Zendaya'),(16,3,'Denis Villeneuve','Actiune','Sci-Fi','Drama','Timothee Chalamet','Rebecca Ferguson','Zendaya'),(17,4,'Newt Arnold','Actiune','Drama','Sport','Jean-Claude Van Damme',NULL,NULL),(18,5,'Mark DiSalle','Actiune','Arte Martiale','Sport','Jean-Claude Van Damme',NULL,NULL),(19,6,'James Cameron','Actiune','Calatorie in timp','Cyberpunk','Arnold Schwarzenegger','Michael Biehn','Linda Hamilton'),(20,7,'James Cameron','Actiune','Sci-Fi','Cyberpunk','Arnold Schwarzenegger','Edward Furlong','Linda Hamilton'),(21,8,'Rob Choen','Actiune','Thriler','Curse','Vin Diesel','Paul Walker','Michelle Rodriguez'),(22,9,'Todd Phillips','Psihologic','Drama','Tragedie','Joaquin Phoenix','Robert De Niro',NULL),(23,10,'Christopher Nolan','Istoric','Drama','Biografie','Cillian Murphy','Emily Blunt','Matt Damon'),(24,11,'Christopher Nolan','Epic','Sci-Fi','Calatorie in timp','Matthew McConaughey','Anne Hathaway','Jessica Chastain'),(25,12,'Quentin Tarantino','Politist','Comedie Neagra','Drama','John Travolta','Samuel L. Jackson','Uma Thurman'),(26,13,'Pascal Laugier','Horror','Tragedie','Psihologic','Catherine Begin','Morjana Alaoui',NULL);
/*!40000 ALTER TABLE `filmProiect` ENABLE KEYS */;
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
