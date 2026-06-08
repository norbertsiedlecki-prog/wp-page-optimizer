# SETUP.md - Automatyczna konfiguracja

Jeśli chcesz szybko skonfigurować wtyczkę, użyj poniższych kroków:

## 🚀 Szybka instalacja (5 minut)

### 1. Aktywuj wtyczkę
```
WordPress Admin → Wtyczki → Page Optimizer Pro → Aktywuj
```

### 2. Przejdź do ustawień
```
WordPress Admin → Page Optimizer
```

### 3. Włącz wszystkie opcje wydajności
- ✅ Minifikacja CSS
- ✅ Minifikacja JavaScript  
- ✅ Lazy Loading obrazów
- ✅ Defer JavaScript
- Czas cache: **3600** (1 godzina)

Kliknij: **💾 Zapisz ustawienia**

### 4. Skonfiguruj SEO (3 minuty)
Przejdź do: **Page Optimizer → SEO**

Wklej:
```
Title: Twoja nazwa strony - WordPress
Description: Szybka i zoptymalizowana strona na WordPressie
Keywords: wordpress, optymalizacja, wydajność
```

- ✅ Włącz Schema.org
- ✅ Włącz Sitemap XML

Kliknij: **💾 Zapisz ustawienia SEO**

### 5. (Opcjonalnie) Dodaj AI
Przejdź do: **Page Optimizer → AI Integracja**

Wybierz dostawcę i wklej API Key:
- **OpenAI**: https://platform.openai.com/api-keys
- **Claude**: https://console.anthropic.com/
- **Gemini**: https://makersuite.google.com/app/apikey

Kliknij: **💾 Zapisz ustawienia AI**

### 6. Uruchom naprawy
Przejdź do: **Page Optimizer → Naprawa Strony**

Kliknij po kolei:
1. 🗑️ **Wyczyść BD**
2. ⚡ **Wyczyść Cache**
3. 🧹 **Usuń Sieroty**

**Gotowe!** ✅ Wtyczka jest pełnie skonfigurowana.

---

## 📊 Sprawdzenie czy działa

Otwórz stronę główną i sprawdź:

```bash
# Sprawdź meta tags
curl -s https://twoja-strona.pl | grep -E "meta name=\"(description|keywords)\""

# Sprawdź cache headers
curl -I https://twoja-strona.pl | grep "Cache-Control"

# Sprawdź sitemap
curl -s https://twoja-strona.pl/sitemap.xml | head -5
```

Powinno wyświetlić meta tagi, cache headers i sitemap XML.

---

## 🔧 Reset konfiguracji

Jeśli coś pójdzie nie tak, możesz zresetować:

```bash
# W konsoli MySQL:
DELETE FROM wp_options WHERE option_name LIKE 'wpo_%';
```

Następnie przejdź do wtyczki i ponownie ją aktywuj.
