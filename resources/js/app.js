/*--- GŁÓWNE IMPORTY ---*/
// Importujemy tylko Alpine, resztę bibliotek (GSAP) ładujemy globalnie

import Alpine from 'alpinejs';

import baguetteBox from 'baguettebox.js';

// Importy zasobów dla Vite (np. obrazy, fonty)
import.meta.glob(['../images/**', '../fonts/**']);

// Twoje niestandardowe moduły JS
import './menubar.js';
import './footer-accordion.js';
import './cart-drawer.js';

/*--- USED ---*/

document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('.b-reviews')) import('./blocks/reviews');
  if (document.querySelector('.b-tabs')) import('./blocks/tabs');
  if (document.querySelector('.b-slider')) import('./blocks/slider');
  if (document.querySelector('.b-steps')) import('./blocks/steps');
  if (document.querySelector('.b-catalogues')) import('./blocks/catalogues');
  if (document.querySelector('[data-ral-picker]')) import('./ral-search');
});

/*--- NOT USED ---*/

/*--- INICJALIZACJA BIBLIOTEK ---*/
// Uruchom Alpine.js
window.Alpine = Alpine;
Alpine.start();

/*--- SKRYPTY URUCHAMIANE PO ZAŁADOWANIU STRONY ---*/

document.addEventListener('DOMContentLoaded', function () {
  // Inicjalizacja baguetteBox.js dla galerii
  if (document.querySelector('.lightbox-gallery')) {
    baguetteBox.run('.lightbox-gallery');
  }

  // Sprawdzenie, czy globalny GSAP istnieje. Jeśli nie, nic nie robimy, aby uniknąć błędów.
  if (typeof gsap === 'undefined') {
    console.error(
      'GSAP nie został załadowany globalnie. Sprawdź plik app/setup.php lub functions.php'
    );
    return;
  }

  // --- TWOJE ISTNIEJĄCE ANIMACJE GSAP (TERAZ POWINNY DZIAŁAĆ) ---
  gsap.utils.toArray("[data-gsap-anim='section']").forEach((section) => {
    const standardImages = section.querySelectorAll(
      "[data-gsap-element='img']"
    );
    standardImages.forEach((img) => {
      gsap.from(img, {
        opacity: 0,
        y: 50,
        filter: 'blur(15px)',
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: img,
          start: 'top 90%',
          toggleActions: 'play none none none',
          once: true,
        },
      });
    });

    const otherElements = section.querySelectorAll(
      "[data-gsap-element]:not([data-gsap-element*='img']):not([data-gsap-element='stagger'])"
    );
    otherElements.forEach((element, index) => {
      gsap.from(element, {
        opacity: 0,
        y: 50,
        filter: 'blur(15px)',
        duration: 1,
        ease: 'power2.out',
        delay: index * 0.1,
        scrollTrigger: {
          trigger: element,
          start: 'top 90%',
          toggleActions: 'play none none none',
          once: true,
        },
      });
    });

    const staggerElements = section.querySelectorAll(
      "[data-gsap-element='stagger']"
    );
    if (staggerElements.length > 0) {
      const sorted = [...staggerElements].sort((a, b) => {
        const getDelay = (el) => {
          const attr = el.getAttribute('data-gsap-edit');
          return attr && attr.startsWith('delay-')
            ? parseFloat(attr.replace('delay-', '')) || 0
            : 0;
        };
        return getDelay(a) - getDelay(b);
      });

      gsap.set(sorted, { opacity: 0, y: 50 });

      gsap.to(sorted, {
        opacity: 1,
        y: 0,
        filter: 'blur(0px)',
        duration: 1,
        ease: 'power2.out',
        stagger: { amount: 1.5, each: 0.1 },
        scrollTrigger: {
          trigger: section,
          start: 'top 80%',
          toggleActions: 'play none none none',
          once: true,
        },
      });
    }
  });
});

/*--- LINE ----*/

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', function () {
  const line = document.querySelector('.animated-line');
  if (!line) return;

  const length = line.getTotalLength();

  gsap.set(line, {
    strokeDasharray: length,
    strokeDashoffset: length,
  });

  gsap.to(line, {
    strokeDashoffset: 0,
    duration: 0.5,
    ease: 'power1.inOut',

    scrollTrigger: {
      trigger: line,
      start: 'top 80%',
      end: 'bottom 20%',
      toggleActions: 'play none none none',
      // markers: true,
    },
  });
});


document.addEventListener('DOMContentLoaded', () => {
  // Znajdź wszystkie kontenery megamenu
  const megamenuContents = document.querySelectorAll('.megamenu-content');

  megamenuContents.forEach(megamenu => {
    const level2Items = megamenu.querySelectorAll('.level-2-item');
    const level3Lists = megamenu.querySelectorAll('.level-3-list');
    const imageContainer = megamenu.querySelector('.active-level-2-image');

    level2Items.forEach(item => {
      item.addEventListener('mouseenter', () => {
        // Usuń klasę 'active' ze wszystkich elementów i list w obrębie TEGO megamenu
        level2Items.forEach(i => i.classList.remove('active'));
        level3Lists.forEach(l => l.classList.remove('active'));

        // Dodaj 'active' do najechanego elementu
        item.classList.add('active');

        // Znajdź i pokaż odpowiednią listę poziomu 3
        const parentId = item.id;
        const targetList = megamenu.querySelector(`.level-3-list[data-parent-id="${parentId}"]`);
        if (targetList) {
          targetList.classList.add('active');
        }

        // Zaktualizuj obrazek
        const imageUrl = item.dataset.imageSrc;
        if (imageUrl && imageContainer) {
          // Sprawdź, czy obrazek już istnieje, aby uniknąć przeładowywania
          let img = imageContainer.querySelector('img');
          if (!img) {
            img = document.createElement('img');
            imageContainer.appendChild(img);
          }
          img.src = imageUrl;
          img.alt = ''; // Dodaj pusty alt dla dostępności
          img.className = 'menu-image'; // Upewnij się, że obrazek ma odpowiednie style
        } else if (imageContainer) {
          imageContainer.innerHTML = ''; // Wyczyść, jeśli nie ma obrazka
        }
      });
    });

    // Ustaw domyślny stan przy pierwszym załadowaniu
    const firstItem = megamenu.querySelector('.level-2-item:first-child');
    if (firstItem) {
      // Używamy setTimeout, aby upewnić się, że wszystko jest gotowe
      setTimeout(() => {
        firstItem.dispatchEvent(new MouseEvent('mouseenter', {
          'view': window,
          'bubbles': true,
          'cancelable': true
        }));
      }, 100);
    }
  });
});

