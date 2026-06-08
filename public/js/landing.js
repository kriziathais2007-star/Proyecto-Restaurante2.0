
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn         = document.getElementById('menuBtn');
    const dashboard       = document.getElementById('dashboard');
    const dashboardClose  = document.getElementById('dashboardClose');
    const dashboardOverlay= document.getElementById('dashboardOverlay');

    const openDashboard = () => {
        dashboard.classList.add('open');
        dashboardOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden'; // evita scroll de fondo
    };

    const closeDashboard = () => {
        dashboard.classList.remove('open');
        dashboardOverlay.classList.remove('visible');
        document.body.style.overflow = '';
    };

    if (menuBtn)          menuBtn.addEventListener('click', openDashboard);
    if (dashboardClose)   dashboardClose.addEventListener('click', closeDashboard);
    if (dashboardOverlay) dashboardOverlay.addEventListener('click', closeDashboard);

    // Cerrar al presionar Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDashboard();
    });

    // Cerrar al hacer clic en un link del dashboard
    document.querySelectorAll('.dashboard-link').forEach(link => {
        link.addEventListener('click', closeDashboard);
    });


    /* ────────────────────────────────────────────────────────
       NAVBAR – sombra al hacer scroll
    ──────────────────────────────────────────────────────── */
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.style.boxShadow = window.scrollY > 10
                ? '0 2px 14px rgba(0,0,0,.12)'
                : '0 1px 6px rgba(0,0,0,.07)';
        }, { passive: true });
    }


    /* ────────────────────────────────────────────────────────
       CARRUSEL DE FUNCIONALIDADES
    ──────────────────────────────────────────────────────── */
    const track    = document.getElementById('carouselTrack');
    const dotsWrap = document.getElementById('carouselDots');
    const btnPrev  = document.getElementById('carouselPrev');
    const btnNext  = document.getElementById('carouselNext');

    if (track && dotsWrap) {
        const cards = Array.from(track.children);

        // ── Cuántas tarjetas se muestran según el viewport ──
        const visibleCount = () => {
            if (window.innerWidth <= 640)  return 1;
            if (window.innerWidth <= 1024) return 2;
            return 3;
        };

        let currentIndex = 0;

        const totalSteps = () => Math.max(0, cards.length - visibleCount());

        // ── Calcular el ancho real de una tarjeta + gap ──────
        const cardStep = () => {
            const card = cards[0];
            const style = window.getComputedStyle(track);
            const gap = parseFloat(style.gap) || 20;
            return card.offsetWidth + gap;
        };

        // ── Construir dots ───────────────────────────────────
        const buildDots = () => {
            dotsWrap.innerHTML = '';
            const steps = totalSteps();
            for (let i = 0; i <= steps; i++) {
                const dot = document.createElement('button');
                dot.classList.add('dot');
                dot.setAttribute('aria-label', `Ir al slide ${i + 1}`);
                if (i === currentIndex) dot.classList.add('active');
                dot.addEventListener('click', () => goTo(i));
                dotsWrap.appendChild(dot);
            }
        };

        // ── Actualizar posición y estados ────────────────────
        const update = () => {
            track.style.transform = `translateX(-${currentIndex * cardStep()}px)`;

            // Sincronizar dots
            dotsWrap.querySelectorAll('.dot').forEach((d, i) =>
                d.classList.toggle('active', i === currentIndex)
            );

            // Habilitar / deshabilitar botones flechas
            if (btnPrev) btnPrev.disabled = currentIndex === 0;
            if (btnNext) btnNext.disabled = currentIndex >= totalSteps();
        };

        // ── Ir a un slide concreto ───────────────────────────
        const goTo = (index) => {
            currentIndex = Math.max(0, Math.min(index, totalSteps()));
            update();
        };

        if (btnPrev) btnPrev.addEventListener('click', () => goTo(currentIndex - 1));
        if (btnNext) btnNext.addEventListener('click', () => goTo(currentIndex + 1));

        // ── Touch / Swipe (móvil) ────────────────────────────
        let touchStartX = 0;

        track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].clientX;
        }, { passive: true });

        track.addEventListener('touchend', (e) => {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 40) {
                goTo(diff > 0 ? currentIndex + 1 : currentIndex - 1);
            }
        });

        // ── Auto-play (pausa en hover / touch) ──────────────
        let autoTimer;

        const startAuto = () => {
            autoTimer = setInterval(() => {
                goTo(currentIndex >= totalSteps() ? 0 : currentIndex + 1);
            }, 5000);
        };

        const stopAuto = () => clearInterval(autoTimer);

        const wrapper = document.querySelector('.carousel-wrapper');
        if (wrapper) {
            wrapper.addEventListener('mouseenter', stopAuto);
            wrapper.addEventListener('mouseleave', startAuto);
            track.addEventListener('touchstart', stopAuto, { passive: true });
            track.addEventListener('touchend',   () => setTimeout(startAuto, 3000));
        }

        
        buildDots();
        update();
        startAuto();

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                stopAuto();
                currentIndex = Math.min(currentIndex, totalSteps());
                buildDots();
                update();
                startAuto();
            }, 150);
        });
    }

    const playBtn = document.getElementById('playBtn');
    if (playBtn) {
        playBtn.addEventListener('click', () => {
            // Reemplazar con apertura de modal o redirección real
            alert('¡Abre el demo interactivo!');
        });
    }

    const fadeOverlay = document.getElementById('fadeOverlay');
    if (fadeOverlay) {
        document.querySelectorAll('a[href]:not([href="#"])').forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#')) return;
                e.preventDefault();
                fadeOverlay.classList.add('active');
                setTimeout(() => { window.location.href = href; }, 400);
            });
        });
    }

});