# 📋 KONFIGURACJA WTYCZKI - Page Optimizer Pro v2.1

## 🚀 PORADNIK INSTALACJI I KONFIGURACJI

### ETAP 1: INSTALACJA WTYCZKI

#### Opcja A: Przez FTP/File Manager
1. Pobierz repozytorium: `https://github.com/norbertsiedlecki-prog/wp-page-optimizer`
2. Rozpakuj folder do: `wp-content/plugins/wp-page-optimizer`
3. Przejdź do panelu WordPress → **Wtyczki**
4. Znajdź "Page Optimizer Pro" i kliknij **Aktywuj**

#### Opcja B: Przez Git
```bash
cd wp-content/plugins/
git clone https://github.com/norbertsiedlecki-prog/wp-page-optimizer.git
```

---

## ⚙️ ETAP 2: KONFIGURACJA WYDAJNOŚCI

Po aktywacji wtyczki przejdź do: **Panel Admin → Page Optimizer**

### 📦 Ustawienia podstawowe:

| Opcja | Rekomendacja | Opis |
|-------|-------------|------|
| **Minifikacja CSS** | ✅ WŁĄCZONA | Zmniejsza rozmiar CSS o ~40% |
| **Minifikacja JS** | ✅ WŁĄCZONA | Zmniejsza rozmiar JavaScript |
| **Lazy Loading** | ✅ WŁĄCZONA | Ładuje obrazy na żądanie |
| **Defer JS** | ✅ WŁĄCZONA | JS ładuje się po wyrenderowaniu strony |
| **Cache Time** | 3600 sekund | 1 godzina (można zwiększyć do 86400) |

**Jak aktywować:**
1. Zaznacz checkboxy
2. Ustaw czas cache (domyślnie 3600 = 1h)
3. Kliknij **💾 Zapisz ustawienia**

---

## 🔍 ETAP 3: KONFIGURACJA SEO

### Przejdź do: **Page Optimizer → SEO**

#### Wypełnij następujące pola:

| Pole | Przykład | Wskazówki |
|------|---------|----------|
| **Title Tag** | "Optymalizacja Stron - WordPress" | 50-60 znaków |
| **Meta Description** | "Zwiększ wydajność WordPress o 40%. AI, SEO, naprawa bazy danych." | 150-160 znaków |
| **Słowa kluczowe** | "wordpress, seo, optymalizacja, wydajność" | Oddzielone przecinkami |
| **Schema.org** | ✅ WŁĄCZONE | Dane strukturalne dla Google |
| **Sitemap XML** | ✅ WŁĄCZONE | Dostępny na `/sitemap.xml` |

**Po wpisaniu kliknij:** 💾 **Zapisz ustawienia SEO**

---

## 🤖 ETAP 4: KONFIGURACJA AI (OPCJONALNIE)

### Przejdź do: **Page Optimizer → AI Integracja**

Wybierz jednego dostawcę AI i skonfiguruj:

### 1️⃣ **OpenAI (ChatGPT) - REKOMENDOWANY**

1. Przejdź do: https://platform.openai.com/api-keys
2. Zaloguj się / Załóż konto
3. Kliknij **Create new secret key**
4. Skopiuj klucz (np: `sk-proj-xxxxx...`)
5. Wróć do WordPress → **Page Optimizer → AI Integracja**
6. Wybierz: **OpenAI (ChatGPT)**
7. Wklej API Key
8. Zaznacz (opcjonalnie):
   - ✅ **Auto-generowanie treści** - AI pisze posty
   - ✅ **Optymalizacja SEO** - AI optymalizuje treści
9. Kliknij **💾 Zapisz ustawienia AI**

### 2️⃣ **Claude (Anthropic)**

1. Przejdź do: https://console.anthropic.com/
2. Zaloguj się / Załóż konto
3. Kliknij **API Keys**
4. Utwórz nowy klucz
5. Skopiuj i wklej w WordPress
6. Kliknij **Zapisz**

### 3️⃣ **Google Gemini**

1. Przejdź do: https://makersuite.google.com/app/apikey
2. Zaloguj się na Google
3. Kliknij **Create API Key**
4. Skopiuj i wklej w WordPress
5. Kliknij **Zapisz**

### 4️⃣ **Cohere**

1. Przejdź do: https://dashboard.cohere.ai/
2. Zaloguj się / Załóż konto
3. Kliknij **API Keys**
4. Utwórz nowy klucz
5. Skopiuj i wklej w WordPress
6. Kliknij **Zapisz**

