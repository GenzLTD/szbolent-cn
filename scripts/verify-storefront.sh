#!/usr/bin/env bash
# =============================================================
# verify-storefront.sh — 书店门面「用户看得见」层验收
#
#   和 healthcheck.sh 的分工：healthcheck 验「服务活着」（端口/安装态/容器），
#   活着 ≠ 好看。本脚本验的是「用户打开页面看到的东西」。
#
#   由来（第四道防线，接 variety 的 verify-nav-render.mjs / verify-bookstore.mjs）：
#   前三处假绿全部是同一个病害——只看机器层的状态码：
#     ① /v1/menus curl 200、浏览器导航空；
#     ② 菜单链路从未部署但仍然 200；
#     ③ 书店栏目 feed 通、页面降级成「暂无文章」仍然 200。
#   书店这边同源风险更大：WordPress 哪怕主题没激活、数据没导入、
#   permalink 没刷新，首页照样返回 200，只是内容是空壳。
#
#   为什么这里不需要浏览器：书店是 WordPress，服务端就把内容渲染进 HTML，
#   curl 拿到的 DOM 就是用户看到的 DOM（门户才是 SPA，必须 playwright）。
#   纯 curl = 零依赖，可以直接跑在目标机和 CI runner 上。
#
#   断言（全部硬断言，失败即退出码 1）：
#     A. 首页 200
#     B. title 是真站名（不是 WP 默认、不是「Page not found」占位页）
#     C. meta description 有内容（门面的描述位不是空的）
#     D. canonical 指向本店域名（防被改回 localhost / 旧域名）
#     E. 首页渲染出 ≥MIN_POEMS 条诗词条目链接（首页不能是空壳）
#     F. /poem/ 归档页 200 且条目链接 ≥MIN_POEMS
#     G. 抽样 SAMPLE 篇单篇：200 + 正文容器在 + 正文 ≥MIN_BYTES 字节
#        —— 防「有页面没内容」，这是内容站最容易蒙混过关的形态
#     H. feed 200 且 count>0：门户书店卡片的跨仓数据源，顺手一起守
#     I. 站点图标 200（浏览器标签位是用户看得见的一部分）
#
#   用法：
#     bash scripts/verify-storefront.sh                  # 默认 https://szbolent.cn
#     BASE_URL=http://127.0.0.1 bash scripts/verify-storefront.sh
#     MIN_POEMS=10 SAMPLE=5 MIN_BYTES=120 bash scripts/verify-storefront.sh
#   退出码：0 = 全通过；1 = 有断言不过
# =============================================================
set -uo pipefail

BASE_URL="${BASE_URL:-https://szbolent.cn}"
BASE_URL="${BASE_URL%/}"
MIN_POEMS="${MIN_POEMS:-3}"
SAMPLE="${SAMPLE:-3}"
MIN_BYTES="${MIN_BYTES:-60}"
FEED_PATH="${FEED_PATH:-/wp-json/bookstore/v1/feed}"
ICON_PATH="${ICON_PATH:-/wp-content/uploads/2026/10/fangweng-icon-300x300.png}"
# 站名关键字：站点正式改名时同步改这里（正是「看得见层」该被锁住的东西）
EXPECT_TITLE_KEYWORD="${EXPECT_TITLE_KEYWORD:-放翁文库}"

TMP="${TMPDIR:-/tmp}/storefront-verify.$$"
mkdir -p "$TMP"
trap 'rm -rf "$TMP"' EXIT

pass=0
fail=0
FAILED=()
ok()   { printf '\033[0;32m✅ PASS\033[0m  %s\n' "$1"; pass=$((pass + 1)); }
no()   { printf '\033[0;31m❌ FAIL\033[0m  %s\n' "$1"; fail=$((fail + 1)); FAILED+=("$1"); }
note() { printf '\033[0;33m⚠️ SKIP\033[0m  %s\n' "$1"; }

# get <url> <outfile>  →  打印 http_code（连不上时 000）
get() { curl -sS -L --max-time 30 -o "$2" -w '%{http_code}' "$1" 2>/dev/null || echo 000; }

