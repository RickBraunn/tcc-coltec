-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: tcc
-- ------------------------------------------------------
-- Server version	8.0.19

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
-- Table structure for table `administrador`
--

DROP TABLE IF EXISTS `administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administrador` (
  `id_adm` int NOT NULL AUTO_INCREMENT,
  `nome_adm` varchar(45) NOT NULL,
  `senha_adm` varchar(45) NOT NULL,
  PRIMARY KEY (`id_adm`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrador`
--

LOCK TABLES `administrador` WRITE;
/*!40000 ALTER TABLE `administrador` DISABLE KEYS */;
INSERT INTO `administrador` VALUES (3,'teste','teste');
/*!40000 ALTER TABLE `administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `advogado`
--

DROP TABLE IF EXISTS `advogado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `advogado` (
  `id_adv` int NOT NULL AUTO_INCREMENT,
  `nome_adv` varchar(45) NOT NULL,
  `sobrenome_adv` varchar(45) NOT NULL,
  `email_adv` varchar(45) NOT NULL,
  `cidade_adv` int NOT NULL,
  `telefone_adv` varchar(45) NOT NULL,
  `nome_usuario_adv` varchar(45) NOT NULL,
  `senha_adv` varchar(45) NOT NULL,
  `formacao` text NOT NULL,
  `foto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_adv`),
  KEY `fk_Advogado_Cidades1_idx` (`cidade_adv`),
  CONSTRAINT `fk_Advogado_Cidades1` FOREIGN KEY (`cidade_adv`) REFERENCES `cidade` (`id_cidade`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advogado`
--

LOCK TABLES `advogado` WRITE;
/*!40000 ALTER TABLE `advogado` DISABLE KEYS */;
INSERT INTO `advogado` VALUES (3,'qaffg','wedfweae','saf@gmail.com',1,'123454','teste','2e6f9b0d5885b6010f9167787445617f553a735f','d',NULL),(4,'ggggg','ggggggg','saf@gmail.com',1,'11111111','asd','gggggg','d',NULL),(5,'Teste login','as','a@g.com',11,'1234','fg','123','a',NULL),(6,'Ricardo','Braunn','rick@u.com',1,'2134123513','teste1','2e6f9b0d5885b6010f9167787445617f553a735f','s',NULL);
/*!40000 ALTER TABLE `advogado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `area` (
  `id_area` int NOT NULL AUTO_INCREMENT,
  `nome_area` varchar(45) NOT NULL,
  `descricao` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_area`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area`
--

LOCK TABLES `area` WRITE;
/*!40000 ALTER TABLE `area` DISABLE KEYS */;
INSERT INTO `area` VALUES (1,'Criminal','Area Criminal do Direito');
/*!40000 ALTER TABLE `area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `area_adv`
--

DROP TABLE IF EXISTS `area_adv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `area_adv` (
  `id_adv` int NOT NULL,
  `id_area` int NOT NULL,
  PRIMARY KEY (`id_adv`,`id_area`),
  KEY `fk_advogado_has_area_area1_idx` (`id_area`),
  KEY `fk_advogado_has_area_advogado1_idx` (`id_adv`),
  CONSTRAINT `fk_advogado_has_area_advogado1` FOREIGN KEY (`id_adv`) REFERENCES `advogado` (`id_adv`),
  CONSTRAINT `fk_advogado_has_area_area1` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area_adv`
--

LOCK TABLES `area_adv` WRITE;
/*!40000 ALTER TABLE `area_adv` DISABLE KEYS */;
INSERT INTO `area_adv` VALUES (3,1);
/*!40000 ALTER TABLE `area_adv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacao`
--

DROP TABLE IF EXISTS `avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao` (
  `id_avaliacao` int NOT NULL,
  `nota` int NOT NULL,
  `descricao` varchar(45) DEFAULT NULL,
  `data` datetime NOT NULL,
  `Advogado_idAdvogado` int NOT NULL,
  `Cliente_idCliente` int NOT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'Ativo',
  PRIMARY KEY (`id_avaliacao`),
  KEY `fk_Avaliacao_Advogado1_idx` (`Advogado_idAdvogado`),
  KEY `fk_Avaliacao_Cliente1_idx` (`Cliente_idCliente`),
  CONSTRAINT `fk_Avaliacao_Advogado1` FOREIGN KEY (`Advogado_idAdvogado`) REFERENCES `advogado` (`id_adv`),
  CONSTRAINT `fk_Avaliacao_Cliente1` FOREIGN KEY (`Cliente_idCliente`) REFERENCES `cliente` (`id_cli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao`
--

LOCK TABLES `avaliacao` WRITE;
/*!40000 ALTER TABLE `avaliacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `avaliacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cidade`
--

DROP TABLE IF EXISTS `cidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cidade` (
  `id_cidade` int NOT NULL,
  `Nome` varchar(45) NOT NULL,
  `estado_idestados` int NOT NULL,
  PRIMARY KEY (`id_cidade`),
  KEY `fk_Cidades_estados1_idx` (`estado_idestados`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidade`
--

LOCK TABLES `cidade` WRITE;
/*!40000 ALTER TABLE `cidade` DISABLE KEYS */;
INSERT INTO `cidade` VALUES (1,'União da Vitoria',1),(2,'Porto Vitória',1),(3,'Paula Freitas',1),(4,'Cruz Machado',1),(5,'Bituruna',1),(6,'General Carneiro',1),(7,'Curitiba',1),(11,'Florianopolis ',2),(12,'São Paulo ',2);
/*!40000 ALTER TABLE `cidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `id_cli` int NOT NULL AUTO_INCREMENT,
  `nome_cli` varchar(45) NOT NULL,
  `sobrenome_cli` varchar(45) NOT NULL,
  `email_cli` varchar(45) NOT NULL,
  `cidade_cli` int NOT NULL,
  `telefone_cli` varchar(45) NOT NULL,
  `nome_usuario_cli` varchar(45) NOT NULL,
  `senha_cli` varchar(45) NOT NULL,
  `foto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cli`),
  KEY `fk_Cliente_Cidades1_idx` (`cidade_cli`),
  CONSTRAINT `fk_Cliente_Cidades1` FOREIGN KEY (`cidade_cli`) REFERENCES `cidade` (`id_cidade`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='		';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (6,'fggg','afdfsa','rick@hg.com',1,'44443','teste','teste',NULL);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento` (
  `id_documento` int NOT NULL AUTO_INCREMENT,
  `nome_doc` varchar(45) NOT NULL,
  `Solicitacoes_idSolicitacoes` int NOT NULL,
  PRIMARY KEY (`id_documento`),
  KEY `fk_documentos_Solicitacoes1_idx` (`Solicitacoes_idSolicitacoes`),
  CONSTRAINT `fk_documentos_Solicitacoes1` FOREIGN KEY (`Solicitacoes_idSolicitacoes`) REFERENCES `solicitacoes` (`id_solicitacoes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado`
--

DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `nome_estado` varchar(45) NOT NULL,
  `sigla_estado` varchar(2) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado`
--

LOCK TABLES `estado` WRITE;
/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
INSERT INTO `estado` VALUES (1,'Paraná','PR'),(2,'Santa Catarina','SC'),(3,'São Paulo','SP');
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oab`
--

DROP TABLE IF EXISTS `oab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oab` (
  `id_oab` int NOT NULL AUTO_INCREMENT,
  `numero_oab` varchar(45) NOT NULL,
  `estados_oab` int NOT NULL,
  `data` datetime DEFAULT NULL,
  `id_adv` int NOT NULL,
  `status_oab` varchar(45) NOT NULL DEFAULT 'Aguardando Avaliacao',
  PRIMARY KEY (`id_oab`),
  KEY `fk_oab_estados1_idx` (`estados_oab`),
  KEY `fk_oab_Advogado1_idx` (`id_adv`),
  CONSTRAINT `fk_oab_Advogado1` FOREIGN KEY (`id_adv`) REFERENCES `advogado` (`id_adv`),
  CONSTRAINT `fk_oab_estados1` FOREIGN KEY (`estados_oab`) REFERENCES `estado` (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oab`
--

LOCK TABLES `oab` WRITE;
/*!40000 ALTER TABLE `oab` DISABLE KEYS */;
INSERT INTO `oab` VALUES (4,'66661',1,NULL,4,'Aprovado'),(5,'12345',1,NULL,4,'Aguardando Avaliacao'),(6,'12346',1,NULL,4,'Aguardando Avaliacao'),(7,'66666',1,NULL,4,'Reprovado'),(8,'12340',1,NULL,3,'Aprovado');
/*!40000 ALTER TABLE `oab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitacoes`
--

DROP TABLE IF EXISTS `solicitacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitacoes` (
  `id_solicitacoes` int NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  `data_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_adv` int NOT NULL,
  `id_cli` int NOT NULL,
  `status_solicitacoes` varchar(45) NOT NULL DEFAULT 'Pendente',
  PRIMARY KEY (`id_solicitacoes`),
  KEY `fk_Solicitacoes_Advogado1_idx` (`id_adv`),
  KEY `fk_Solicitacoes_Cliente1_idx` (`id_cli`),
  CONSTRAINT `fk_Solicitacoes_Advogado1` FOREIGN KEY (`id_adv`) REFERENCES `advogado` (`id_adv`),
  CONSTRAINT `fk_Solicitacoes_Cliente1` FOREIGN KEY (`id_cli`) REFERENCES `cliente` (`id_cli`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitacoes`
--

LOCK TABLES `solicitacoes` WRITE;
/*!40000 ALTER TABLE `solicitacoes` DISABLE KEYS */;
INSERT INTO `solicitacoes` VALUES (1,'teste','2020-08-12 19:35:05',3,6,'Aceito');
/*!40000 ALTER TABLE `solicitacoes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:58:03
