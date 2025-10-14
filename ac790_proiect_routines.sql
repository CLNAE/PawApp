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
-- Temporary view structure for view `view_filmeProiect`
--

DROP TABLE IF EXISTS `view_filmeProiect`;
/*!50001 DROP VIEW IF EXISTS `view_filmeProiect`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_filmeProiect` AS SELECT 
 1 AS `id_produs`,
 1 AS `nume_produs`,
 1 AS `an_lansare`,
 1 AS `pret_zi`,
 1 AS `stoc`,
 1 AS `id_film`,
 1 AS `regizor`,
 1 AS `gen_1`,
 1 AS `gen_2`,
 1 AS `gen_3`,
 1 AS `actor_cunoscut_1`,
 1 AS `actor_cunoscut_2`,
 1 AS `actor_cunoscut_3`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_muzicaProiect`
--

DROP TABLE IF EXISTS `view_muzicaProiect`;
/*!50001 DROP VIEW IF EXISTS `view_muzicaProiect`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_muzicaProiect` AS SELECT 
 1 AS `id_produs`,
 1 AS `nume_produs`,
 1 AS `an_lansare`,
 1 AS `pret_zi`,
 1 AS `stoc`,
 1 AS `id_album`,
 1 AS `gen`,
 1 AS `subgen`,
 1 AS `artist`,
 1 AS `artist_2`,
 1 AS `casa_discuri`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_jocuriProiect`
--

DROP TABLE IF EXISTS `view_jocuriProiect`;
/*!50001 DROP VIEW IF EXISTS `view_jocuriProiect`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_jocuriProiect` AS SELECT 
 1 AS `id_produs`,
 1 AS `nume_produs`,
 1 AS `an_lansare`,
 1 AS `pret_zi`,
 1 AS `stoc`,
 1 AS `id_joc`,
 1 AS `platforma`,
 1 AS `studio`,
 1 AS `gen_1`,
 1 AS `gen_2`,
 1 AS `gen_3`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `inchirieriCalcul`
--

DROP TABLE IF EXISTS `inchirieriCalcul`;
/*!50001 DROP VIEW IF EXISTS `inchirieriCalcul`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `inchirieriCalcul` AS SELECT 
 1 AS `id_inchiriere`,
 1 AS `id_client`,
 1 AS `id_produs`,
 1 AS `cantitate`,
 1 AS `data_inchiriere`,
 1 AS `perioada_inchiriere`,
 1 AS `data_returnare`,
 1 AS `intarziere`,
 1 AS `pret_zi`,
 1 AS `suma_platita`,
 1 AS `penalizare`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `view_filmeProiect`
--

/*!50001 DROP VIEW IF EXISTS `view_filmeProiect`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`ac790`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `view_filmeProiect` AS select `p`.`id_produs` AS `id_produs`,`p`.`nume_produs` AS `nume_produs`,`p`.`an_lansare` AS `an_lansare`,`p`.`pret_zi` AS `pret_zi`,`p`.`stoc` AS `stoc`,`f`.`id_film` AS `id_film`,`f`.`regizor` AS `regizor`,`f`.`gen_1` AS `gen_1`,`f`.`gen_2` AS `gen_2`,`f`.`gen_3` AS `gen_3`,`f`.`actor_cunoscut_1` AS `actor_cunoscut_1`,`f`.`actor_cunoscut_2` AS `actor_cunoscut_2`,`f`.`actor_cunoscut_3` AS `actor_cunoscut_3` from (`produseProiect` `p` join `filmProiect` `f` on((`p`.`id_produs` = `f`.`id_produs`))) where (`p`.`tip_produs` = 'film') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_muzicaProiect`
--

/*!50001 DROP VIEW IF EXISTS `view_muzicaProiect`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`ac790`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `view_muzicaProiect` AS select `p`.`id_produs` AS `id_produs`,`p`.`nume_produs` AS `nume_produs`,`p`.`an_lansare` AS `an_lansare`,`p`.`pret_zi` AS `pret_zi`,`p`.`stoc` AS `stoc`,`m`.`id_album` AS `id_album`,`m`.`gen` AS `gen`,`m`.`subgen` AS `subgen`,`m`.`artist` AS `artist`,`m`.`artist_2` AS `artist_2`,`m`.`casa_discuri` AS `casa_discuri` from (`produseProiect` `p` join `muzicaProiect` `m` on((`p`.`id_produs` = `m`.`id_produs`))) where (`p`.`tip_produs` = 'muzica') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_jocuriProiect`
--

/*!50001 DROP VIEW IF EXISTS `view_jocuriProiect`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`ac790`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `view_jocuriProiect` AS select `p`.`id_produs` AS `id_produs`,`p`.`nume_produs` AS `nume_produs`,`p`.`an_lansare` AS `an_lansare`,`p`.`pret_zi` AS `pret_zi`,`p`.`stoc` AS `stoc`,`j`.`id_joc` AS `id_joc`,`j`.`platforma` AS `platforma`,`j`.`studio` AS `studio`,`j`.`gen_1` AS `gen_1`,`j`.`gen_2` AS `gen_2`,`j`.`gen_3` AS `gen_3` from (`produseProiect` `p` join `jocProiect` `j` on((`p`.`id_produs` = `j`.`id_produs`))) where (`p`.`tip_produs` = 'joc') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `inchirieriCalcul`
--

/*!50001 DROP VIEW IF EXISTS `inchirieriCalcul`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`ac790`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `inchirieriCalcul` AS select `i`.`id_inchiriere` AS `id_inchiriere`,`i`.`id_client` AS `id_client`,`i`.`id_produs` AS `id_produs`,`i`.`cantitate` AS `cantitate`,`i`.`data_inchiriere` AS `data_inchiriere`,`i`.`perioada_inchiriere` AS `perioada_inchiriere`,`i`.`data_returnare` AS `data_returnare`,greatest((to_days(`i`.`data_returnare`) - to_days((`i`.`data_inchiriere` + interval `i`.`perioada_inchiriere` day))),0) AS `intarziere`,`p`.`pret_zi` AS `pret_zi`,((`p`.`pret_zi` * `i`.`cantitate`) * `i`.`perioada_inchiriere`) AS `suma_platita`,(greatest((to_days(`i`.`data_returnare`) - to_days((`i`.`data_inchiriere` + interval `i`.`perioada_inchiriere` day))),0) * 2) AS `penalizare`,(((`p`.`pret_zi` * `i`.`cantitate`) * `i`.`perioada_inchiriere`) + (greatest((to_days(`i`.`data_returnare`) - to_days((`i`.`data_inchiriere` + interval `i`.`perioada_inchiriere` day))),0) * 2)) AS `total` from (`inchirieriProiect` `i` join `produseProiect` `p` on((`i`.`id_produs` = `p`.`id_produs`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-01 17:53:16
