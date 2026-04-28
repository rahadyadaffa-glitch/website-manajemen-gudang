# Changelog

Semua perubahan penting pada proyek **Website Gudang** akan dicatat di dokumen ini.

Format changelog ini mengacu secara sederhana pada prinsip [Keep a Changelog](https://keepachangelog.com/), dengan penandaan versi dan tanggal rilis.

## [0.1.0] - 2026-04-28

### Added
- Setup awal struktur proyek `website-gudang`.
- Penambahan dokumentasi inti di folder `app/docs/`:
  - `01-prd.md`
  - `02-project-context.md`
  - `03-architecture.md`
  - `04-databases-schema.md`
  - `05-ui-guidline.md`
  - `06-ai-rule.md`
- Inisialisasi baseline dokumentasi untuk pengembangan fitur berikutnya.

### Changed
- Penyempurnaan `01-prd.md`:
  - penambahan status dokumen,
  - klarifikasi makna checklist scope,
  - prioritas eksekusi MVP,
  - referensi silang ke dokumen teknis.
- Penyempurnaan `02-project-context.md`:
  - penambahan status dokumen,
  - penegasan strategi image handling berbasis Laravel Storage,
  - penambahan development guardrails sesuai `06-ai-rule.md`.
- Penyempurnaan `03-architecture.md`:
  - penambahan status dokumen,
  - penambahan architecture guardrails agar konsisten dengan `06-ai-rule.md`.
- Penyempurnaan `04-databases-schema.md`:
  - penambahan status dokumen,
  - penambahan schema guardrails untuk sinkronisasi migration/model/docs.
- Penyempurnaan `05-ui-guidline.md`:
  - penambahan status dokumen,
  - penambahan UI implementation guardrails selaras `06-ai-rule.md`.

