# PANDUAN WHITEBOX TESTING - PKL DPMPTSP

## 1. APA ITU WHITEBOX TESTING?

Whitebox testing (juga dikenal sebagai **structural testing** atau **glass-box testing**) adalah testing yang **melihat internal logic dari code**, bukan hanya input/output.

### Perbedaan dengan Blackbox Testing:
| Whitebox Testing | Blackbox Testing |
|---|---|
| Melihat internal logic | Hanya melihat input/output |
| Tester memahami kode | Tester tidak perlu tahu kode |
| Testing: decision path, branch, logic | Testing: fungsionalitas |
| Contoh: unit test | Contoh: integration test |

---

## 2. SETUP WHITEBOX TESTING DI LARAVEL

### A. Check PHPUnit Configuration
```bash
cat phpunit.xml
```

File ini sudah ada di project dengan konfigurasi:
- Test suites: `Unit` dan `Feature`
- Bootstrap: `vendor/autoload.php`
- Coverage directory: `./app`

### B. Setup Database untuk Testing
Di `phpunit.xml`, gunakan SQLite in-memory database (tidak perlu DB real):

```xml
<server name="DB_CONNECTION" value="sqlite"/>
<server name="DB_DATABASE" value=":memory:"/>
```

Atau edit langsung file Anda.

### C. Install Dependencies (jika belum)
```bash
composer install
```

---

## 3. STRUKTUR TEST YANG BAIK

### Naming Convention:
```
tests/
├── Unit/
│   ├── Models/
│   │   └── LogPengunduhanTest.php
│   └── Controllers/
│       └── LogPengunduhanControllerTest.php
└── Feature/
    └── Download/
        └── DownloadPdfTest.php
```

### Test Method Naming:
```php
// Format: test_[functionality]_[condition]_[expected_result]

public function test_store_valid_data() { }
public function test_store_missing_email_returns_validation_error() { }
public function test_phone_validation_rejects_letters() { }
```

---

## 4. JENIS-JENIS WHITEBOX TESTING

### 1️⃣ Statement Coverage
Pastikan setiap baris code dijalankan minimal sekali.

```php
// Dalam LogPengunduhanController::store()
public function store(Request $request)
{
    $request->validate([...]); // ✅ Line 1 - tested?
    LogPengunduhan::create([...]); // ✅ Line 2 - tested?
    return response()->json(['success' => true]); // ✅ Line 3 - tested?
}
```

**Test untuk coverage statement:**
```php
public function test_store_valid_data()
{
    // Ini akan jalankan semua 3 baris di atas
    $data = [...];
    $response = $this->postJson('/api/log-pengunduhan/store', $data);
}
```

### 2️⃣ Branch/Decision Coverage
Pastikan setiap IF/ELSE condition ditest.

```php
// Example code with branch:
if ($request->telpon) {
    $data['telpon'] = $request->telpon; // Branch TRUE
} else {
    $data['telpon'] = null; // Branch FALSE
}
```

**Tests:**
```php
public function test_store_with_telpon() { } // Branch TRUE
public function test_store_without_telpon() { } // Branch FALSE
```

### 3️⃣ Loop Coverage
Pastikan loop condition (entry, repeat, exit) ditest.

```php
// Test looping:
public function test_store_multiple_submissions()
{
    for ($i = 1; $i <= 3; $i++) {
        // Entry, repeat 3x, exit
    }
}
```

### 4️⃣ Validation Coverage
Test semua validation rules (required, email, max, min, dll).

---

## 5. MENJALANKAN TESTS

### A. Run Semua Tests
```bash
php artisan test
```

### B. Run Tests Tertentu
```bash
# Run unit tests saja
php artisan test --testsuite=Unit

# Run feature tests saja
php artisan test --testsuite=Feature

# Run test file spesifik
php artisan test tests/Unit/LogPengunduhanControllerTest.php

# Run test method spesifik
php artisan test tests/Unit/LogPengunduhanControllerTest.php --filter test_store_valid_data
```

### C. Generate Coverage Report
```bash
php artisan test --coverage
```

Output akan menunjukkan percentage of code yang ter-cover oleh tests.

---

## 6. CONTOH TEST CASES UNTUK PROJECT INI

### A. LogPengunduhanController Testing

**Test Scenario:**
```
✅ VALID CASE
  → Valid data should be saved + return success

❌ INVALID CASES - REQUIRED FIELDS
  → Missing kategori_pengunduh
  → Missing nama_instansi
  → Missing email_pengunduh

❌ INVALID CASES - VALIDATION RULES
  → Invalid email format
  → kategori_pengunduh > 50 chars
  → nama_instansi > 100 chars
  → email_pengunduh > 100 chars

✅ EDGE CASES
  → Optional fields (telpon, keperluan) empty
  → Multiple submissions
  → Timestamp (waktu_download) is set correctly
```