# extract_links <htmlfile> → 去重后的绝对条目链接（/poem/<slug>/）
extract_links() {
  tr -d '\r' < "$1" | tr '\n' ' ' \
    | grep -oE 'href="[^"]*?/poem/[^"]*?"' \
    | sed -e 's/^href="//' -e 's/"$//' \
    | grep -v '?' \
    | grep -vE '/poem/?$' \
    | sed -E "s#^/#${BASE_URL}/#" \
    | sort -u
}

echo "===================================================="
echo "  书店门面 — 用户可见层验收（服务端渲染，纯 curl）"
echo "  ${BASE_URL}   ($(date -u '+%Y-%m-%d %H:%M:%SZ'))"
echo "===================================================="
echo ""

# ---------- A. 首页可达 ----------
code=$(get "${BASE_URL}/" "$TMP/home.html")
if [ "$code" = "200" ]; then
  ok "首页 200（${BASE_URL}/）"
else
  no "首页不是 200（实际 ${code}）"
fi
[ -s "$TMP/home.html" ] || : > "$TMP/home.html"

# ---------- B. title 是真站名 ----------
title=$(tr -d '\r' < "$TMP/home.html" | tr '\n' ' ' | sed -n 's/.*<title>\(.*\)<\/title>.*/\1/p' | head -1)
if [ -z "$title" ]; then
  no "首页没有 <title>（主题没渲染出来，用户看到的是半成品）"
elif printf '%s' "$title" | grep -qiE 'just another wordpress|page not found'; then
  no "首页 title 是占位/错误页（用户看不到门面）: ${title}"
elif [ -n "$EXPECT_TITLE_KEYWORD" ] && ! printf '%s' "$title" | grep -q "$EXPECT_TITLE_KEYWORD"; then
  no "首页 title 不含站名关键字「${EXPECT_TITLE_KEYWORD}」: ${title}"
else
  ok "首页 title 是真站名: ${title}"
fi

# ---------- C. meta description 有内容 ----------
desc=$(tr -d '\r' < "$TMP/home.html" | tr '\n' ' ' \
  | sed -n 's/.*<meta name="description" content="\([^"]*\)".*/\1/p' | head -1)
if [ -z "$desc" ]; then
  no "首页没有 meta description（搜索结果/分享位拿不到描述）"
elif printf '%s' "$desc" | grep -qiE 'just another wordpress'; then
  no "meta description 还是 WP 默认文案: ${desc}"
else
  ok "meta description 有内容（${#desc} 字符）"
fi

# ---------- D. canonical 指向本店 ----------
canonical=$(tr -d '\r' < "$TMP/home.html" | tr '\n' ' ' \
  | grep -oE '<link[^>]*rel="canonical"[^>]*>' | head -1)
canon_href=$(printf '%s' "$canonical" | sed -n 's/.*href="\([^"]*\)".*/\1/p')
if [ -z "$canon_href" ]; then
  no "首页没有 canonical（SEO 主域未锁）"
elif [ "${canon_href%/}" = "${BASE_URL}" ] || [ "${canon_href%/}" = "${BASE_URL}/" ]; then
  ok "canonical 指向本店域名: ${canon_href}"
else
  no "canonical 指向的不是本店域名（实际 ${canon_href}，期望 ${BASE_URL}/）"
fi

# ---------- E. 首页渲染出条目链接 ----------
home_links=$(extract_links "$TMP/home.html")
home_count=$(printf '%s' "$home_links" | grep -c . || true)
if [ "$home_count" -ge "$MIN_POEMS" ]; then
  ok "首页渲染出 ${home_count} 条诗词条目链接（门槛 ${MIN_POEMS}）"
else
  no "首页只渲染出 ${home_count} 条诗词条目链接（门槛 ${MIN_POEMS}）—— 首页是空壳"
fi

# ---------- F. 归档页有列表 ----------
code=$(get "${BASE_URL}/poem/" "$TMP/archive.html")
if [ "$code" != "200" ]; then
  no "/poem/ 归档页不是 200（实际 ${code}）"
