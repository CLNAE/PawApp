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
-- Table structure for table `produseProiect`
--

DROP TABLE IF EXISTS `produseProiect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produseProiect` (
  `id_produs` int NOT NULL AUTO_INCREMENT,
  `nume_produs` varchar(50) NOT NULL,
  `an_lansare` int NOT NULL,
  `tip_produs` enum('muzica','film','joc') NOT NULL,
  `pret_zi` int NOT NULL,
  `stoc` int NOT NULL,
  PRIMARY KEY (`id_produs`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produseProiect`
--

LOCK TABLES `produseProiect` WRITE;
/*!40000 ALTER TABLE `produseProiect` DISABLE KEYS */;
INSERT INTO `produseProiect` VALUES (1,'Elden Ring',2022,'joc',5,10),(2,'Bloodborne',2015,'joc',4,5),(3,'Doom Eternal',2020,'joc',3,4),(4,'Final Fantasy XV',2016,'joc',3,2),(5,'Grand Theft Auto V',2013,'joc',3,5),(6,'Grand Theft Auto V',2013,'joc',3,2),(7,'Stellar Blade',2024,'joc',5,8),(8,'Astro Bot',2024,'joc',5,5),(9,'Tekken 8',2024,'joc',5,6),(10,'Street Fighter 6',2023,'joc',5,4),(11,'Forza Horizon 5',2021,'joc',4,3),(12,'Sea of Thieves',2018,'joc',3,3),(13,'Cyberpunk2077',2020,'joc',4,12),(14,'Blade Runner 2049',2017,'film',3,15),(15,'Dune',2021,'film',5,20),(16,'Dune 2',2024,'film',5,20),(17,'Bloodsport',1988,'film',2,2),(18,'Kickboxer',1989,'film',2,4),(19,'The Terminator',1984,'film',2,4),(20,'Terminator 2: Judgment Day',1991,'film',2,6),(21,'Fast and Furious',2001,'film',2,10),(22,'Joker',2019,'film',4,8),(23,'Oppenheimer',2023,'film',5,15),(24,'Interstellar',2014,'film',4,10),(25,'Pulp Fiction',1994,'film',3,5),(26,'Martyrs',2008,'film',3,4),(27,'Meteora',2003,'muzica',3,5),(28,'Hybrid Theory',2000,'muzica',3,5),(29,'Collision Course',2004,'muzica',3,7),(30,'Watch The Throne',2011,'muzica',4,6),(31,'Cease & Disintegrate',2024,'muzica',5,10),(32,'Kostolom',2019,'muzica',4,4),(33,'After Hours',2020,'muzica',4,12),(34,'2093',2024,'muzica',5,10),(35,'Origini',2024,'muzica',5,10),(36,'Thriller',2005,'muzica',5,20);
/*!40000 ALTER TABLE `produseProiect` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-01 17:53:16