File test sudah dibuat: `tests/Unit/LogPengunduhanControllerTest.php`

### B. Model Validation Testing

```php
namespace Tests\Unit\Models;

use App\Models\LogPengunduhan;
use Tests\TestCase;

class LogPengunduhanModelTest extends TestCase
{
    public function test_fillable_attributes()
    {
        $expected = [
            'kategori_pengunduh',
            'nama_instansi',
            'email_pengunduh',
            'telpon',
            'keperluan',
            'waktu_download',
        ];

        $this->assertEquals(
            $expected,
            (new LogPengunduhan())->getFillable()
        );
    }

    public function test_primary_key()
    {
        $this->assertEquals('id_download', (new LogPengunduhan())->getKeyName());
    }

    public function test_table_name()
    {
        $this->assertEquals('log_pengunduhan', (new LogPengunduhan())->getTable());
    }
}
```

---

## 7. CHECKLIST WHITEBOX TESTING

Untuk memastikan whitebox testing Anda complete:

### 📋 Pre-Testing
- [ ] Pahami flow/logic dari code yang akan ditest
- [ ] Identifikasi semua conditional branches (if/else)
- [ ] Identifikasi semua loops
- [ ] Identifikasi semua validation rules
- [ ] Buat test data untuk berbagai scenario

### 📋 Test Execution
- [ ] Run all tests: `php artisan test`
- [ ] Check coverage: `php artisan test --coverage`
- [ ] Target coverage >= 80% untuk production code
- [ ] All tests harus PASS ✅

### 📋 Test Documentation
- [ ] Setiap test punya comment yang jelas
- [ ] Setiap test merepresentasi 1 scenario saja
- [ ] Method names deskriptif dan mencerminkan test case

---

## 8. BEST PRACTICES WHITEBOX TESTING

### ✅ DO's:
```php
// ✅ GOOD: Test 1 scenario per method
public function test_store_valid_data() { }
public function test_store_missing_email() { }

// ✅ GOOD: Use descriptive names
public function test_phone_validation_rejects_non_numeric_characters() { }

// ✅ GOOD: Test both success and failure paths
public function test_download_succeeds_when_data_exists() { }
public function test_download_fails_when_data_not_found() { }

// ✅ GOOD: Use assertions clearly
$this->assertDatabaseHas('log_pengunduhan', [
    'email_pengunduh' => 'test@example.com'
]);
```

### ❌ DON'Ts:
```php
// ❌ BAD: Multiple scenarios in 1 test
public function test_everything() {
    // Test valid data, invalid data, edge cases all here
}

// ❌ BAD: Unclear test name
public function test_store() { }

// ❌ BAD: Vague assertions
$this->assertTrue($response->status() === 200);
// Better: $response->assertStatus(200);
```

---

## 9. RUNNING YOUR TESTS SEKARANG

### Step 1: Jalankan semua tests
```bash
php artisan test
```

### Step 2: Jalankan tests dengan coverage
```bash
php artisan test --coverage
```

### Step 3: Jalankan specific test class
```bash
php artisan test tests/Unit/LogPengunduhanControllerTest.php
```

### Step 4: Jalankan specific test method
```bash
php artisan test tests/Unit/LogPengunduhanControllerTest.php --filter test_store_valid_data
```

---

## 10. TIPS DEBUGGING FAILED TESTS

### Jika test FAIL:

1. **Baca error message dengan teliti:**
   ```
   FAILED tests/Unit/LogPengunduhanControllerTest.php::test_store_valid_data
   
   AssertionError: Expected status code 200 but received 422
   ```

2. **Check validation errors:**
   ```php
   $response->dumpSession(); // Lihat session data
   $response->dump(); // Lihat full response
   ```

3. **Add debug output:**
   ```php
   dd($response->json()); // Dump and die
   // atau
   \Log::info('Response:', $response->json());
   ```

4. **Check database state:**
   ```php
   $this->assertDatabaseCount('log_pengunduhan', 1);
   $this->assertDatabaseMissing('log_pengunduhan', ['email_pengunduh' => 'test@example.com']);
   ```

---

## 11. INTEGRASI CI/CD (OPTIONAL)

Untuk GitHub Actions:
```yaml
# .github/workflows/tests.yml
name: Tests
on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: php-actions/composer@v6
      - run: php artisan test --coverage
```

---

## Sekarang mari kita jalankan tests! 🚀
