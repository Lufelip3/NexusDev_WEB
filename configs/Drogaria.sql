CREATE DATABASE IF NOT EXISTS drogariaWEB;
USE drogariaWEB;

-- ===============================
-- Tabela: drogaria
-- ===============================
CREATE TABLE drogaria (
  CNPJ_Drog VARCHAR(18) PRIMARY KEY,
  Nome_Drog VARCHAR(50) NOT NULL,
  Telefone_Drog VARCHAR(15) NOT NULL,
  Cep_Drog VARCHAR(10) NOT NULL,
  Num_Drog INT NOT NULL,
  Email_Drog VARCHAR(50) UNIQUE NOT NULL,
  Ativo_Drog TINYINT(1) NOT NULL DEFAULT 1,
  Foto_Drog VARCHAR(255) NULL
);

-- ===============================
-- Tabela: laboratorio
-- ===============================
CREATE TABLE laboratorio (
  CNPJ_Lab VARCHAR(18) PRIMARY KEY,
  Nome_Lab VARCHAR(50) NOT NULL,
  Telefone_Lab VARCHAR(15) NOT NULL,
  Cep_Lab VARCHAR(10) NOT NULL,
  Num_Lab INT UNIQUE NOT NULL,
  Email_Lab VARCHAR(50) UNIQUE NOT NULL,
  Ativo_Lab TINYINT(1) NOT NULL DEFAULT 1,
  Foto_Lab VARCHAR(255) NULL
);

-- ===============================
-- Tabela Funcionario
-- ===============================
CREATE TABLE funcionario (
  CPF VARCHAR(14) UNIQUE PRIMARY KEY,
  Nome_Fun VARCHAR(50) NOT NULL,
  Telefone_Fun VARCHAR(15) NOT NULL,
  Cep_Fun VARCHAR(10) NOT NULL,
  Num_Fun INT NOT NULL,
  Email_Fun VARCHAR(50) UNIQUE NOT NULL,
  Senha_Fun VARCHAR(255) NOT NULL,
  Funcao VARCHAR(50),
  Ativo_Fun TINYINT(1) NOT NULL DEFAULT 1,
  imagem VARCHAR(255) NULL
);

-- ===============================
-- Tabela: catalogo_medicamento
-- ===============================
CREATE TABLE catalogo_medicamento (
  Cod_CatMed INT AUTO_INCREMENT PRIMARY KEY,
  EAN_Med VARCHAR(13) UNIQUE,
  Nome_CatMed VARCHAR(50) NOT NULL,
  Desc_CatMed VARCHAR(100) NOT NULL,
  Valor_CatMed DECIMAL(10,2) NOT NULL,
  datacompraItemCat DATE,
  dataValItemCat DATE,
  quantidade INT NOT NULL,
  CNPJ_Lab VARCHAR(18),
  FOREIGN KEY (CNPJ_Lab) REFERENCES laboratorio(CNPJ_Lab)
);

-- ===============================
-- Tabela: medicamento
-- ===============================
CREATE TABLE medicamento (
  Cod_Med INT AUTO_INCREMENT PRIMARY KEY,
  EAN_Med VARCHAR(13) UNIQUE,
  Nome_Med VARCHAR(50) NOT NULL,
  Desc_Med VARCHAR(100) NOT NULL,
  DataVal_Med DATE NOT NULL,
  Qtd_Med INT NOT NULL,
  Valor_Med DECIMAL(10,2) NOT NULL,
  Cod_CatMed INT,
  Foto_Med VARCHAR(255) NULL,
  FOREIGN KEY (Cod_CatMed) REFERENCES catalogo_medicamento(Cod_CatMed)
);

-- ===============================
-- Tabela: compra
-- ===============================
CREATE TABLE compra (
  NotaFiscal_Entrada INT AUTO_INCREMENT PRIMARY KEY,
  Valor_Total DECIMAL(10,2),
  Data_Compra DATE DEFAULT (CURRENT_DATE),
  CPF VARCHAR(14),
  CNPJ_Lab VARCHAR(18),
  Finalizada TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (CPF) REFERENCES funcionario(CPF),
  FOREIGN KEY (CNPJ_Lab) REFERENCES laboratorio(CNPJ_Lab)
);

-- ===============================
-- Tabela: venda
-- ===============================
CREATE TABLE venda (
  NotaFiscal_Saida INT AUTO_INCREMENT PRIMARY KEY,
  Data_Venda DATE DEFAULT (CURRENT_DATE),
  Valor_Venda DECIMAL(10,2),
  CNPJ_Drog VARCHAR(18),
  CPF VARCHAR(14),
  Finalizada TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (CNPJ_Drog) REFERENCES drogaria(CNPJ_Drog),
  FOREIGN KEY (CPF) REFERENCES funcionario(CPF)
);

