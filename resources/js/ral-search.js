// Combobox koloru RAL na stronie produktu - podmienia ukryty <select> (kolor-ral) na przewijaną,
// filtrowaną podczas pisania listę z kolorową kropką przy każdej opcji. Wybór synchronizuje się
// z prawdziwym <select>, więc reszta formularza wariantów WooCommerce działa bez zmian.
//
// Ten moduł jest importowany dynamicznie (import('./ral-search')) z wnętrza handlera
// DOMContentLoaded w app.js - w momencie, gdy DOM jest już gotowy, więc NIE wolno tu drugi raz
// czekać na 'DOMContentLoaded' (to zdarzenie jednorazowe - już minęło, listener nigdy by się nie
// odpalił i cały widget by się nie budował - dokładnie ten błąd siedział tu wcześniej).
{
  document.querySelectorAll('[data-ral-picker]').forEach((field) => {
    const select = field.querySelector('select');

    if (!select) return;

    const options = Array.from(select.options).filter((option) => option.value !== '');

    if (!options.length) return;

    const wrapper = document.createElement('div');
    wrapper.className = 'ral-combobox relative';

    // Kropka z wybranym kolorem, widoczna wewnątrz pola (po lewej) dopiero po wyborze koloru.
    const activeSwatch = document.createElement('span');
    activeSwatch.hidden = true;
    activeSwatch.className = 'pointer-events-none absolute left-3.5 top-1/2 h-7 w-7 -translate-y-1/2 rounded-full border-2 border-white shadow ring-1 ring-slate-300';

    const input = document.createElement('input');
    input.type = 'text';
    input.setAttribute('role', 'combobox');
    input.setAttribute('aria-expanded', 'false');
    input.setAttribute('aria-autocomplete', 'list');
    input.setAttribute('autocomplete', 'off');
    input.placeholder = 'Wybierz lub wpisz kod RAL, np. 3000';
    input.className = 'w-full rounded-lg border-2 border-slate-300 bg-white py-3.5 pl-4 pr-10 text-base font-medium text-slate-900 shadow-sm transition-colors outline-none focus:border-primary focus:ring-2 focus:ring-primary/20';

    // Strzałka sygnalizująca, że to rozwijana lista, a nie zwykłe pole tekstowe.
    const chevron = document.createElement('span');
    chevron.className = 'pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400';
    chevron.innerHTML = '<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    const list = document.createElement('ul');
    list.hidden = true;
    list.setAttribute('role', 'listbox');
    list.className = 'ral-combobox-list absolute z-20 mt-1 max-h-72 w-full overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-lg';

    const empty = document.createElement('li');
    empty.hidden = true;
    empty.className = 'px-3 py-2 text-sm text-slate-500';
    empty.textContent = 'Nie znaleziono koloru RAL o podanym kodzie.';

    const setActiveSwatchColor = (color) => {
      if (color) {
        activeSwatch.style.backgroundColor = color;
        activeSwatch.hidden = false;
        input.classList.add('pl-11');
        input.classList.remove('pl-4');
      } else {
        activeSwatch.hidden = true;
        input.classList.add('pl-4');
        input.classList.remove('pl-11');
      }
    };

    const rows = options.map((option) => {
      const li = document.createElement('li');
      li.setAttribute('role', 'option');
      li.className = 'ral-combobox-option flex items-center gap-3 px-3 py-2.5 text-sm cursor-pointer hover:bg-slate-50';
      li.dataset.value = option.value;

      const swatch = document.createElement('span');
      swatch.className = 'h-6 w-6 rounded-full border border-slate-200 shrink-0';
      swatch.style.backgroundColor = option.dataset.color || '#CCCCCC';

      const label = document.createElement('span');
      label.textContent = option.textContent;

      li.append(swatch, label);
      list.appendChild(li);

      return { option, li, label: option.textContent };
    });

    list.appendChild(empty);
    wrapper.append(activeSwatch, input, chevron, list);
    select.insertAdjacentElement('afterend', wrapper);

    let activeIndex = -1;

    const visibleRows = () => rows.filter(({ li }) => !li.hidden);

    const setActive = (index) => {
      rows.forEach(({ li }) => li.classList.remove('bg-slate-100'));
      const visible = visibleRows();
      activeIndex = index;

      const target = visible[activeIndex];

      if (target) {
        target.li.classList.add('bg-slate-100');
        target.li.scrollIntoView({ block: 'nearest' });
      }
    };

    const openList = () => {
      list.hidden = false;
      input.setAttribute('aria-expanded', 'true');
    };

    const closeList = () => {
      list.hidden = true;
      input.setAttribute('aria-expanded', 'false');
      activeIndex = -1;
    };

    const selectRow = ({ option, label }) => {
      select.value = option.value;
      select.dispatchEvent(new Event('change', { bubbles: true }));
      input.value = label;
      setActiveSwatchColor(option.dataset.color);
      closeList();
    };

    // Otwarcie pola (fokus / klik) zawsze pokazuje WSZYSTKIE kolory - niezależnie od tego, co
    // wcześniej wpisano lub wybrano - z aktualnie wybranym kolorem podświetlonym w liście. Filtrowanie
    // po tekście (filterByQuery) uruchamia się dopiero, gdy ktoś faktycznie zacznie pisać.
    const showAll = () => {
      rows.forEach(({ li }) => {
        li.hidden = false;
      });
      empty.hidden = true;
      // Lista musi być już otwarta (display != none), zanim wywołamy scrollIntoView w setActive() -
      // przewijanie elementu w ukrytym kontenerze nic nie robi, więc kolejność tu ma znaczenie.
      openList();
      setActive(rows.findIndex(({ option }) => option.value === select.value));
    };

    const filterByQuery = () => {
      const query = input.value.trim().toLowerCase();
      let anyVisible = false;

      rows.forEach(({ li, label }) => {
        const matches = !query || label.toLowerCase().includes(query);
        li.hidden = !matches;
        if (matches) anyVisible = true;
      });

      empty.hidden = anyVisible;
      setActive(-1);
      openList();
    };

    const openWithAllOnFocus = () => {
      showAll();
      // Zaznacza cały tekst, żeby pisanie od razu nadpisywało poprzednio wybraną wartość zamiast
      // dopisywać się do niej (np. "RAL 3007" + wpisanie "5" -> "5", a nie "RAL 30075").
      input.select();
    };

    input.addEventListener('focus', openWithAllOnFocus);

    // Po wyborze koloru pole zostaje w fokusie (celowo - mousedown+preventDefault na wierszu listy,
    // żeby klik na opcję nie gubił fokusu przed selectRow()). Przez to kolejne kliknięcie w JUŻ
    // zafokusowane pole nie odpala 'focus' (tylko stawia kursor w tekście) - bez tego trzeba by
    // kliknąć gdzie indziej i wrócić, żeby ponownie rozwinąć listę. 'click' domyka tę lukę.
    input.addEventListener('click', () => {
      if (list.hidden) {
        openWithAllOnFocus();
      }
    });

    input.addEventListener('input', filterByQuery);

    // Jeśli ktoś wpisał coś, co nie pasuje do żadnej opcji, i kliknął gdzie indziej bez wybrania
    // koloru - pole wraca do faktycznie wybranej wartości (albo pustego), żeby nie zostawiać
    // tekstu, który nie odpowiada żadnemu realnemu wyborowi.
    input.addEventListener('blur', () => {
      window.setTimeout(() => {
        if (wrapper.contains(document.activeElement)) return;

        const current = options.find((option) => option.value === select.value);
        input.value = current ? current.textContent : '';
      }, 0);
    });

    input.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowDown') {
        event.preventDefault();

        if (list.hidden) {
          showAll();
          return;
        }

        setActive(Math.min(activeIndex + 1, visibleRows().length - 1));
      } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        setActive(Math.max(activeIndex - 1, 0));
      } else if (event.key === 'Enter') {
        event.preventDefault();
        const visible = visibleRows();
        const target = visible[activeIndex] || visible[0];
        if (target) selectRow(target);
      } else if (event.key === 'Escape') {
        closeList();
      }
    });

    rows.forEach((row) => {
      // mousedown (nie click) + preventDefault, żeby input nie stracił fokusu (blur) zanim zdąży
      // zadziałać wybór wiersza - klasyczny problem list rozwijanych budowanych nad <input>.
      row.li.addEventListener('mousedown', (event) => {
        event.preventDefault();
        selectRow(row);
      });
    });

    document.addEventListener('click', (event) => {
      if (!wrapper.contains(event.target)) {
        closeList();
      }
    });

    const selected = options.find((option) => option.selected);
    if (selected) {
      input.value = selected.textContent;
      setActiveSwatchColor(selected.dataset.color);
    }

    // Synchronizacja np. po kliknięciu linku "Wyczyść" (reset_variations), który czyści <select> z zewnątrz.
    select.addEventListener('change', () => {
      const current = options.find((option) => option.value === select.value);
      input.value = current ? current.textContent : '';
      setActiveSwatchColor(current ? current.dataset.color : null);
    });
  });
}
