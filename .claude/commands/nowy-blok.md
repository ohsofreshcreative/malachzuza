Nazwa bloku: $ARGUMENTS

Jeśli powyżej nie podano nazwy bloku — zapytaj o nią, zanim zaczniesz (nazwa musi być jednowyrazowa,
np. Paths — zobacz AGENTS.md → Anatomia bloku ACF; to ta sama forma dla klasy PHP, `$slug`, pliku
blade i klasy CSS `b-<slug>`).

Przeanalizuj załączony screenshot i odwzoruj widoczną na nim sekcję jako nowy blok o podanej wyżej nazwie.
Jeśli nie załączono screena — zapytaj o niego zamiast zgadywać wygląd bloku.

Zanim zaczniesz:

* przeczytaj AGENTS.md,
* przeanalizuj strukturę repozytorium,
* znajdź istniejące bloki najbardziej zbliżone konstrukcją lub sposobem implementacji,
* trzymaj się istniejących wzorców projektu zamiast tworzyć własną architekturę.

Następnie zaimplementuj blok tak, aby możliwie dokładnie odwzorowywał screenshot, również pod względem responsywności.

Utwórz lub zmodyfikuj wszystkie pliki faktycznie potrzebne do poprawnego działania bloku, zgodnie z konwencjami z AGENTS.md (struktura pól ACF, tabsy dla grupy/repeatera, plik SCSS i wpis w app.css itd.).

Nie zmieniaj plików niezwiązanych z tym zadaniem.

Nie uruchamiaj builda ani komend Acorn (yarn build, wp acorn acf:cache) — jeśli będą potrzebne, napisz mi o tym na końcu.

Po implementacji sprawdź swoją pracę i krótko podsumuj, co zrobiłeś oraz jakie kroki muszę wykonać ręcznie.