-- ===============================
-- Tabela: item
-- ===============================
CREATE TABLE item (
  Cod_Item INT AUTO_INCREMENT PRIMARY KEY,
  DataVal_Item DATE NOT NULL,
  Qtd_Item INT NOT NULL,
  Valor_Item DECIMAL(10,2) NOT NULL,
  Data_Venda DATE NOT NULL,
  NotaFiscal_Entrada INT,
  Cod_CatMed INT,
  Cod_Med INT,
  FOREIGN KEY (NotaFiscal_Entrada) REFERENCES compra(NotaFiscal_Entrada),
  FOREIGN KEY (Cod_CatMed) REFERENCES catalogo_medicamento(Cod_CatMed),
  FOREIGN KEY (Cod_Med) REFERENCES medicamento(Cod_Med)
);

-- ===============================
-- Tabela: item_venda
-- ===============================
CREATE TABLE item_venda (
  Cod_ItemVenda INT AUTO_INCREMENT PRIMARY KEY,
  DataVal_ItemVenda DATE NOT NULL,
  Qtd_ItemVenda INT NOT NULL,
  Valor_ItemVenda DECIMAL(10,2) NOT NULL,
  Cod_Med INT,
  NotaFiscal_Saida INT,
  FOREIGN KEY (Cod_Med) REFERENCES medicamento(Cod_Med),
  FOREIGN KEY (NotaFiscal_Saida) REFERENCES venda(NotaFiscal_Saida)
);

-- ===============================
-- INSERTS: Drogaria
-- ===============================
INSERT INTO drogaria (CNPJ_Drog, Nome_Drog, Telefone_Drog, Cep_Drog, Num_Drog, Email_Drog) VALUES
('12.345.678/0001-11', 'Drogaria Central', '(11)99888-1111', '01001-000', 101, 'contato@drogariacentral.com'),
('23.456.789/0001-22', 'Drogaria Vida', '(11)97777-2222', '01002-000', 102, 'contato@drogariavida.com');

-- ===============================
-- INSERTS: Laboratório
-- ===============================
INSERT INTO laboratorio (CNPJ_Lab, Nome_Lab, Telefone_Lab, Cep_Lab, Num_Lab, Email_Lab) VALUES
('01.111.111/0001-01', 'Pfizer', '(11)90000-0001', '01010-000', 1, 'contatopfizer@lab.com'),
('02.222.222/0001-02', 'EMS', '(11)90000-0002', '02010-000', 2, 'contatoems@lab.com'),
('03.333.333/0001-03', 'Eurofarma', '(11)90000-0003', '03010-000', 3, 'contatoeurofarma@lab.com'),
('04.444.444/0001-04', 'Aché', '(11)90000-0004', '04010-000', 4, 'contatoaché@lab.com'),
('05.555.555/0001-05', 'Biolab', '(11)90000-0005', '05010-000', 5, 'contatobiolab@lab.com'),
('06.666.666/0001-06', 'Neo Química', '(11)90000-0006', '06010-000', 6, 'contatoneoquímica@lab.com'),
('07.777.777/0001-07', 'Sanofi', '(11)90000-0007', '07010-000', 7, 'contatosanofi@lab.com'),
('08.888.888/0001-08', 'Roche', '(11)90000-0008', '08010-000', 8, 'contatoroche@lab.com'),
('09.999.999/0001-09', 'Bayer', '(11)90000-0009', '09010-000', 9, 'contatobayer@lab.com'),
('10.000.000/0001-10', 'Medley', '(11)90000-00010', '010010-000', 10, 'contatomedley@lab.com');

