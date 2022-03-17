-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.73-community


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema face
--

CREATE DATABASE IF NOT EXISTS face;
USE face;

--
-- Definition of table `t001_servico`
--

DROP TABLE IF EXISTS `t001_servico`;
CREATE TABLE `t001_servico` (
  `A001_codigo_servico` int(11) NOT NULL,
  `A001_descricao_servico` varchar(50) NOT NULL,
  `A001_valor_hora_servico` decimal(15,2) NOT NULL,
  PRIMARY KEY (`A001_codigo_servico`),
  UNIQUE KEY `T001` (`A001_descricao_servico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t001_servico`
--

/*!40000 ALTER TABLE `t001_servico` DISABLE KEYS */;
INSERT INTO `t001_servico` (`A001_codigo_servico`,`A001_descricao_servico`,`A001_valor_hora_servico`) VALUES 
 (1,'ABC','150.00'),
 (2,'XYZ','120.00'),
 (3,'KTM','170.00');
/*!40000 ALTER TABLE `t001_servico` ENABLE KEYS */;


--
-- Definition of table `t002_cliente`
--

DROP TABLE IF EXISTS `t002_cliente`;
CREATE TABLE `t002_cliente` (
  `A002_codigo_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `A002_cnpj_cliente` varchar(15) NOT NULL,
  `A002_razao_social_cliente` varchar(100) DEFAULT NULL,
  `A002_endereco_cliente` varchar(100) DEFAULT NULL,
  `A002_cidade_cliente` varchar(100) DEFAULT NULL,
  `A002_uf_cliente` char(2) DEFAULT NULL,
  `A002_cep_cliente` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`A002_codigo_cliente`),
  UNIQUE KEY `T002` (`A002_cnpj_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t002_cliente`
--

/*!40000 ALTER TABLE `t002_cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `t002_cliente` ENABLE KEYS */;


--
-- Definition of table `t003_venda`
--

DROP TABLE IF EXISTS `t003_venda`;
CREATE TABLE `t003_venda` (
  `A003_codigo_venda` int(11) NOT NULL AUTO_INCREMENT,
  `A001_codigo_servico` int(11) NOT NULL,
  `A002_codigo_cliente` int(11) NOT NULL,
  `A003_data_venda` date NOT NULL,
  `A003_custo_venda` decimal(15,2) NOT NULL,
  `A003_faturado_venda` decimal(15,2) NOT NULL,
  PRIMARY KEY (`A003_codigo_venda`),
  UNIQUE KEY `T003` (`A001_codigo_servico`,`A002_codigo_cliente`,`A003_data_venda`),
  KEY `T002_T003` (`A002_codigo_cliente`),
  CONSTRAINT `T001_T003` FOREIGN KEY (`A001_codigo_servico`) REFERENCES `t001_servico` (`A001_codigo_servico`),
  CONSTRAINT `T002_T003` FOREIGN KEY (`A002_codigo_cliente`) REFERENCES `t002_cliente` (`A002_codigo_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t003_venda`
--

/*!40000 ALTER TABLE `t003_venda` DISABLE KEYS */;
/*!40000 ALTER TABLE `t003_venda` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
