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
-- Table structure for table `clientiProiect`
--

DROP TABLE IF EXISTS `clientiProiect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientiProiect` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `nume_client` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefon` varchar(10) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientiProiect`
--

LOCK TABLES `clientiProiect` WRITE;
/*!40000 ALTER TABLE `clientiProiect` DISABLE KEYS */;
INSERT INTO `clientiProiect` VALUES (1,'Sur Marian','mariansur@gmail.com','0777777777',NULL),(2,'Constantin Florin',NULL,'0712345678',NULL),(3,'Ionascu Florentin','florascu@yahoo.com','0722222222',NULL),(4,'Caraibot Virgil','virgilcar@outlook.com','0745645678','carvi'),(9,'Calin Andrei','test@gmail.com','0900000000','ac790'),(20,'Ana Popescu','ana.popescu@email.com','0712345678','anap'),(21,'Mihai Ionescu','mihai.ionescu@email.ro','0723456789','mihaii'),(22,'Elena Georgescu','elena.g@email.com','0734567890','elenag'),(23,'Andrei Stoica','andrei.s@email.ro','0745678901','andreis'),(24,'Ioana Marinescu','ioana.m@email.ro','0756789012','ioanam'),(25,'Radu Dumitrescu','radu.d@email.com','0767890123','radud'),(26,'Cristina Pavel','cristina.p@email.com','0778901234','cristinap'),(27,'Daniel Matei','daniel.m@email.ro','0789012345','danielm'),(28,'Laura Serban','laura.s@email.com','0790123456','lauras'),(29,'Alexandru Vasile','alex.v@email.ro','0701234567','alexv'),(30,'test','test@gmail.com','1234123412','testeetste');
/*!40000 ALTER TABLE `clientiProiect` ENABLE KEYS */;
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