-- ===============================
-- INSERTS: Catálogo Medicamento e Medicamento (Estoque Local) 100 iterações
-- ===============================
INSERT INTO catalogo_medicamento (EAN_Med, Nome_CatMed, Desc_CatMed, Valor_CatMed, CNPJ_Lab, datacompraItemCat, dataValItemCat, quantidade) VALUES
('7891000000001', 'Pfizer Spray Forte', 'Antiviral Farmacológico', 59.57, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 120),
('7891000000002', 'Pfizer Xarope Infantil', 'Antibiótico Farmacológico', 63.9, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 839),
('7891000000003', 'Pfizer Cápsula Infantil', 'Antiviral Farmacológico', 39.97, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 758),
('7891000000004', 'Pfizer Loção Plus', 'Anti-inflamatório Farmacológico', 54.5, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 704),
('7891000000005', 'Pfizer Pomada Forte', 'Anti-inflamatório Farmacológico', 27.55, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 261),
('7891000000006', 'Pfizer Loção Plus', 'Antibiótico Farmacológico', 25.18, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 745),
('7891000000007', 'Pfizer Cápsula Max', 'Relaxante Muscular Farmacológico', 25.38, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 725),
('7891000000008', 'Pfizer Comprimido Forte', 'Colírio Farmacológico', 85.47, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 113),
('7891000000009', 'Pfizer Cápsula 500mg', 'Relaxante Muscular Farmacológico', 93.24, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 643),
('7891000000010', 'Pfizer Comprimido 500mg', 'Antibiótico Farmacológico', 45.83, '01.111.111/0001-01', '2024-01-01', '2026-12-31', 316),
('7891000000011', 'EMS Gotas Retard', 'Antitérmico Farmacológico', 52.47, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 785),
('7891000000012', 'EMS Xarope Infantil', 'Antialérgico Farmacológico', 54.73, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 107),
('7891000000013', 'EMS Pomada 200mg', 'Antibiótico Farmacológico', 56.25, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 907),
('7891000000014', 'EMS Gel Retard', 'Anti-inflamatório Farmacológico', 51.5, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 973),
('7891000000015', 'EMS Spray XR', 'Antialérgico Farmacológico', 87.51, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 584),
('7891000000016', 'EMS Gel Infantil', 'Antibiótico Farmacológico', 87.66, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 263),
('7891000000017', 'EMS Gotas Plus', 'Colírio Farmacológico', 88.89, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 617),
('7891000000018', 'EMS Loção Forte', 'Antibiótico Farmacológico', 78.52, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 832),
('7891000000019', 'EMS Xarope XR', 'Antitérmico Farmacológico', 17.21, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 129),
('7891000000020', 'EMS Pomada Max', 'Vitamina Farmacológico', 49.84, '02.222.222/0001-02', '2024-01-01', '2026-12-31', 705),
('7891000000021', 'Eurofarma Injecao 1g', 'Anti-inflamatório Farmacológico', 92.05, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 766),
('7891000000022', 'Eurofarma Xarope 500mg', 'Analgésico Farmacológico', 32.51, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 333),
('7891000000023', 'Eurofarma Gel Infantil', 'Colírio Farmacológico', 56.45, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 597),
('7891000000024', 'Eurofarma Comprimido Retard', 'Anti-inflamatório Farmacológico', 60.96, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 297),
('7891000000025', 'Eurofarma Loção XR', 'Protetor Gástrico Farmacológico', 24.21, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 820),
('7891000000026', 'Eurofarma Pomada 200mg', 'Relaxante Muscular Farmacológico', 30.35, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 515),
('7891000000027', 'Eurofarma Injecao Infantil', 'Protetor Gástrico Farmacológico', 80.69, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 881),
('7891000000028', 'Eurofarma Xarope Forte', 'Antitérmico Farmacológico', 56.79, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 759),
('7891000000029', 'Eurofarma Spray XR', 'Vitamina Farmacológico', 56.18, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 566),
('7891000000030', 'Eurofarma Pomada Retard', 'Antiviral Farmacológico', 33.72, '03.333.333/0001-03', '2024-01-01', '2026-12-31', 425),
('7891000000031', 'Aché Loção Plus', 'Protetor Gástrico Farmacológico', 40.98, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 259),
('7891000000032', 'Aché Spray Retard', 'Analgésico Farmacológico', 84.14, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 197),
('7891000000033', 'Aché Comprimido Forte', 'Antialérgico Farmacológico', 26, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 696),
('7891000000034', 'Aché Gotas Plus', 'Anti-inflamatório Farmacológico', 16.84, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 231),
('7891000000035', 'Aché Loção Max', 'Antiviral Farmacológico', 71.21, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 204),
('7891000000036', 'Aché Loção 1g', 'Analgésico Farmacológico', 95.7, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 539),
('7891000000037', 'Aché Spray Max', 'Colírio Farmacológico', 59.61, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 349),
('7891000000038', 'Aché Gotas 1g', 'Antialérgico Farmacológico', 91.15, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 334),
('7891000000039', 'Aché Gel 200mg', 'Colírio Farmacológico', 52.27, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 876),
('7891000000040', 'Aché Injecao Retard', 'Antitérmico Farmacológico', 58.72, '04.444.444/0001-04', '2024-01-01', '2026-12-31', 172),
('7891000000041', 'Biolab Cápsula 200mg', 'Analgésico Farmacológico', 100.12, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 544),
('7891000000042', 'Biolab Gotas Retard', 'Antiviral Farmacológico', 31.32, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 495),
('7891000000043', 'Biolab Gel Retard', 'Antiviral Farmacológico', 29.21, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 288),
('7891000000044', 'Biolab Comprimido Max', 'Protetor Gástrico Farmacológico', 84.57, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 312),
('7891000000045', 'Biolab Loção XR', 'Anti-inflamatório Farmacológico', 64.32, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 812),
('7891000000046', 'Biolab Gotas Max', 'Relaxante Muscular Farmacológico', 37.36, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 630),
('7891000000047', 'Biolab Xarope Retard', 'Protetor Gástrico Farmacológico', 50.39, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 843),
('7891000000048', 'Biolab Pomada XR', 'Protetor Gástrico Farmacológico', 63.62, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 202),
('7891000000049', 'Biolab Injecao Infantil', 'Antitérmico Farmacológico', 78, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 971),
('7891000000050', 'Biolab Cápsula Infantil', 'Antialérgico Farmacológico', 30.84, '05.555.555/0001-05', '2024-01-01', '2026-12-31', 360),
('7891000000051', 'Neo Química Cápsula Plus', 'Anti-inflamatório Farmacológico', 83.35, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 414),
('7891000000052', 'Neo Química Gel Infantil', 'Protetor Gástrico Farmacológico', 45.9, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 216),
('7891000000053', 'Neo Química Loção 200mg', 'Relaxante Muscular Farmacológico', 75.97, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 166),
('7891000000054', 'Neo Química Injecao 1g', 'Antibiótico Farmacológico', 50.91, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 683),
('7891000000055', 'Neo Química Gotas Max', 'Anti-inflamatório Farmacológico', 69.07, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 596),
('7891000000056', 'Neo Química Gotas Max', 'Antiviral Farmacológico', 21.63, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 796),
('7891000000057', 'Neo Química Xarope Forte', 'Relaxante Muscular Farmacológico', 35.78, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 568),
('7891000000058', 'Neo Química Gotas 500mg', 'Antibiótico Farmacológico', 43, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 120),
('7891000000059', 'Neo Química Cápsula 1g', 'Anti-inflamatório Farmacológico', 14.05, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 815),
('7891000000060', 'Neo Química Cápsula Plus', 'Antibiótico Farmacológico', 13.61, '06.666.666/0001-06', '2024-01-01', '2026-12-31', 197),
('7891000000061', 'Sanofi Pomada Max', 'Antiviral Farmacológico', 22.04, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 522),
('7891000000062', 'Sanofi Injecao XR', 'Analgésico Farmacológico', 86.38, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 760),
('7891000000063', 'Sanofi Cápsula Infantil', 'Analgésico Farmacológico', 85.57, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 406),
('7891000000064', 'Sanofi Pomada Plus', 'Antibiótico Farmacológico', 66.18, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 839),
('7891000000065', 'Sanofi Gotas 500mg', 'Protetor Gástrico Farmacológico', 16.18, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 186),
('7891000000066', 'Sanofi Comprimido Forte', 'Antialérgico Farmacológico', 62.81, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 804),
('7891000000067', 'Sanofi Gotas 1g', 'Antibiótico Farmacológico', 23.88, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 220),
('7891000000068', 'Sanofi Injecao 500mg', 'Colírio Farmacológico', 94.62, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 700),
('7891000000069', 'Sanofi Injecao Infantil', 'Analgésico Farmacológico', 52.51, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 397),
('7891000000070', 'Sanofi Loção Plus', 'Colírio Farmacológico', 36.78, '07.777.777/0001-07', '2024-01-01', '2026-12-31', 648),
('7891000000071', 'Roche Injecao 500mg', 'Protetor Gástrico Farmacológico', 72.9, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 978),
('7891000000072', 'Roche Xarope Max', 'Anti-inflamatório Farmacológico', 95.57, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 450),
('7891000000073', 'Roche Gel Infantil', 'Antitérmico Farmacológico', 75.74, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 955),
('7891000000074', 'Roche Xarope 200mg', 'Anti-inflamatório Farmacológico', 91.58, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 840),
('7891000000075', 'Roche Spray Infantil', 'Anti-inflamatório Farmacológico', 71.24, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 914),
('7891000000076', 'Roche Gel Plus', 'Antialérgico Farmacológico', 63.45, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 845),
('7891000000077', 'Roche Cápsula Retard', 'Analgésico Farmacológico', 42.84, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 881),
('7891000000078', 'Roche Xarope Max', 'Colírio Farmacológico', 65.43, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 108),
('7891000000079', 'Roche Xarope Forte', 'Antiviral Farmacológico', 81.32, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 569),
('7891000000080', 'Roche Loção XR', 'Analgésico Farmacológico', 63.29, '08.888.888/0001-08', '2024-01-01', '2026-12-31', 329),
('7891000000081', 'Bayer Cápsula 500mg', 'Antibiótico Farmacológico', 20.31, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 988),
('7891000000082', 'Bayer Injecao XR', 'Vitamina Farmacológico', 96.64, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 151),
('7891000000083', 'Bayer Cápsula Retard', 'Vitamina Farmacológico', 79.77, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 493),
('7891000000084', 'Bayer Spray 200mg', 'Vitamina Farmacológico', 26.65, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 740),
('7891000000085', 'Bayer Xarope 500mg', 'Vitamina Farmacológico', 21.48, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 180),
('7891000000086', 'Bayer Gotas Retard', 'Colírio Farmacológico', 19.63, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 435),
('7891000000087', 'Bayer Gel Retard', 'Antitérmico Farmacológico', 58.94, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 872),
('7891000000088', 'Bayer Pomada Max', 'Antibiótico Farmacológico', 95.7, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 163),
('7891000000089', 'Bayer Pomada Plus', 'Protetor Gástrico Farmacológico', 94.44, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 190),
('7891000000090', 'Bayer Loção Infantil', 'Anti-inflamatório Farmacológico', 26.29, '09.999.999/0001-09', '2024-01-01', '2026-12-31', 541),
('7891000000091', 'Medley Pomada Retard', 'Relaxante Muscular Farmacológico', 68.55, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 764),
('7891000000092', 'Medley Gel XR', 'Antiviral Farmacológico', 15.03, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 833),
('7891000000093', 'Medley Gotas Plus', 'Protetor Gástrico Farmacológico', 39.91, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 912),
('7891000000094', 'Medley Spray 200mg', 'Antiviral Farmacológico', 57.2, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 902),
('7891000000095', 'Medley Xarope XR', 'Colírio Farmacológico', 53.07, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 536),
('7891000000096', 'Medley Cápsula 200mg', 'Relaxante Muscular Farmacológico', 77.22, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 853),
('7891000000097', 'Medley Gotas Max', 'Antiviral Farmacológico', 43.74, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 911),
('7891000000098', 'Medley Comprimido Retard', 'Antialérgico Farmacológico', 34.68, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 375),
('7891000000099', 'Medley Comprimido Max', 'Antialérgico Farmacológico', 16.3, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 1000),
('7891000000100', 'Medley Comprimido XR', 'Antialérgico Farmacológico', 32.36, '10.000.000/0001-10', '2024-01-01', '2026-12-31', 111);

