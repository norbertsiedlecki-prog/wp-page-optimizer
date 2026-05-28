# 🚀 Page Optimizer Pro

Wtyczka WordPress optymalizująca wydajność strony poprzez minifikację, lazy loading i cache.

## ✨ Funkcje

- **📦 Minifikacja CSS** - Zmniejsza rozmiar plików CSS usuwając zbędne znaki
- **📦 Minifikacja JavaScript** - Zmniejsza rozmiar plików JS
- **⏳ Defer JavaScript** - Ładuje JS dopiero po wyrenderowaniu strony
- **🖼️ Lazy Loading** - Ładuje obrazy dopiero gdy użytkownik ich widzi
- **💾 Browser Cache** - Ustawia nagłówki cache dla szybszego ładowania
- **🔒 Nagłówki bezpieczeństwa** - Dodaje nagłówki X-Frame-Options i X-Content-Type-Options

## 📥 Instalacja

### Metoda 1: FTP/File Manager

1. Pobierz plik `wp-page-optimizer.zip`
2. Rozpakuj go w folderze `wp-content/plugins/`
3. W WordPress Admin → **Wtyczki** → Znajdź **Page Optimizer Pro**
4. Kliknij **Aktywuj**

### Metoda 2: Bezpośrednio z GitHub

```bash
cd wp-content/plugins/
git clone https://github.com/norbertsiedlecki-prog/wp-page-optimizer.git
```

## ⚙️ Konfiguracja

1. W WordPress Admin przejdź do **Page Optimizer**
2. Zaznacz opcje optymalizacji które chcesz włączyć
3. Ustaw czas cache (domyślnie 3600 sekund = 1 godzina)
4. Kliknij **Zapisz ustawienia**

## 📊 Metryki wydajności

Wtyczka automatycznie:
- Zmniejsza rozmiar CSS i JS (do 40% oszczędności)
- Ładuje obrazy na żądanie (redukcja transferu)
- Opóźnia ładowanie JS (szybsze TTFB)
- Przechowuje pliki w cache (szybkie ładowanie powtórne)

## 🔧 Wymagania

- WordPress 5.0+
- PHP 7.4+
- Dostęp do administracji WordPress

## 📝 Licencja

GPL v2 lub nowsza

## 👤 Autor

Norbert Siedlecki

## 🐛 Wsparcie

Jeśli masz problemy, otwórz issue na GitHub:
https://github.com/norbertsiedlecki-prog/wp-page-optimizer/issues