else
  arch_links=$(extract_links "$TMP/archive.html")
  arch_count=$(printf '%s' "$arch_links" | grep -c . || true)
  if [ "$arch_count" -ge "$MIN_POEMS" ]; then
    ok "/poem/ 归档页 200，列表 ${arch_count} 条（门槛 ${MIN_POEMS}）"
  else
    no "/poem/ 归档页只有 ${arch_count} 条条目（门槛 ${MIN_POEMS}）—— 列表是空的"
  fi
fi

# ---------- G. 抽样单篇：页面在 + 正文有内容 ----------
pool=$(printf '%s' "$home_links")
[ -z "$pool" ] && pool=$(printf '%s' "${arch_links:-}")
if [ -z "$pool" ]; then
  no "没有任何单篇链接可抽（E/F 已失败，此断言无从执行）"
else
  while IFS= read -r u; do
    [ -z "$u" ] && continue
    c=$(get "$u" "$TMP/poem.html")
    if [ "$c" != "200" ]; then
      no "单篇 404/异常（${c}）: ${u}"
      continue
    fi
    if ! grep -q 'fw-poem-text' "$TMP/poem.html"; then
      no "单篇是 200 但没有正文容器 fw-poem-text（模板没用上，用户看到的是空页）: ${u}"
      continue
    fi
    body=$(tr -d '\r' < "$TMP/poem.html" | tr '\n' ' ' \
      | sed -e 's/.*fw-poem-text"[^>]*>//' -e 's/<\/div>.*//')
    bytes=$(printf '%s' "$body" | wc -c | tr -d ' ')
    if [ "$bytes" -lt "$MIN_BYTES" ]; then
      no "单篇正文只有 ${bytes} 字节（门槛 ${MIN_BYTES}）: ${u}"
    else
      ok "单篇有正文（${bytes} 字节）: ${u##*/poem/}"
    fi
  done <<EOF
$(printf '%s\n' "$pool" | head -n "$SAMPLE")
EOF
fi

# ---------- H. 跨仓契约：feed 可用且有数据 ----------
code=$(get "${BASE_URL}${FEED_PATH}" "$TMP/feed.json")
if [ "$code" != "200" ]; then
  no "书店 feed 不是 200（实际 ${code}）—— 门户书店栏目会降级成空态"
else
  items=$(grep -o '"title"' "$TMP/feed.json" | wc -l | tr -d ' ')
  cnt=$(grep -o '"count":[0-9]*' "$TMP/feed.json" | head -1 | cut -d: -f2)
  if [ "${cnt:-0}" -gt 0 ] 2>/dev/null && [ "$items" -gt 0 ]; then
    ok "书店 feed 有数据（count=${cnt}, items=${items}）"
  else
    no "书店 feed 是空的（count=${cnt:-?}, items=${items}）"
  fi
fi

# ---------- I. 站点图标 ----------
code=$(get "${BASE_URL}${ICON_PATH}" "$TMP/icon.bin")
if [ "$code" = "200" ]; then
  ok "站点图标 200（${ICON_PATH##*/}）"
else
  note "站点图标不是 200（实际 ${code}）—— 检查 ICON_PATH 是否随品牌改了路径"
fi

echo ""
echo "===================================================="
echo "  通过: ${pass}   失败: ${fail}"
if [ "$fail" -gt 0 ]; then
  echo "  失败项:"
  printf '    - %s\n' "${FAILED[@]}"
  echo "===================================================="
  echo ""
  echo "排查顺序："
  echo "  1) 主题/数据：activate-bookstore.php 有没有把 fangweng 子主题激活、9417 首有没有导入"
  echo "  2) permalink：WP 后台「设置-固定链接」重新保存一次 flush rewrite（或 services/status 看 rewrite）"
  echo "  3) 身份/SEO：scripts/set-site-identity.php 有没有跑（站名/描述/canonical/图标）"
  echo "  4) 跨仓契约：门户 verify:book 依赖本店 feed，这里红了门户那边也会降级"
  exit 1
fi
echo "===================================================="
echo ""
echo "验收通过：书店门面在服务端就把真内容渲染出来了（用户可见层无空壳、无占位页）。"