INSERT INTO medicamento (EAN_Med, Nome_Med, Desc_Med, DataVal_Med, Qtd_Med, Valor_Med, Cod_CatMed, Foto_Med) VALUES
('7891000000001', 'Pfizer Spray Forte', 'Antiviral Farmacológico', '2026-12-31', 25, 59.57, 1, NULL),
('7891000000002', 'Pfizer Xarope Infantil', 'Antibiótico Farmacológico', '2026-12-31', 22, 63.9, 2, NULL),
('7891000000003', 'Pfizer Cápsula Infantil', 'Antiviral Farmacológico', '2026-12-31', 28, 39.97, 3, NULL),
('7891000000004', 'Pfizer Loção Plus', 'Anti-inflamatório Farmacológico', '2026-12-31', 24, 54.5, 4, NULL),
('7891000000005', 'Pfizer Pomada Forte', 'Anti-inflamatório Farmacológico', '2026-12-31', 37, 27.55, 5, NULL),
('7891000000006', 'Pfizer Loção Plus', 'Antibiótico Farmacológico', '2026-12-31', 14, 25.18, 6, NULL),
('7891000000007', 'Pfizer Cápsula Max', 'Relaxante Muscular Farmacológico', '2026-12-31', 25, 25.38, 7, NULL),
('7891000000008', 'Pfizer Comprimido Forte', 'Colírio Farmacológico', '2026-12-31', 17, 85.47, 8, NULL),
('7891000000009', 'Pfizer Cápsula 500mg', 'Relaxante Muscular Farmacológico', '2026-12-31', 21, 93.24, 9, NULL),
('7891000000010', 'Pfizer Comprimido 500mg', 'Antibiótico Farmacológico', '2026-12-31', 34, 45.83, 10, NULL),
('7891000000011', 'EMS Gotas Retard', 'Antitérmico Farmacológico', '2026-12-31', 23, 52.47, 11, NULL),
('7891000000012', 'EMS Xarope Infantil', 'Antialérgico Farmacológico', '2026-12-31', 40, 54.73, 12, NULL),
('7891000000013', 'EMS Pomada 200mg', 'Antibiótico Farmacológico', '2026-12-31', 11, 56.25, 13, NULL),
('7891000000014', 'EMS Gel Retard', 'Anti-inflamatório Farmacológico', '2026-12-31', 37, 51.5, 14, NULL),
('7891000000015', 'EMS Spray XR', 'Antialérgico Farmacológico', '2026-12-31', 18, 87.51, 15, NULL),
('7891000000016', 'EMS Gel Infantil', 'Antibiótico Farmacológico', '2026-12-31', 31, 87.66, 16, NULL),
('7891000000017', 'EMS Gotas Plus', 'Colírio Farmacológico', '2026-12-31', 38, 88.89, 17, NULL),
('7891000000018', 'EMS Loção Forte', 'Antibiótico Farmacológico', '2026-12-31', 45, 78.52, 18, NULL),
('7891000000019', 'EMS Xarope XR', 'Antitérmico Farmacológico', '2026-12-31', 46, 17.21, 19, NULL),
('7891000000020', 'EMS Pomada Max', 'Vitamina Farmacológico', '2026-12-31', 27, 49.84, 20, NULL),
('7891000000021', 'Eurofarma Injecao 1g', 'Anti-inflamatório Farmacológico', '2026-12-31', 36, 92.05, 21, NULL),
('7891000000022', 'Eurofarma Xarope 500mg', 'Analgésico Farmacológico', '2026-12-31', 23, 32.51, 22, NULL),
('7891000000023', 'Eurofarma Gel Infantil', 'Colírio Farmacológico', '2026-12-31', 40, 56.45, 23, NULL),
('7891000000024', 'Eurofarma Comprimido Retard', 'Anti-inflamatório Farmacológico', '2026-12-31', 11, 60.96, 24, NULL),
('7891000000025', 'Eurofarma Loção XR', 'Protetor Gástrico Farmacológico', '2026-12-31', 29, 24.21, 25, NULL),
('7891000000026', 'Eurofarma Pomada 200mg', 'Relaxante Muscular Farmacológico', '2026-12-31', 25, 30.35, 26, NULL),
('7891000000027', 'Eurofarma Injecao Infantil', 'Protetor Gástrico Farmacológico', '2026-12-31', 30, 80.69, 27, NULL),
('7891000000028', 'Eurofarma Xarope Forte', 'Antitérmico Farmacológico', '2026-12-31', 32, 56.79, 28, NULL),
('7891000000029', 'Eurofarma Spray XR', 'Vitamina Farmacológico', '2026-12-31', 14, 56.18, 29, NULL),
('7891000000030', 'Eurofarma Pomada Retard', 'Antiviral Farmacológico', '2026-12-31', 24, 33.72, 30, NULL),
('7891000000031', 'Aché Loção Plus', 'Protetor Gástrico Farmacológico', '2026-12-31', 45, 40.98, 31, NULL),
('7891000000032', 'Aché Spray Retard', 'Analgésico Farmacológico', '2026-12-31', 24, 84.14, 32, NULL),
('7891000000033', 'Aché Comprimido Forte', 'Antialérgico Farmacológico', '2026-12-31', 27, 26, 33, NULL),
('7891000000034', 'Aché Gotas Plus', 'Anti-inflamatório Farmacológico', '2026-12-31', 21, 16.84, 34, NULL),
('7891000000035', 'Aché Loção Max', 'Antiviral Farmacológico', '2026-12-31', 16, 71.21, 35, NULL),
('7891000000036', 'Aché Loção 1g', 'Analgésico Farmacológico', '2026-12-31', 44, 95.7, 36, NULL),
('7891000000037', 'Aché Spray Max', 'Colírio Farmacológico', '2026-12-31', 32, 59.61, 37, NULL),
('7891000000038', 'Aché Gotas 1g', 'Antialérgico Farmacológico', '2026-12-31', 12, 91.15, 38, NULL),
('7891000000039', 'Aché Gel 200mg', 'Colírio Farmacológico', '2026-12-31', 33, 52.27, 39, NULL),
('7891000000040', 'Aché Injecao Retard', 'Antitérmico Farmacológico', '2026-12-31', 10, 58.72, 40, NULL),
('7891000000041', 'Biolab Cápsula 200mg', 'Analgésico Farmacológico', '2026-12-31', 12, 100.12, 41, NULL),
('7891000000042', 'Biolab Gotas Retard', 'Antiviral Farmacológico', '2026-12-31', 36, 31.32, 42, NULL),
('7891000000043', 'Biolab Gel Retard', 'Antiviral Farmacológico', '2026-12-31', 12, 29.21, 43, NULL),
('7891000000044', 'Biolab Comprimido Max', 'Protetor Gástrico Farmacológico', '2026-12-31', 45, 84.57, 44, NULL),
('7891000000045', 'Biolab Loção XR', 'Anti-inflamatório Farmacológico', '2026-12-31', 10, 64.32, 45, NULL),
('7891000000046', 'Biolab Gotas Max', 'Relaxante Muscular Farmacológico', '2026-12-31', 37, 37.36, 46, NULL),
('7891000000047', 'Biolab Xarope Retard', 'Protetor Gástrico Farmacológico', '2026-12-31', 17, 50.39, 47, NULL),
('7891000000048', 'Biolab Pomada XR', 'Protetor Gástrico Farmacológico', '2026-12-31', 48, 63.62, 48, NULL),
('7891000000049', 'Biolab Injecao Infantil', 'Antitérmico Farmacológico', '2026-12-31', 18, 78, 49, NULL),
('7891000000050', 'Biolab Cápsula Infantil', 'Antialérgico Farmacológico', '2026-12-31', 45, 30.84, 50, NULL),
('7891000000051', 'Neo Química Cápsula Plus', 'Anti-inflamatório Farmacológico', '2026-12-31', 19, 83.35, 51, NULL),
('7891000000052', 'Neo Química Gel Infantil', 'Protetor Gástrico Farmacológico', '2026-12-31', 48, 45.9, 52, NULL),
('7891000000053', 'Neo Química Loção 200mg', 'Relaxante Muscular Farmacológico', '2026-12-31', 44, 75.97, 53, NULL),
('7891000000054', 'Neo Química Injecao 1g', 'Antibiótico Farmacológico', '2026-12-31', 28, 50.91, 54, NULL),
('7891000000055', 'Neo Química Gotas Max', 'Anti-inflamatório Farmacológico', '2026-12-31', 48, 69.07, 55, NULL),
('7891000000056', 'Neo Química Gotas Max', 'Antiviral Farmacológico', '2026-12-31', 50, 21.63, 56, NULL),
('7891000000057', 'Neo Química Xarope Forte', 'Relaxante Muscular Farmacológico', '2026-12-31', 12, 35.78, 57, NULL),
('7891000000058', 'Neo Química Gotas 500mg', 'Antibiótico Farmacológico', '2026-12-31', 24, 43, 58, NULL),
('7891000000059', 'Neo Química Cápsula 1g', 'Anti-inflamatório Farmacológico', '2026-12-31', 25, 14.05, 59, NULL),
('7891000000060', 'Neo Química Cápsula Plus', 'Antibiótico Farmacológico', '2026-12-31', 31, 13.61, 60, NULL),
('7891000000061', 'Sanofi Pomada Max', 'Antiviral Farmacológico', '2026-12-31', 14, 22.04, 61, NULL),
('7891000000062', 'Sanofi Injecao XR', 'Analgésico Farmacológico', '2026-12-31', 10, 86.38, 62, NULL),
('7891000000063', 'Sanofi Cápsula Infantil', 'Analgésico Farmacológico', '2026-12-31', 11, 85.57, 63, NULL),
('7891000000064', 'Sanofi Pomada Plus', 'Antibiótico Farmacológico', '2026-12-31', 13, 66.18, 64, NULL),
('7891000000065', 'Sanofi Gotas 500mg', 'Protetor Gástrico Farmacológico', '2026-12-31', 46, 16.18, 65, NULL),
('7891000000066', 'Sanofi Comprimido Forte', 'Antialérgico Farmacológico', '2026-12-31', 27, 62.81, 66, NULL),
('7891000000067', 'Sanofi Gotas 1g', 'Antibiótico Farmacológico', '2026-12-31', 31, 23.88, 67, NULL),
('7891000000068', 'Sanofi Injecao 500mg', 'Colírio Farmacológico', '2026-12-31', 12, 94.62, 68, NULL),
('7891000000069', 'Sanofi Injecao Infantil', 'Analgésico Farmacológico', '2026-12-31', 18, 52.51, 69, NULL),
('7891000000070', 'Sanofi Loção Plus', 'Colírio Farmacológico', '2026-12-31', 47, 36.78, 70, NULL),
('7891000000071', 'Roche Injecao 500mg', 'Protetor Gástrico Farmacológico', '2026-12-31', 41, 72.9, 71, NULL),
('7891000000072', 'Roche Xarope Max', 'Anti-inflamatório Farmacológico', '2026-12-31', 50, 95.57, 72, NULL),
('7891000000073', 'Roche Gel Infantil', 'Antitérmico Farmacológico', '2026-12-31', 26, 75.74, 73, NULL),
('7891000000074', 'Roche Xarope 200mg', 'Anti-inflamatório Farmacológico', '2026-12-31', 43, 91.58, 74, NULL),
('7891000000075', 'Roche Spray Infantil', 'Anti-inflamatório Farmacológico', '2026-12-31', 33, 71.24, 75, NULL),
('7891000000076', 'Roche Gel Plus', 'Antialérgico Farmacológico', '2026-12-31', 47, 63.45, 76, NULL),
('7891000000077', 'Roche Cápsula Retard', 'Analgésico Farmacológico', '2026-12-31', 50, 42.84, 77, NULL),
('7891000000078', 'Roche Xarope Max', 'Colírio Farmacológico', '2026-12-31', 46, 65.43, 78, NULL),
('7891000000079', 'Roche Xarope Forte', 'Antiviral Farmacológico', '2026-12-31', 22, 81.32, 79, NULL),
('7891000000080', 'Roche Loção XR', 'Analgésico Farmacológico', '2026-12-31', 28, 63.29, 80, NULL),
('7891000000081', 'Bayer Cápsula 500mg', 'Antibiótico Farmacológico', '2026-12-31', 21, 20.31, 81, NULL),
('7891000000082', 'Bayer Injecao XR', 'Vitamina Farmacológico', '2026-12-31', 47, 96.64, 82, NULL),
('7891000000083', 'Bayer Cápsula Retard', 'Vitamina Farmacológico', '2026-12-31', 37, 79.77, 83, NULL),
('7891000000084', 'Bayer Spray 200mg', 'Vitamina Farmacológico', '2026-12-31', 24, 26.65, 84, NULL),
('7891000000085', 'Bayer Xarope 500mg', 'Vitamina Farmacológico', '2026-12-31', 13, 21.48, 85, NULL),
('7891000000086', 'Bayer Gotas Retard', 'Colírio Farmacológico', '2026-12-31', 38, 19.63, 86, NULL),
('7891000000087', 'Bayer Gel Retard', 'Antitérmico Farmacológico', '2026-12-31', 33, 58.94, 87, NULL),
('7891000000088', 'Bayer Pomada Max', 'Antibiótico Farmacológico', '2026-12-31', 15, 95.7, 88, NULL),
('7891000000089', 'Bayer Pomada Plus', 'Protetor Gástrico Farmacológico', '2026-12-31', 22, 94.44, 89, NULL),
('7891000000090', 'Bayer Loção Infantil', 'Anti-inflamatório Farmacológico', '2026-12-31', 44, 26.29, 90, NULL),
('7891000000091', 'Medley Pomada Retard', 'Relaxante Muscular Farmacológico', '2026-12-31', 17, 68.55, 91, NULL),
('7891000000092', 'Medley Gel XR', 'Antiviral Farmacológico', '2026-12-31', 38, 15.03, 92, NULL),
('7891000000093', 'Medley Gotas Plus', 'Protetor Gástrico Farmacológico', '2026-12-31', 12, 39.91, 93, NULL),
('7891000000094', 'Medley Spray 200mg', 'Antiviral Farmacológico', '2026-12-31', 16, 57.2, 94, NULL),
('7891000000095', 'Medley Xarope XR', 'Colírio Farmacológico', '2026-12-31', 30, 53.07, 95, NULL),
('7891000000096', 'Medley Cápsula 200mg', 'Relaxante Muscular Farmacológico', '2026-12-31', 20, 77.22, 96, NULL),
('7891000000097', 'Medley Gotas Max', 'Antiviral Farmacológico', '2026-12-31', 38, 43.74, 97, NULL),
('7891000000098', 'Medley Comprimido Retard', 'Antialérgico Farmacológico', '2026-12-31', 37, 34.68, 98, NULL),
('7891000000099', 'Medley Comprimido Max', 'Antialérgico Farmacológico', '2026-12-31', 38, 16.3, 99, NULL),
('7891000000100', 'Medley Comprimido XR', 'Antialérgico Farmacológico', '2026-12-31', 49, 32.36, 100, NULL);


