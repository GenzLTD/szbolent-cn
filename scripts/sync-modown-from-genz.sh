#!/usr/bin/env bash
# =============================================================
# sync-modown-from-genz.sh — Phase B 前置：把 modown 主题与
#   erphpdown 插件二进制取到本仓库的 themes/ 与 plugins/。
#   （Lyanna 非独立插件，其个人主页能力已内置 modown 主题，无需单独复制）
#
# 两种来源（自动选择）：
#   1) 本地源（默认，推荐本环境使用）：
#      Poetry-modown 备份仓的 WP 根在 public/ 下（非仓库根！），
#      即 …/Poetry-modown/public/wp-content/{themes/modown,plugins/erphpdown}
#      用 SRC_LOCAL 指向该 public/wp-content 目录即可，无需网络。
#   2) 远程源（CI runner 能免密 SSH 到 genz.ltd 时）：
#      设置 GENZ_HOST，走 SSH rsync。
#
# 用法：
#   # 本地源（本环境）
#   SRC_LOCAL=/e/servbay-win-kit/GenzLTDbackup/Poetry-modown/public/wp-content \
#     bash scripts/sync-modown-from-genz.sh
#   # 远程源
#   GENZ_HOST=genz bash scripts/sync-modown-from-genz.sh
#
# 产物：本仓库 themes/modown + plugins/{erphpdown[,lyanna]}
#   随后 git add themes plugins && git commit && git push 触发 CI。
# =============================================================
set -euo pipefail

LOCAL_REPO="${LOCAL_REPO:-$(cd "$(dirname "$0")/.." && pwd)}"
GENZ_HOST="${GENZ_HOST:-genz}"
GENZ_WP_ROOT="${GENZ_WP_ROOT:-/var/www/html}"
SRC_LOCAL="${SRC_LOCAL:-}"

THEME="modown"
PLUGINS=(erphpdown)   # Lyanna 非独立插件，能力已内置 modown 主题（template/page-personal.php 等），勿单独复制

mkdir -p "$LOCAL_REPO/themes" "$LOCAL_REPO/plugins"

copy_local() {
  local src="$1" dst="$2" label="$3"
  if [ ! -d "$src" ]; then echo "⚠️ 本地源缺失 $label: $src（跳过）"; return 1; fi
  echo "[*] 本地复制 $label ..."
  mkdir -p "$dst"
  # rsync 优先，否则退化为 cp -r
  if command -v rsync >/dev/null 2>&1; then
    rsync -a --delete "$src/" "$dst/"
  else
    find "$dst" -mindepth 1 -delete 2>/dev/null || true
    cp -r "$src/." "$dst/"
  fi
  echo "✅ $label → $dst"
}

copy_remote() {
  local src="$1" dst="$2" label="$3"
  echo "[*] rsync $label from ${GENZ_HOST}:${GENZ_WP_ROOT} ..."
  if ! ssh "$GENZ_HOST" "test -d ${GENZ_WP_ROOT}/${src}"; then
    echo "⚠️ 远端缺失 $label，跳过（请确认路径/插件名）"; return 1
  fi
  mkdir -p "$dst"
  rsync -avz --delete "${GENZ_HOST}:${GENZ_WP_ROOT}/${src}/" "$dst/"
  echo "✅ $label → $dst"
}

if [ -n "$SRC_LOCAL" ]; then
  echo "[模式] 本地源：$SRC_LOCAL"
  copy_local "$SRC_LOCAL/themes/$THEME" "$LOCAL_REPO/themes/$THEME" "主题 $THEME"
  for p in "${PLUGINS[@]}"; do
    copy_local "$SRC_LOCAL/plugins/$p" "$LOCAL_REPO/plugins/$p" "插件 $p" || true
  done
else
  echo "[模式] 远程源：$GENZ_HOST"
  copy_remote "wp-content/themes/$THEME" "$LOCAL_REPO/themes/$THEME" "主题 $THEME"
  for p in "${PLUGINS[@]}"; do
    copy_remote "wp-content/plugins/$p" "$LOCAL_REPO/plugins/$p" "插件 $p" || true
  done
fi

echo "✅ 完成。下一步："
echo "   git add themes plugins && git commit -m 'feat: 引入 modown 主题与自运营插件' && git push"
echo "   CI 自动 rsync 进容器；启用：取消 docker-compose.yml 中 themes/plugins 两行注释后 docker compose up -d，WP 后台启用 modown + 激活 Lyanna/erphpdown。"
