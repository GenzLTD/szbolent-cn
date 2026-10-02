#!/usr/bin/env bash
# =============================================================
# import-poems.sh — 手动在线上机执行：把 9,417 首陆游作品导入 cn WP。
# （deploy.yml 已含同款自动步骤；此脚本用于单独重跑/试点。）
#
# 用法（在目标机上）：
#   bash scripts/import-poems.sh           全量
#   LIMIT=30 bash scripts/import-poems.sh  仅前 30 篇
# =============================================================
set -euo pipefail

LIMIT="${LIMIT:-0}"
REMOTE_DIR="/opt/szbolent-cn"
JSON_SRC="${REMOTE_DIR}/bookstore-data/luyou.json"

if ! docker ps --format "{{.Names}}" | grep -q "^bolent_wp$"; then
  echo "⏭ bolent_wp 未运行，退出"; exit 0
fi

if [ ! -f "$JSON_SRC" ]; then
  echo "❌ 缺少 $JSON_SRC（先在仓库放 bookstore-data/luyou.json 并 push）"; exit 1
fi

docker exec bolent_wp mkdir -p /var/www/html/wp-content/uploads/bookstore
docker cp "$JSON_SRC" bolent_wp:/var/www/html/wp-content/uploads/bookstore/luyou.json
docker exec bolent_wp chown -R www-data:www-data /var/www/html/wp-content/uploads/bookstore

# 确保脚本在容器内可用
docker cp "${REMOTE_DIR}/scripts/import-poems.php" bolent_wp:/var/www/html/wp-content/scripts/import-poems.php 2>/dev/null || true

echo "[*] 开始导入（limit=${LIMIT}）..."
docker exec bolent_wp php /var/www/html/wp-content/scripts/import-poems.php "$LIMIT"
echo "✅ 导入完成"
