# CHECKLIST.md - Lista kontrolna

## ✅ Pełna lista konfiguracji WP Page Optimizer Pro

### FAZA 1: INSTALACJA
- [ ] Pobierz wtyczkę z GitHub
- [ ] Rozpakuj do `wp-content/plugins/`
- [ ] Aktywuj w panelu WordPress
- [ ] Sprawdź czy menu "Page Optimizer" pojawił się w panelu

### FAZA 2: WYDAJNOŚĆ
Przejdź do: **Page Optimizer → Dashboard**

**Wydajność:**
- [ ] Zaznacz: **Minifikacja CSS**
- [ ] Zaznacz: **Minifikacja JavaScript**
- [ ] Zaznacz: **Lazy Loading obrazów**
- [ ] Zaznacz: **Defer JavaScript**
- [ ] Ustaw Cache: **3600** sekund (1 godzina)
- [ ] Kliknij: **💾 Zapisz ustawienia**
- [ ] Sprawdź powiadomienie: ✅ "Ustawienia zapisane pomyślnie!"

### FAZA 3: SEO
Przejdź do: **Page Optimizer → SEO**

**Meta Tagi:**
- [ ] **Title Tag** (50-60 znaków): _______________________
- [ ] **Meta Description** (150-160 znaków): _______________________
- [ ] **Słowa kluczowe** (przecinkami): _______________________

**Dodatkowe:**
- [ ] Zaznacz: **Schema.org (strukturalne dane)**
- [ ] Zaznacz: **Generuj Sitemap XML**
- [ ] Kliknij: **💾 Zapisz ustawienia SEO**
- [ ] Sprawdź: Sitemap dostępny na `/sitemap.xml`

### FAZA 4: AI INTEGRACJA (OPCJONALNIE)
Przejdź do: **Page Optimizer → AI Integracja**

**Wybór dostawcy:**
- [ ] Wybieram: ☐ OpenAI ☐ Claude ☐ Gemini ☐ Cohere

**Konfiguracja:**
- [ ] Ustawiłem dostawcę AI
- [ ] Wklejłem API Key
- [ ] (Opcjonalnie) Zaznaczam: **Auto-generowanie treści**
- [ ] (Opcjonalnie) Zaznaczam: **Optymalizacja SEO**
- [ ] Kliknąłem: **💾 Zapisz ustawienia AI**

**Linki do API Keys:**
- [ ] OpenAI: https://platform.openai.com/api-keys
- [ ] Claude: https://console.anthropic.com/
- [ ] Gemini: https://makersuite.google.com/app/apikey
- [ ] Cohere: https://dashboard.cohere.ai/

### FAZA 5: NAPRAWA STRONY
Przejdź do: **Page Optimizer → Naprawa Strony**

**Diagnostyka:**
- [ ] Sprawdzam: **Rozmiar BD** (powinno być < 100 MB)
- [ ] Sprawdzam: **Błędy debugowania** (powinno być < 10)
- [ ] Sprawdzam: **Wersja WordPress** (powinna być aktualna)
- [ ] Sprawdzam: **Wersja PHP** (powinna być >= 7.4)

**Naprawy:**
- [ ] Kliknąłem: **🗑️ Wyczyść BD**
- [ ] Czekam na komunikat: ✅ "Baza danych wyczyszczona!"
- [ ] Kliknąłem: **⚡ Wyczyść Cache**
- [ ] Czekam na komunikat: ✅ "Cache wyczyszczony!"
- [ ] Kliknąłem: **🧹 Usuń Sieroty**
- [ ] Czekam na komunikat: ✅ "Usunięto X sierocych wpisów!"

### FAZA 6: WERYFIKACJA
Przejdź do strony głównej i sprawdź:

**Frontend:**
- [ ] Otwórz stronę główną
- [ ] Otwórz DevTools (F12)
- [ ] Przejdź na zakładkę **Network**
- [ ] Odśwież stronę (F5)
- [ ] Sprawdzam czy:
  - [ ] CSS jest zminifikowany (mniej białych znaków)
  - [ ] Obrazy mają `loading="lazy"`
  - [ ] Jest meta tag description
  - [ ] Jest meta tag keywords

**Sitemap:**
- [ ] Otwórz: `https://twoja-strona.pl/sitemap.xml`
- [ ] Sprawdzam czy widnieją linki do postów/stron

**Cache Headers:**
```bash
curl -I https://twoja-strona.pl | grep "Cache-Control"
```
- [ ] Powinna być linia: `Cache-Control: public, max-age=3600`

**Schema.org:**
- [ ] DevTools → Source
- [ ] Szukam: `<script type="application/ld+json">`
- [ ] Sprawdzam czy zawiera dane strony

### FAZA 7: TESTY WYDAJNOŚCI

Przed optymalizacją zanotuj wyniki:
- [ ] **Rozmiar CSS**: _____ KB
- [ ] **Rozmiar JS**: _____ KB
- [ ] **Load Time**: _____ s
- [ ] **Google PageSpeed**: _____ punktów

Po optymalizacji (za 24h) zanotuj:
- [ ] **Rozmiar CSS**: _____ KB (cel: -40%)
- [ ] **Rozmiar JS**: _____ KB (cel: -40%)
- [ ] **Load Time**: _____ s (cel: -30%)
- [ ] **Google PageSpeed**: _____ punktów (cel: +15)

### FAZA 8: BACKUP I BEZPIECZEŃSTWO

- [ ] Robiłem backup bazy danych PRZED wprowadzaniem zmian
- [ ] Sprawdzam czy wrażliwe dane (API Keys) nie są w GIT
- [ ] Włączam 2FA na WordPress
- [ ] Rotowałem hasło administratora
- [ ] Ustawiłem backup cron job

### FAZA 9: MONITORING (CO TYDZIEŃ)

- [ ] Sprawdzam size bazy danych (Panel → Naprawa)
- [ ] Sprawdzam błędy PHP (debug.log)
- [ ] Sprawdzam czasy ładowania
- [ ] Robiłem backup bazy danych

---

## 📋 Status Konfiguracji

**Data rozpoczęcia:** _______________  
**Data ukończenia:** _______________

**Ogólny status:**
- [ ] ❌ Niezakończone
- [ ] 🔄 W trakcie
- [ ] ✅ Ukończone
- [ ] 🚀 Zoptymalizowane

**Notatki:**
```
_________________________________________________
_________________________________________________
_________________________________________________
```

---

**Przygotował:** _____________________  
**Serwer/Hosting:** _____________________  
**Wersja WordPress:** _____________________  
**Wersja PHP:** _____________________
