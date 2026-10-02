/**
 * 个人主页模板脚本 page-personal.php
 * 依赖：页面内联设定 window.MODOWN_PERSONAL = { hasMusic, lyrics }
 */
(function () {
    'use strict';
    var MOD = window.MODOWN_PERSONAL || { hasMusic: false, lyrics: [] };

    // 主题切换
    var themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        var isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            document.body.classList.add('dark-mode');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>白天';
        }
        themeToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            var isNowDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isNowDark);
            themeToggle.innerHTML = isNowDark ? '<i class="fas fa-sun"></i>白天' : '<i class="fas fa-moon"></i>黑夜';
        });
    }

    // 随机古诗词
    function fetchQuote() {
        var qt = document.getElementById('quoteText');
        var qa = document.getElementById('quoteAuthor');
        if (!qt || !qa) return;
        fetch('https://api.xyttkx.cn/gsc.php?type=json')
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.content && d.poesy) {
                    qt.textContent = d.content;
                    qa.textContent = '—— ' + d.poesy;
                }
            })
            .catch(function () {
                qt.textContent = '只要过的开心，就不算虚度光阴。';
                qa.textContent = '—— 蔡同学';
            });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fetchQuote);
    } else {
        fetchQuote();
    }

    // 技能条动画
    function initSkillBars() {
        var bars = document.querySelectorAll('.skill-progress');
        if (!bars.length) return;
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var pct = entry.target.getAttribute('data-percentage');
                    if (pct) entry.target.style.width = pct + '%';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        bars.forEach(function (b) { observer.observe(b); });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSkillBars);
    } else {
        initSkillBars();
    }

    // 音乐播放器（仅当 MODOWN_PERSONAL.hasMusic 且存在相应 DOM）
    if (!MOD.hasMusic || !Array.isArray(MOD.lyrics)) return;
    var playBtn = document.getElementById('playBtn');
    var progressBar = document.getElementById('progressBar');
    var progressContainer = document.getElementById('progressContainer');
    var currentTimeEl = document.getElementById('currentTime');
    var totalTimeEl = document.getElementById('totalTime');
    var lyricsContainer = document.getElementById('lyricsContainer');
    var audioPlayer = document.getElementById('audioPlayer');
    if (!playBtn || !progressBar || !progressContainer || !audioPlayer || !lyricsContainer) return;

    var lyricsData = MOD.lyrics;
    lyricsData.forEach(function (lyric, index) {
        var div = document.createElement('div');
        div.className = 'lyric-line';
        div.id = 'line-' + index;
        div.textContent = lyric.text;
        lyricsContainer.appendChild(div);
    });

    var isPlaying = false;
    var currentLyricIndex = -1;

    function syncLyrics(ms) {
        var i, newIndex = -1;
        for (i = 0; i < lyricsData.length; i++) {
            if (ms >= lyricsData[i].time) newIndex = i;
            else break;
        }
        if (newIndex === currentLyricIndex) return;
        currentLyricIndex = newIndex;
        var lines = document.querySelectorAll('.lyric-line');
        lines.forEach(function (line, idx) {
            line.classList.remove('active', 'prev', 'next');
            if (idx === currentLyricIndex) line.classList.add('active');
            else if (idx === currentLyricIndex - 1) line.classList.add('prev');
            else if (idx === currentLyricIndex + 1) line.classList.add('next');
        });
        if (currentLyricIndex >= 0) {
            var cur = document.getElementById('line-' + currentLyricIndex);
            if (cur) cur.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function togglePlayback() {
        isPlaying = !isPlaying;
        playBtn.innerHTML = isPlaying ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>';
        if (isPlaying) audioPlayer.play();
        else audioPlayer.pause();
    }

    function updateProgress() {
        var t = audioPlayer.currentTime;
        var d = audioPlayer.duration;
        progressBar.style.width = (d > 0 ? (t / d) * 100 : 0) + '%';
        if (currentTimeEl) {
            var m = Math.floor(t / 60);
            var s = Math.floor(t % 60);
            currentTimeEl.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }
        syncLyrics(t * 1000);
        if (isPlaying) requestAnimationFrame(updateProgress);
    }

    progressContainer.addEventListener('click', function (e) {
        var rect = progressContainer.getBoundingClientRect();
        var p = (e.clientX - rect.left) / rect.width;
        audioPlayer.currentTime = p * audioPlayer.duration;
        progressBar.style.width = p * 100 + '%';
        syncLyrics(p * audioPlayer.duration * 1000);
    });

    playBtn.addEventListener('click', togglePlayback);

    audioPlayer.addEventListener('loadedmetadata', function () {
        var d = audioPlayer.duration;
        if (totalTimeEl) {
            var m = Math.floor(d / 60);
            var s = Math.floor(d % 60);
            totalTimeEl.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }
    });

    audioPlayer.addEventListener('play', updateProgress);

    audioPlayer.addEventListener('pause', function () {
        isPlaying = false;
        playBtn.innerHTML = '<i class="fas fa-play"></i>';
    });
})();
