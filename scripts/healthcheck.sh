#!/usr/bin/env bash
# =============================================================
# healthcheck.sh — 健康检查（本机读，非断言口径）
# =============================================================
set -uo pipefail
fail=0

check() { # check <名称> <命令>
  if eval "$2" >/dev/null 2>&1; then echo "✅ $1"; else echo "❌ $1"; fail=1; fi
}

check "nginx :80"        'curl -sf -o /dev/null http://127.0.0.1:80'
check "wordpress :8080"  'curl -sf -o /dev/null http://127.0.0.1:8080'
check "pgvector :5433"   'docker exec pgvector pg_isready -U postgres'
check "mysql 存活"        'docker exec bolent_wp_mysql mysqladmin ping -h localhost'

if [ "$fail" -eq 0 ]; then echo "=== 全部健康 ==="; else echo "=== 有服务异常 ==="; fi
exit $fail