-- ===============================
-- VIEWS, PROCEDURES E TRIGGERS
-- ===============================
CREATE OR REPLACE VIEW vw_relatorio_vendas_detalhadas AS
SELECT
    V.NotaFiscal_Saida,
    V.Data_Venda AS Data_Venda,
    V.Valor_Venda AS Valor_Total_Venda,
    M.Nome_Med AS Medicamento_Vendido,
    M.Valor_Med AS Valor_Base_Medicamento,
    IV.Qtd_ItemVenda AS Quantidade,
    IV.Valor_ItemVenda AS Valor_Unitario_Venda,
    F.Nome_Fun AS Funcionario_Venda,
    D.Nome_Drog AS Drogaria_Venda
FROM
    venda V
JOIN
    item_venda IV ON V.NotaFiscal_Saida = IV.NotaFiscal_Saida
JOIN
    medicamento M ON IV.Cod_Med = M.Cod_Med
JOIN
    funcionario F ON V.CPF = F.CPF
JOIN
    drogaria D ON V.CNPJ_Drog = D.CNPJ_Drog;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_atualiza_estoque_apos_compra $$
CREATE PROCEDURE sp_atualiza_estoque_apos_compra (
    IN p_Cod_Med INT,
    IN p_Qtd_Comprada INT
)
BEGIN
    DECLARE v_estoque_atual INT;
    SELECT Qtd_Med INTO v_estoque_atual FROM medicamento WHERE Cod_Med = p_Cod_Med;
    UPDATE medicamento SET Qtd_Med = v_estoque_atual + p_Qtd_Comprada WHERE Cod_Med = p_Cod_Med;
    SELECT CONCAT('Sucesso! Novo Total: ', v_estoque_atual + p_Qtd_Comprada) AS Mensagem_Estoque;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS trg_baixa_estoque_apos_venda $$
