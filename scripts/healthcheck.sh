#!/usr/bin/env bash
# =============================================================
# healthcheck.sh — 健康检查（带重试，容忍服务启动时间）
#   CI 部署后立即调用时，WordPress/MySQL 可能还在初始化，
#   故对每项做有限重试（默认 12 次 × 5s = 60s 上限）。
# =============================================================
set -uo pipefail
fail=0

# check <名称> <命令> [重试次数=12] [间隔秒=5]
check() {
  local name="$1" cmd="$2" tries="${3:-12}" delay="${4:-5}" i=1
  while [ "$i" -le "$tries" ]; do
    if eval "$cmd" >/dev/null 2>&1; then
      echo "✅ $name"
      return 0
    fi
    [ "$i" -lt "$tries" ] && sleep "$delay"
    i=$((i + 1))
  done
  echo "❌ $name (重试 ${tries} 次仍失败)"
  fail=1
}

check "nginx :80"       'curl -sf -o /dev/null http://127.0.0.1:80'
check "wordpress :8080" 'curl -sf -o /dev/null http://127.0.0.1:8080'
check "pgvector :5433"  'docker exec pgvector pg_isready -U postgres'
check "mysql 存活"       'docker exec bolent_wp_mysql mysqladmin ping -h localhost'

if [ "$fail" -eq 0 ]; then echo "=== 全部健康 ==="; else echo "=== 有服务异常 ==="; fi
exit $fail
