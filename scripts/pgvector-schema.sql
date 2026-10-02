-- =============================================================
-- poems 表：陆游作品全集（9,417 篇）落 pgvector
-- 阶段一（当前）：先存结构化原文，embedding 列预留（NULL）。
-- 阶段二（向量化）：回填 embedding 后再建 ANN 索引。
--
-- 由 deploy.yml 通过 pgvector 容器执行：
--   docker exec -i pgvector psql -U postgres -f - < scripts/pgvector-schema.sql
-- （密码来自 szbolent-cn/.env 的 PGVECTOR_PASSWORD，容器本地认证通常使用 peer/trust，
--   若要求密码则在宿主侧用 `psql "postgresql://postgres:<PW>@127.0.0.1:5433/postgres"`）
-- =============================================================

CREATE EXTENSION IF NOT EXISTS vector;

CREATE TABLE IF NOT EXISTS poems (
  luyou_id   TEXT PRIMARY KEY,                       -- 如 luyou-shi:00126
  author     TEXT NOT NULL DEFAULT '陆游',
  dynasty    TEXT,                                   -- 宋
  genre      TEXT,                                   -- 诗 / 词
  title      TEXT NOT NULL,
  paragraphs TEXT[] NOT NULL,                       -- 正文逐段
  strains    TEXT[],                                 -- 词牌/韵律（词有，诗常空）
  source     TEXT,                                   -- 出处（chinese-poetry 等）
  embedding  vector(1536),                          -- 预留：语义向量，当前 NULL
  created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- 非向量辅助索引（按体裁/作者检索）
CREATE INDEX IF NOT EXISTS idx_poems_genre  ON poems (genre);
CREATE INDEX IF NOT EXISTS idx_poems_author ON poems (author);

-- ===== 向量化完成后，取消下一行注释以建 ANN 索引 =====
-- 维度需与所选模型一致；若非 1536 维，先执行：
--   ALTER TABLE poems ALTER COLUMN embedding TYPE vector(<N>);
-- CREATE INDEX IF NOT EXISTS idx_poems_embedding
--   ON poems USING ivfflat (embedding vector_cosine_ops) WITH (lists = 100);
