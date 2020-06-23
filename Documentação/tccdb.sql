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
  `idAdministrador` int NOT NULL AUTO_INCREMENT,
  `nome_adm` varchar(45) NOT NULL,
  `senha_adm` varchar(45) NOT NULL,
  PRIMARY KEY (`idAdministrador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrador`
--

LOCK TABLES `administrador` WRITE;
/*!40000 ALTER TABLE `administrador` DISABLE KEYS */;
/*!40000 ALTER TABLE `administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `advogado`
--

DROP TABLE IF EXISTS `advogado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `advogado` (
  `idAdvogado` int NOT NULL AUTO_INCREMENT,
  `nome_adv` varchar(45) NOT NULL,
  `sobrenome_adv` varchar(45) NOT NULL,
  `email_adv` varchar(45) NOT NULL,
  `estados_adv` int NOT NULL,
  `cidade_adv` varchar(45) NOT NULL,
  `telefone_Adv` varchar(45) NOT NULL,
  `nome_usuario_adv` varchar(45) NOT NULL,
  `senha_adv` varchar(45) NOT NULL,
  `formacao` text NOT NULL,
  `Cidades_idCidades` int NOT NULL,
  `foto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idAdvogado`),
  KEY `fk_Advogado_estados1_idx` (`estados_adv`),
  KEY `fk_Advogado_Cidades1_idx` (`Cidades_idCidades`),
  CONSTRAINT `fk_Advogado_Cidades1` FOREIGN KEY (`Cidades_idCidades`) REFERENCES `cidades` (`idCidades`),
  CONSTRAINT `fk_Advogado_estados1` FOREIGN KEY (`estados_adv`) REFERENCES `estados` (`idestados`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advogado`
--

LOCK TABLES `advogado` WRITE;
/*!40000 ALTER TABLE `advogado` DISABLE KEYS */;
/*!40000 ALTER TABLE `advogado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas` (
  `idarea` int NOT NULL AUTO_INCREMENT,
  `nome_area` varchar(45) NOT NULL,
  `descricao` varchar(45) NOT NULL,
  PRIMARY KEY (`idarea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `areas_has_advogado`
--

DROP TABLE IF EXISTS `areas_has_advogado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas_has_advogado` (
  `areas_idarea` int NOT NULL,
  `Advogado_idAdvogado` int NOT NULL,
  PRIMARY KEY (`areas_idarea`,`Advogado_idAdvogado`),
  KEY `fk_areas_has_Advogado_Advogado1_idx` (`Advogado_idAdvogado`),
  KEY `fk_areas_has_Advogado_areas1_idx` (`areas_idarea`),
  CONSTRAINT `fk_areas_has_Advogado_Advogado1` FOREIGN KEY (`Advogado_idAdvogado`) REFERENCES `advogado` (`idAdvogado`),
  CONSTRAINT `fk_areas_has_Advogado_areas1` FOREIGN KEY (`areas_idarea`) REFERENCES `areas` (`idarea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas_has_advogado`
--

LOCK TABLES `areas_has_advogado` WRITE;
/*!40000 ALTER TABLE `areas_has_advogado` DISABLE KEYS */;
/*!40000 ALTER TABLE `areas_has_advogado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacao`
--

DROP TABLE IF EXISTS `avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao` (
  `idAvaliacao` int NOT NULL,
  `nota` int NOT NULL,
  `descricao` varchar(45) DEFAULT NULL,
  `data` datetime NOT NULL,
  `Advogado_idAdvogado` int NOT NULL,
  `Cliente_idCliente` int NOT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'Ativo',
  PRIMARY KEY (`idAvaliacao`),
  KEY `fk_Avaliacao_Advogado1_idx` (`Advogado_idAdvogado`),
  KEY `fk_Avaliacao_Cliente1_idx` (`Cliente_idCliente`),
  CONSTRAINT `fk_Avaliacao_Advogado1` FOREIGN KEY (`Advogado_idAdvogado`) REFERENCES `advogado` (`idAdvogado`),
  CONSTRAINT `fk_Avaliacao_Cliente1` FOREIGN KEY (`Cliente_idCliente`) REFERENCES `cliente` (`idCliente`)
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
-- Table structure for table `cidades`
--

DROP TABLE IF EXISTS `cidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cidades` (
  `idCidades` int NOT NULL,
  `Nome` varchar(45) NOT NULL,
  `estados_idestados` int NOT NULL,
  PRIMARY KEY (`idCidades`),
  KEY `fk_Cidades_estados1_idx` (`estados_idestados`),
  CONSTRAINT `fk_Cidades_estados1` FOREIGN KEY (`estados_idestados`) REFERENCES `estados` (`idestados`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidades`
--

LOCK TABLES `cidades` WRITE;
/*!40000 ALTER TABLE `cidades` DISABLE KEYS */;
INSERT INTO `cidades` VALUES (1,'União da Vitoria',1),(2,'Porto Vitória',1),(3,'Paula Freitas',1),(4,'Cruz Machado',1),(5,'Bituruna',1),(6,'General Carneiro',1),(7,'Curitiba',1),(11,'Florianopolis ',2),(12,'São Paulo ',2);
/*!40000 ALTER TABLE `cidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `idCliente` int NOT NULL AUTO_INCREMENT,
  `nome_cli` varchar(45) NOT NULL,
  `sobrenome_cli` varchar(45) NOT NULL,
  `email_cli` varchar(45) NOT NULL,
  `estados_cli` int NOT NULL,
  `Cidades_idCidades` int NOT NULL,
  `telefone_cli` varchar(45) NOT NULL,
  `nome_usuario_cli` varchar(45) NOT NULL,
  `senha_cli` varchar(45) NOT NULL,
  `foto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `fk_Cliente_estados1_idx` (`estados_cli`),
  KEY `fk_Cliente_Cidades1_idx` (`Cidades_idCidades`),
  CONSTRAINT `fk_Cliente_Cidades1` FOREIGN KEY (`Cidades_idCidades`) REFERENCES `cidades` (`idCidades`),
  CONSTRAINT `fk_Cliente_estados1` FOREIGN KEY (`estados_cli`) REFERENCES `estados` (`idestados`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='		';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentos` (
  `iddocumentos` int NOT NULL AUTO_INCREMENT,
  `nome_doc` varchar(45) NOT NULL,
  `Solicitacoes_idSolicitacoes` int NOT NULL,
  PRIMARY KEY (`iddocumentos`),
  KEY `fk_documentos_Solicitacoes1_idx` (`Solicitacoes_idSolicitacoes`),
  CONSTRAINT `fk_documentos_Solicitacoes1` FOREIGN KEY (`Solicitacoes_idSolicitacoes`) REFERENCES `solicitacoes` (`idSolicitacoes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos`
--

LOCK TABLES `documentos` WRITE;
/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados` (
  `idestados` int NOT NULL AUTO_INCREMENT,
  `nome_estado` varchar(45) NOT NULL,
  `sigla_estado` varchar(2) NOT NULL,
  PRIMARY KEY (`idestados`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados`
--

LOCK TABLES `estados` WRITE;
/*!40000 ALTER TABLE `estados` DISABLE KEYS */;
INSERT INTO `estados` VALUES (1,'Paraná','PR'),(2,'Santa Catarina','SC'),(3,'São Paulo','SP');
/*!40000 ALTER TABLE `estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oab`
--

DROP TABLE IF EXISTS `oab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oab` (
  `idoab` int NOT NULL AUTO_INCREMENT,
  `numero_oab` varchar(45) NOT NULL,
  `estados_oab` int NOT NULL,
  `validacao` datetime DEFAULT NULL,
  `Advogado_idAdvogado` int NOT NULL,
  PRIMARY KEY (`idoab`),
  KEY `fk_oab_estados1_idx` (`estados_oab`),
  KEY `fk_oab_Advogado1_idx` (`Advogado_idAdvogado`),
  CONSTRAINT `fk_oab_Advogado1` FOREIGN KEY (`Advogado_idAdvogado`) REFERENCES `advogado` (`idAdvogado`),
  CONSTRAINT `fk_oab_estados1` FOREIGN KEY (`estados_oab`) REFERENCES `estados` (`idestados`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oab`
--

LOCK TABLES `oab` WRITE;
/*!40000 ALTER TABLE `oab` DISABLE KEYS */;
/*!40000 ALTER TABLE `oab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitacoes`
--

DROP TABLE IF EXISTS `solicitacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitacoes` (
  `idSolicitacoes` int NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  `data_hora` datetime NOT NULL,
  `Advogado_idAdvogado` int NOT NULL,
  `Cliente_idCliente` int NOT NULL,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`idSolicitacoes`),
  KEY `fk_Solicitacoes_Advogado1_idx` (`Advogado_idAdvogado`),
  KEY `fk_Solicitacoes_Cliente1_idx` (`Cliente_idCliente`),
  CONSTRAINT `fk_Solicitacoes_Advogado1` FOREIGN KEY (`Advogado_idAdvogado`) REFERENCES `advogado` (`idAdvogado`),
  CONSTRAINT `fk_Solicitacoes_Cliente1` FOREIGN KEY (`Cliente_idCliente`) REFERENCES `cliente` (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitacoes`
--

LOCK TABLES `solicitacoes` WRITE;
/*!40000 ALTER TABLE `solicitacoes` DISABLE KEYS */;
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

-- Dump completed on 2020-06-23  8:18:44
