-- MySQL dump 10.13  Distrib 8.4.2, for Win64 (x86_64)
--
-- Host: localhost    Database: image_comment_board
-- ------------------------------------------------------
-- Server version	8.4.2

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
-- Table structure for table `image_table`
--

DROP TABLE IF EXISTS `image_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `image_table` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `image_title` varchar(255) NOT NULL,
  `image_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `image_a` varchar(255) NOT NULL,
  `image_comment` int DEFAULT '0',
  `image_love` int DEFAULT '0',
  `uid` int NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `image_table_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user_table` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_table`
--

LOCK TABLES `image_table` WRITE;
/*!40000 ALTER TABLE `image_table` DISABLE KEYS */;
INSERT INTO `image_table` VALUES (12,'绗竴娆¤鍜孉I鑱婂嚭鎰熷徆鍙锋潵','2024-12-22 14:09:57','uploads/6767ad3582b77.png',3,4,3),(13,'','2024-12-22 14:22:24','uploads/6767b0207daaf.png',3,3,6),(14,'','2024-12-22 14:26:00','uploads/6767b0f882e36.png',3,2,4),(15,'鏈€鏂扮垎鏂欐秷鎭細涓変綋鍔ㄧ敾瑕侀噸鍚簡','2024-12-22 14:49:01','uploads/6767b65d1ba3f.png',4,1,11),(16,'鍥藉妯″瀷绔炴妧鍦鸿瘎娴嬭棰戞ā鍨嬫帓琛屾','2024-12-22 15:14:53','uploads/6767bc6d58f26.png',4,1,4),(17,'AI鑳嗗ぇ鍏氱湡浜哄寲杞粯','2024-12-22 15:22:07','uploads/6767be1f2f11a.png',0,0,6),(18,'楂樻俯鐜鍙兘鍔犻€熻“鑰?,'2024-12-22 15:30:36','uploads/6767c01c35b03.png',3,1,6),(19,'浣犲拰鏈嬪弸鍏变韩寰敓鐗╃兢','2024-12-22 15:38:49','uploads/6767c20905597.png',0,0,9),(20,'鑲ヨ儢鈥滆蹇嗏€濆啓鍦ㄧ粏鑳為噷','2024-12-22 15:44:21','uploads/6767c355d038c.png',2,0,9),(21,'璁板繂涓嶄粎瀛樺湪浜庡ぇ鑴戜腑','2024-12-22 15:49:45','uploads/6767c499ec8d0.png',0,0,7),(22,'浜虹被棣栨姊﹀浜ゆ祦','2024-12-22 15:53:35','uploads/6767c57f304d2.png',0,0,7);
/*!40000 ALTER TABLE `image_table` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_img_number_after_insert` AFTER INSERT ON `image_table` FOR EACH ROW BEGIN
    UPDATE webdata_table
    SET img_number = img_number + 1
    WHERE webdata_id = 1;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_img_number_after_delete` AFTER DELETE ON `image_table` FOR EACH ROW BEGIN
    UPDATE webdata_table
    SET img_number = img_number - 1
    WHERE webdata_id = 1;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `images_comment_table`
--

DROP TABLE IF EXISTS `images_comment_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `images_comment_table` (
  `comment_id` int NOT NULL AUTO_INCREMENT,
  `image_id` int NOT NULL,
  `uid` int NOT NULL,
  `comment_txt` text NOT NULL,
  `comment_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `comment_love` int DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `image_id` (`image_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `images_comment_table_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `image_table` (`image_id`) ON DELETE CASCADE,
  CONSTRAINT `images_comment_table_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user_table` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images_comment_table`
--

LOCK TABLES `images_comment_table` WRITE;
/*!40000 ALTER TABLE `images_comment_table` DISABLE KEYS */;
INSERT INTO `images_comment_table` VALUES (3,12,4,'楠椾綘鐨?,'2024-12-22 14:14:07',1),(4,12,5,'绗戞浜?,'2024-12-22 14:18:13',1),(6,12,6,'缁欐垜鍢村攪绗戣浜?,'2024-12-22 14:20:35',0),(7,13,6,'鐡滃瓙鐨紵','2024-12-22 14:23:13',0),(8,13,7,'閫嗗ぉ','2024-12-22 14:23:41',0),(9,13,8,'閮芥槸涔扮摐瀛愶紝浼氬憳鑺卞悓鏍风殑閽变拱鍒扮殑鐡滃瓙鐨洿灏戯紝杩欎笉鏄緢鍚堢悊鍢?,'2024-12-22 14:24:09',0),(10,14,9,'杩欒繕涓嶇畝鍗曪紝鍏堟妸妗屽瓙鎽嗗ソ锛屽啀鐩栨埧瀛?,'2024-12-22 14:26:49',1),(11,14,3,'鍦ㄦ瀛愬皬鏃跺€欏氨鎼繘鍘讳簡','2024-12-22 14:27:18',0),(12,14,10,'鎸塀鏀捐繘鑳屽寘锛屽啀鎷垮嚭鏉ュ氨濂戒簡','2024-12-22 14:27:48',0),(13,15,12,'鎴戝嫆涓?0澶氬紑锛屼粈涔堝ぇ鎵?,'2024-12-22 14:50:03',1),(14,15,4,'涓囩淮鐚槸鐗堟潈鏂癸紝瀹為檯鍒朵綔鐨勫皯锛岄兘鏄垎鍙戠粰鍚勪釜鍏徃鍒朵綔锛屼粬浠礋璐ｅ墠鏈熺珛椤瑰拰姒傚康璁捐','2024-12-22 14:50:22',0),(15,15,13,'闃垮Ж锛岀湡鑳介€嗚浆鍚楋紵','2024-12-22 14:51:08',0),(16,15,14,'鎰熻鑽父','2024-12-22 14:52:45',0),(17,16,15,'Runway鐨勫疄鏃舵€э紝Luma鐨勬暣浣撹川閲忥紝閮芥槸鏍囨潌璐ㄩ噺銆俓r\n娴疯灪鐨勫熀纭€闈炲父濂斤紝鐩墠璐ㄩ噺鍧愮ǔ绗竴鏄病鏈変簤璁殑銆?,'2024-12-22 15:15:56',0),(18,16,16,'鏄笉鏄汉澶氱殑浼樺娍鍑告樉鍑烘潵浜?,'2024-12-22 15:17:29',0),(19,16,5,'娴疯灪鎴戣嚜宸辩敤娌￠偅涔堝帀瀹冲晩','2024-12-22 15:17:45',0),(20,16,6,'涓€杞溂娴疯灪瓒呰秺蹇墜浜嗭紵','2024-12-22 15:18:02',0),(21,18,17,'楂樻捣鎷旂┖姘旂█钖勭传澶栫嚎寮哄浜轰笉濂斤紝楂樻俯鍦板尯瀵逛汉涓嶅ソ锛屽瘨鍐峰湴鍖哄浜轰篃涓嶅ソ銆傜湅鏉ユ瘡涓湴鍖哄浜轰綋閮芥湁涓嶅悓鐨勪激瀹冲埡婵€鍟?,'2024-12-22 15:32:09',0),(22,18,10,'鎵€浠ュ崡鍖楁瀬鐨勪汉鏄笉鏄壒鍒暱瀵夸笉灏辫兘鍙嶆楂樻俯瀵瑰鍛界殑褰卞搷锛焄doge]','2024-12-22 15:32:32',0),(23,18,9,'浠栬繖涓槸浣撴劅娓╁害锛屼綘濡傛灉涓€鐩村湪鏆栨皵涓偅涔熸病鏁堟灉鐨勩€備粬杩欓噷涓嶆槸璇翠簡鍚楋紵鐭殏鐨勬毚闇插湪鏋佺楂樻俯鎴栦綆娓╂病鏈夊奖鍝嶃€傚彧鏈夐暱鏈熷湪閭ｆ俯搴︽墠鏈夋晥鏋溿€?,'2024-12-22 15:33:08',0),(24,20,9,'鎰忔€濇槸鍙鑳栬繃浜嗗氨鐩稿綋浜庣暀妗堝簳浜嗭紵','2024-12-22 15:45:57',0),(25,20,7,'闅炬€垜鐦︿笉涓嬫潵','2024-12-22 15:47:02',0);
/*!40000 ALTER TABLE `images_comment_table` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_comment_count` AFTER INSERT ON `images_comment_table` FOR EACH ROW BEGIN
    UPDATE image_table
    SET image_comment = image_comment + 1
    WHERE image_id = NEW.image_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_comment_number_after_insert` AFTER INSERT ON `images_comment_table` FOR EACH ROW BEGIN
    UPDATE webdata_table
    SET comment_number = comment_number + 1
    WHERE webdata_id = 1; 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_comment_count_after_delete` AFTER DELETE ON `images_comment_table` FOR EACH ROW BEGIN
    UPDATE image_table
    SET image_comment = image_comment - 1
    WHERE image_id = OLD.image_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_comment_number_after_delete` AFTER DELETE ON `images_comment_table` FOR EACH ROW BEGIN
    UPDATE webdata_table
    SET comment_number = comment_number - 1
    WHERE webdata_id = 1; 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `likes_table`
--

DROP TABLE IF EXISTS `likes_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes_table` (
  `like_id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `image_id` int DEFAULT NULL,
  `comment_id` int DEFAULT NULL,
  `like_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`like_id`),
  KEY `uid` (`uid`),
  KEY `image_id` (`image_id`),
  KEY `comment_id` (`comment_id`),
  CONSTRAINT `likes_table_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user_table` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `likes_table_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `image_table` (`image_id`) ON DELETE CASCADE,
  CONSTRAINT `likes_table_ibfk_3` FOREIGN KEY (`comment_id`) REFERENCES `images_comment_table` (`comment_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes_table`
--

LOCK TABLES `likes_table` WRITE;
/*!40000 ALTER TABLE `likes_table` DISABLE KEYS */;
INSERT INTO `likes_table` VALUES (2,1,12,NULL,'2024-12-22 14:14:24'),(3,6,NULL,3,'2024-12-22 14:20:36'),(4,6,NULL,4,'2024-12-22 14:20:38'),(5,6,12,NULL,'2024-12-22 14:20:43'),(6,6,13,NULL,'2024-12-22 14:22:30'),(7,4,14,NULL,'2024-12-22 14:26:04'),(8,4,12,NULL,'2024-12-22 14:26:05'),(9,4,13,NULL,'2024-12-22 14:26:06'),(10,9,NULL,10,'2024-12-22 14:26:51'),(11,11,15,NULL,'2024-12-22 14:49:14'),(12,11,13,NULL,'2024-12-22 14:49:15'),(13,11,14,NULL,'2024-12-22 14:49:16'),(14,11,12,NULL,'2024-12-22 14:49:18'),(15,14,NULL,13,'2024-12-22 14:52:47'),(16,15,16,NULL,'2024-12-22 15:15:49'),(17,6,18,NULL,'2024-12-22 15:30:40');
/*!40000 ALTER TABLE `likes_table` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_love_number_after_insert` AFTER INSERT ON `likes_table` FOR EACH ROW BEGIN
    UPDATE webdata_table
    SET love_number = love_number + 1
    WHERE webdata_id = 1; 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_love_number_after_delete` AFTER DELETE ON `likes_table` FOR EACH ROW BEGIN
    UPDATE webdata_table
    SET love_number = love_number - 1
    WHERE webdata_id = 1; 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `user_table`
--

DROP TABLE IF EXISTS `user_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_table` (
  `uid` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_img` varchar(255) DEFAULT 'default.png',
  `user_txt` text,
  `user_zt` text,
  `user_rg_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_table`
--

LOCK TABLES `user_table` WRITE;
/*!40000 ALTER TABLE `user_table` DISABLE KEYS */;
INSERT INTO `user_table` VALUES (1,'lin','$2y$10$YKPQqfvnlv1XFMMa52sN3..o8LzV8XD.kXfbDQ1bicV.NYWpMxbUe','user_img/6766c5acde750.jpg','',NULL,'2024-12-21 21:42:04'),(2,'1','$2y$10$WBtnQPXnbFAHKJ6djr1blOhOBY4hSiO2nGuSHCjDz3fo60OCgS6r6','user_img/6766cc00ae078.jpg','',NULL,'2024-12-21 22:09:04'),(3,'ki鍠?,'$2y$10$bPW3/YtgUaqAnID2MujZ5.ZhtRjeqk68srGiUumnFEQthjQoY8Ywe','user_img/6767ad16f2704.png','',NULL,'2024-12-22 14:09:27'),(4,'閾侀閾摦','$2y$10$sGu1VqjzwkYUAjfDWqy8Qu2aLHTsupWZJcni65XmUkebQ8s6Tj8bm','user_img/6767ad9f3e719.png','',NULL,'2024-12-22 14:11:43'),(5,'娓?鏃哄瓙','$2y$10$b1YtUDjeAIFLY8APlEs2i.3.qiF1GTksGXqc51Amwcz8aGvrI52VS','user_img/6767af1504881.png','',NULL,'2024-12-22 14:17:57'),(6,'鑸炲姩璺宠烦绯?,'$2y$10$mc1r5rW3C5aR7FGxvP0fGeF6ofMEf7Q4QpZfCh8lF81z1hqWFFNrW','user_img/6767afa95d839.png','',NULL,'2024-12-22 14:20:25'),(7,'钖潯鐖卞悆鐚?,'$2y$10$FD0.RJE/6NZzyz.KjonCTujQvL58ePn4nXI/FMeGH8k9cvKarxPPC','user_img/6767b04776771.png','',NULL,'2024-12-22 14:23:03'),(8,'闈㈡潯灞?,'$2y$10$PwMdd5XzODnTM6YQX0ZBsO8glLhNKWwf20M3DONvlUj93H/rqVlt.','user_img/default.png','',NULL,'2024-12-22 14:23:57'),(9,'COAL','$2y$10$.rjPY9whFDDoYJlXglY7YeWi0ZU1QakAFHvS3IS.ZtkUZPLZlQWZ6','user_img/6767b119e2dfd.png','',NULL,'2024-12-22 14:26:33'),(10,'濡傛鐙傚緬鏂笉鍙暀','$2y$10$xi6Ln.tNoVBJwIrqhYqZbuE8CIZlOiX8MfC5EIGvWC7DJUZdPJqMe','user_img/default.png','',NULL,'2024-12-22 14:27:36'),(11,'srzy321','$2y$10$mCz6sS9N5QXHziLcA40z8ePrt9kM44CWv23y5POOENozBQwu.uZZC','user_img/default.png','',NULL,'2024-12-22 14:48:37'),(12,'涓€鑸ぞ鍛?,'$2y$10$Sto0HTupZ6NU5I91GS.6YOwOvlrYZWZweKkheMIP/51yGDaz8kwWK','user_img/6767b68b221dd.png','',NULL,'2024-12-22 14:49:47'),(13,'cnclyrm','$2y$10$LNzTdjxbHeiP9jIsInoI0OAAzw6dPg43Ydqmxnrgy0hb0zJtLj5M2','user_img/6767b6c61ad37.png','',NULL,'2024-12-22 14:50:46'),(14,'鐔靛北浜?,'$2y$10$plvWjPmZkxtoQ60XxhDwzOWnZtS5PhAsQ/z2i3BbkukVlQsqymgV2','user_img/6767b730df7ed.png','',NULL,'2024-12-22 14:52:32'),(15,'P-Aseer','$2y$10$GLzP4QxAZXgvFFYnY3ygZ.yS8Jwr68Y8pGqNGwwxdBSAPcgacq2qC','user_img/6767bc9e84cad.png','',NULL,'2024-12-22 15:15:42'),(16,'鏅鸿兘楗璸ro','$2y$10$VG9rXnJBqd7OVBA3K6kPPuNLvb9xYD0CntOST/KWo/GMkE1O2Hyy6','user_img/default.png','',NULL,'2024-12-22 15:17:11'),(17,'闈掗洦璇濋潤鏇?,'$2y$10$Cm4NxGuRps72FfCbrTd/AO71xgzLLnvFs1Y8JGdYbyCj5KrezIrX2','user_img/6767c06eb1ba6.png','',NULL,'2024-12-22 15:31:58'),(18,'lin1','lin1','lin','lin1 ','0','2024-12-22 16:08:24');
/*!40000 ALTER TABLE `user_table` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_user_number_after_insert` AFTER INSERT ON `user_table` FOR EACH ROW BEGIN
    UPDATE webdata_table
    SET user_number = user_number + 1
    WHERE webdata_id = 1;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = gbk */ ;
/*!50003 SET character_set_results = gbk */ ;
/*!50003 SET collation_connection  = gbk_chinese_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_user_number_after_delete` AFTER DELETE ON `user_table` FOR EACH ROW BEGIN
    UPDATE webdata_table
    SET user_number = user_number - 1
    WHERE webdata_id = 1; 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary view structure for view `user_view`
--

DROP TABLE IF EXISTS `user_view`;
/*!50001 DROP VIEW IF EXISTS `user_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `user_view` AS SELECT 
 1 AS `uid`,
 1 AS `user_name`,
 1 AS `user_img`,
 1 AS `user_txt`,
 1 AS `user_zt`,
 1 AS `user_rg_time`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `webdata_table`
--

DROP TABLE IF EXISTS `webdata_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `webdata_table` (
  `webdata_id` int NOT NULL AUTO_INCREMENT,
  `img_number` int DEFAULT '0',
  `user_number` int DEFAULT '0',
  `love_number` int DEFAULT '0',
  `comment_number` int DEFAULT '0',
  PRIMARY KEY (`webdata_id`),
  CONSTRAINT `webdata_table_chk_1` CHECK ((`img_number` >= 0)),
  CONSTRAINT `webdata_table_chk_2` CHECK ((`user_number` >= 0)),
  CONSTRAINT `webdata_table_chk_3` CHECK ((`love_number` >= 0)),
  CONSTRAINT `webdata_table_chk_4` CHECK ((`comment_number` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webdata_table`
--

LOCK TABLES `webdata_table` WRITE;
/*!40000 ALTER TABLE `webdata_table` DISABLE KEYS */;
INSERT INTO `webdata_table` VALUES (1,11,18,17,24);
/*!40000 ALTER TABLE `webdata_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `user_view`
--

/*!50001 DROP VIEW IF EXISTS `user_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = gbk */;
/*!50001 SET character_set_results     = gbk */;
/*!50001 SET collation_connection      = gbk_chinese_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `user_view` AS select `user_table`.`uid` AS `uid`,`user_table`.`user_name` AS `user_name`,`user_table`.`user_img` AS `user_img`,`user_table`.`user_txt` AS `user_txt`,`user_table`.`user_zt` AS `user_zt`,`user_table`.`user_rg_time` AS `user_rg_time` from `user_table` */;
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

-- Dump completed on 2024-12-22 16:40:54
