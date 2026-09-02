---
name: nowy-blok
description: Tworzy nowy blok ACF Composer w motywie Bergermann (Sage 11) na podstawie załączonego screena lub projektu. Używaj, gdy użytkownik wywołuje `$nowy-blok`, prosi o nowy blok ACF albo chce odwzorować sekcję projektu jako blok WordPressa w tym repozytorium.
---

# Nowy blok ACF

## Dane wejściowe

Ustal nazwę bloku z treści polecenia wywołującego skill, np. `$nowy-blok Paths`.

- Jeśli nazwy nie podano, zapytaj o nią przed rozpoczęciem. Musi być jednowyrazowa i zgodna z regułami w `AGENTS.md`.
- Jeśli nie załączono screena ani innego projektu sekcji, poproś o niego zamiast zgadywać wygląd.
- Traktuj załączony projekt jako specyfikację wyglądu i responsywności.

## Workflow

1. Przeczytaj obowiązujący w repozytorium `AGENTS.md` w całości.
2. Sprawdź strukturę projektu i znajdź istniejące bloki najbardziej zbliżone układem, polami lub zachowaniem.
3. Sprawdź dostępne komponenty, tokeny, kontenery, klasy sekcji, obrazy, ikony i wzorce JavaScript.
4. Zaplanuj najmniejszy zestaw plików potrzebny do kompletnej implementacji.
5. Zaimplementuj blok zgodnie z projektem i konwencjami repozytorium.
6. Przejrzyj całość pod kątem responsywności, dostępności, poprawnego escapowania oraz spójności z design systemem.
7. Wykonaj bezpieczne kontrole składni i formatowania, ale nie uruchamiaj buildu ani komend Acorn.

## Implementacja

- Utwórz klasę bloku PHP, widok Blade i plik SCSS zgodnie z anatomią bloku opisaną w `AGENTS.md`.
- Dodaj import SCSS w odpowiedniej sekcji `resources/css/app.css`.
- Dodaj plik JavaScript tylko wtedy, gdy zachowanie tego wymaga; w takim przypadku podepnij go warunkowo w `resources/js/app.js`.
- Użyj istniejących komponentów, w szczególności `x-button` i `x-picture`, gdy odpowiadają projektowi.
- Używaj istniejących tokenów, klas kontenerów, typografii, kolorów i odstępów sekcji.
- Dodawaj wyłącznie pola ACF potrzebne redaktorowi do zarządzania treścią.
- Nie wprowadzaj nowej architektury, gdy repozytorium ma już właściwy wzorzec.
- Nie zmieniaj plików niezwiązanych z blokiem.

## Zakończenie

Nie uruchamiaj `yarn build`, `yarn dev`, `wp acorn acf:cache` ani `wp acorn view:clear`.

W podsumowaniu:

- wymień utworzone i zmienione pliki,
- opisz wykonane kontrole,
- poinformuj o konieczności uruchomienia `wp acorn acf:cache`,
- jeśli zmieniono CSS lub JavaScript, poinformuj o konieczności uruchomienia `yarn build` i uwzględnienia `public/build` w commicie.