---

## 🔧 ETAP 5: NAPRAWA STRONY (DIAGNOSTYKA)

### Przejdź do: **Page Optimizer → Naprawa Strony**

#### Widok diagnostyki pokazuje:
- 📊 **Rozmiar BD** - Jeśli > 100 MB, warto wyczyścić
- 🚨 **Błędy debugowania** - Jeśli > 10, sprawdź debug.log
- 📝 **Wersja WordPress** - Powinna być aktualna
- ⚙️ **Wersja PHP** - Powinna być >= 7.4

#### Dostępne naprawy:

| Przycisk | Opis | Kiedy używać |
|---------|------|-------------|
| **🗑️ Wyczyść BD** | Usuwa duplikaty i sierote metadane | Co miesiąc |
| **⚡ Wyczyść Cache** | Czyści object cache i transients | Po dużych zmianach |
| **🔐 Napraw Uprawnienia** | Ustawia uprawnienia folderów (755/644) | Po problemach z dostępem |
| **🧹 Usuń Sieroty** | Usuwa metadane bez powiązanego postu | Co miesiąc |

**Jak uruchomić:** Kliknij przycisk naprawy (pojawi się komunikat o sukcesie ✅)

---

## 📊 ETAP 6: WERYFIKACJA KONFIGURACJI

### Sprawdź czy wszystko działa:

#### 1. **Frontend (Strona główna)**
```bash
# Sprawdź czy meta tagi są obecne
curl https://twoja-strona.pl | grep "meta name=\"description\""
```

#### 2. **Sitemap**
Odwiedź: `https://twoja-strona.pl/sitemap.xml`
(Powinna załadować się lista postów)

#### 3. **Cache Headers**
```bash
curl -I https://twoja-strona.pl | grep "Cache-Control"
# Powinno wyświetlić: Cache-Control: public, max-age=3600
```

#### 4. **AI Integration** (jeśli skonfigurowany)
Utwórz nowy post w WordPress - AI powinien go zoptymalizować

---

## 📈 PERFORMANCE GAINS (SPODZIEWANE)

Po prawidłowej konfiguracji powinieneś zaobserwować:

| Metrika | Przed | Po | Poprawa |
|---------|-------|----|---------| 
| **Rozmiar CSS** | 250 KB | 150 KB | ↓ 40% |
| **Rozmiar JS** | 400 KB | 240 KB | ↓ 40% |
| **Load Time** | 3.5s | 2.1s | ↓ 40% |
| **Google PageSpeed** | 55 | 78 | ↑ 23 pkt |

---

## ⚠️ TROUBLESHOOTING

### Problem: "Ustawienia nie zapisują się"
**Rozwiązanie:** Sprawdź czy masz uprawnienia administratora (manage_options)

### Problem: "AI nie działa"
**Rozwiązanie:** 
1. Sprawdź czy API Key jest poprawny
2. Sprawdź czy masz wystarczające kredyty w koncie AI
3. Sprawdź czy serwer ma dostęp do internetu

### Problem: "Sitemap nie generuje się"
**Rozwiązanie:**
1. Włącz "Generuj Sitemap" w SEO
2. Sprawdź czy masz co najmniej 1 opublikowany post
3. Wyczyść cache

### Problem: "Strona wolna pomimo konfiguracji"
**Rozwiązanie:**
1. Uruchom "Wyczyść BD"
2. Uruchom "Wyczyść Cache"
3. Sprawdź czy tema lub inne wtyczki nie blokują optymalizacji

---

## 🔐 BEZPIECZEŃSTWO

- ✅ **API Keys** - Przechowywane w bazie danych (w opcjach WordPress)
- ✅ **NONCE Protection** - Każdy formularz ma CSRF token
- ✅ **Input Sanitization** - Wszystkie dane wejściowe są oczyszczane
- ✅ **SQL Prepared Statements** - Zapytania chronione przed SQL injection

### Rekomendacje:
1. Używaj **stałych API Keys** (rotation co 3 miesiące)
2. Ogranicze dostęp do panelu administratora (2FA)
3. Rób **backupy bazy danych** przed naprawami
4. **Testuj na staging** przed uruchomieniem na produkcji

---

## 📞 WSPARCIE

Masz problemy? Otwórz issue:
https://github.com/norbertsiedlecki-prog/wp-page-optimizer/issues

---

**Wersja:** 2.1.0  
**Autor:** Norbert Siedlecki  
**Ostatnia aktualizacja:** 2026-06-08