DROP TRIGGER IF EXISTS trg_baixa_estoque_apos_compra $$

-- Trigger: decrementa estoque de medicamentos ao FINALIZAR uma venda
CREATE TRIGGER trg_finalizar_venda
AFTER UPDATE ON venda
FOR EACH ROW
BEGIN
    IF NEW.Finalizada = 1 AND OLD.Finalizada = 0 THEN
        UPDATE medicamento m
        JOIN item_venda iv ON m.Cod_Med = iv.Cod_Med
        SET m.Qtd_Med = m.Qtd_Med - iv.Qtd_ItemVenda
        WHERE iv.NotaFiscal_Saida = NEW.NotaFiscal_Saida;
    END IF;
END $$

-- Trigger: decrementa estoque do catálogo ao FINALIZAR uma compra
CREATE TRIGGER trg_finalizar_compra
AFTER UPDATE ON compra
FOR EACH ROW
BEGIN
    IF NEW.Finalizada = 1 AND OLD.Finalizada = 0 THEN
        UPDATE catalogo_medicamento cm
        JOIN item i ON cm.Cod_CatMed = i.Cod_CatMed
        SET cm.quantidade = cm.quantidade - i.Qtd_Item
        WHERE i.NotaFiscal_Entrada = NEW.NotaFiscal_Entrada;
    END IF;
END $$

DELIMITER ;
