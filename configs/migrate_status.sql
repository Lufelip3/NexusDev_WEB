-- ============================================================
-- MIGRATION: Adiciona status de finalização e atualiza triggers
-- Execute este script no banco 'drogariaWEB' existente
-- ============================================================

-- 1. Adiciona coluna Finalizada na tabela venda (se não existir)
ALTER TABLE venda 
    ADD COLUMN IF NOT EXISTS Finalizada TINYINT(1) NOT NULL DEFAULT 0;

-- 2. Adiciona coluna Finalizada na tabela compra (se não existir)
ALTER TABLE compra 
    ADD COLUMN IF NOT EXISTS Finalizada TINYINT(1) NOT NULL DEFAULT 0;

-- 3. Remove os triggers antigos (baseados em INSERT)
DROP TRIGGER IF EXISTS trg_baixa_estoque_apos_venda;
DROP TRIGGER IF EXISTS trg_baixa_estoque_apos_compra;

-- 4. Cria o novo trigger para VENDA (dispara ao finalizar)
DELIMITER $$
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
DELIMITER ;

-- 5. Cria o novo trigger para COMPRA (dispara ao finalizar)
DELIMITER $$
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

-- ============================================================
-- Verificação: confira se os triggers foram criados
-- ============================================================
SHOW TRIGGERS LIKE 'trg_finalizar%';
