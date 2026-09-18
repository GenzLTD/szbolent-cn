#!/usr/bin/env bash
# =============================================================
# bootstrap.sh — 首次初始化（新机器一次性）
#   建目录、确认容器运行时与 nginx。幂等，可重复跑。
# =============================================================
set -euo pipefail

echo "[bootstrap] 建目录 ..."
mkdir -p /opt/szbolent-cn /data/pgvector /etc/nginx/conf.d

echo "[bootstrap] 容器运行时："
if command -v docker >/dev/null 2>&1; then docker --version; else echo "  ⚠️ 未找到 docker CLI（本机可能为 podman 模拟）"; fi
if command -v podman >/dev/null 2>&1; then podman --version; fi

echo "[bootstrap] nginx："
if command -v nginx >/dev/null 2>&1; then nginx -v 2>&1; else echo "  ⚠️ 未找到 nginx"; fi

echo "[bootstrap] 完成。后续部署由 CI（GitHub Actions）执行。"
