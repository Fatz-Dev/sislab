<!-- Modal Backdrop -->
<div
    id="islamicQuoteModalBackdrop"
    class="fixed inset-0 bg-slate-900/65 backdrop-blur-sm z-[9999] flex items-center justify-center p-4 opacity-0 invisible pointer-events-none transition-all duration-300"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
>
    <!-- Modal Card -->
    <div
        id="islamicQuoteModalCard"
        class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl shadow-emerald-950/20 border border-emerald-500/30 overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-300"
        onclick="event.stopPropagation()"
    >
        <!-- Header -->
        <div class="relative bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-600 px-6 py-5 text-white overflow-hidden">
            <!-- Pattern aksen -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:14px_14px] pointer-events-none"></div>
            
            <!-- Close Button Top -->
            <button
                type="button"
                id="islamicQuoteCloseBtnTop"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center transition-all duration-200 hover:scale-105 z-10 cursor-pointer"
                title="Tutup"
            >
                <i class="bi bi-x-lg text-sm"></i>
            </button>

            <!-- Badge Tema -->
            <div class="relative z-10">
                <span id="islamicQuoteTheme" class="inline-flex items-center gap-1.5 bg-white/20 border border-white/30 px-3 py-1 rounded-full text-xs font-semibold text-amber-200 mb-2 tracking-wide">
                    <i class="bi bi-moon-stars-fill text-[11px]"></i> Mutiara Hikmah & Sains
                </span>
                <h3 class="text-base sm:text-lg font-bold tracking-tight text-white flex items-center gap-2 m-0">
                    <span>بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</span>
                </h3>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4">
            <!-- Teks Arab -->
            <div
                id="islamicQuoteArabic"
                dir="rtl"
                class="text-xl sm:text-2xl leading-loose text-emerald-950 text-right font-semibold pb-3 border-b border-dashed border-slate-200 font-serif"
            >
                إِنَّ فِي خَلْقِ السَّمَاوَاتِ وَالْأَرْضِ وَاخْتِلَافِ اللَّيْلِ وَالنَّهَارِ لَآيَاتٍ لِّأُولِي الْأَلْبَابِ
            </div>

            <!-- Terjemahan -->
            <div
                id="islamicQuoteTranslation"
                class="text-sm leading-relaxed text-slate-700 italic pl-4 border-l-4 border-emerald-500 my-2"
            >
                "Sesungguhnya dalam penciptaan langit dan bumi, dan silih bergantinya malam dan siang terdapat tanda-tanda (kebesaran Allah) bagi orang-orang yang berakal."
            </div>

            <!-- Sumber / Dalil -->
            <div class="flex items-center justify-between gap-2 text-xs text-slate-500 font-medium pt-1">
                <span id="islamicQuoteSource" class="inline-flex items-center gap-1.5 text-emerald-700 font-bold">
                    <i class="bi bi-bookmark-check-fill text-emerald-600"></i> QS. Ali 'Imran: 190
                </span>
                <span class="text-[11px] text-slate-400">SISLAB Fisika UIN Ar-Raniry</span>
            </div>

            <!-- Catatan Refleksi / Hikmah -->
            <div id="islamicQuoteHikmahBox" class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-xs text-emerald-800 flex items-start gap-2.5 leading-relaxed mt-2">
                <i class="bi bi-lightbulb-fill shrink-0 text-emerald-600 text-sm mt-0.5"></i>
                <span id="islamicQuoteHikmah">Mari niatkan praktikum dan riset fisika hari ini sebagai bentuk tafakur atas keagungan ciptaan Allah SWT.</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3">
            <button
                type="button"
                id="islamicQuoteCloseBtnBottom"
                class="inline-flex items-center gap-1.5 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-emerald-800 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg shadow-emerald-600/20 hover:-translate-y-0.5 cursor-pointer border border-emerald-300"
            >
                <i class="bi bi-check2-circle text-base"></i>
                <span>Alhamdulillah, Mengerti</span>
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    // ── Koleksi Quotes Pilihan Bernuansa Islami & Sains / Adab Ilmu ──
    const islamicQuotes = [
        {
            theme: "Tafakur Sains & Alam Semesta",
            arabic: "إِنَّ فِي خَلْقِ السَّمَاوَاتِ وَالْأَرْضِ وَاخْتِلَافِ اللَّيْلِ وَالنَّهَارِ لَآيَاتٍ لِّأُولِي الْأَلْبَابِ",
            translation: "Sesungguhnya dalam penciptaan langit dan bumi, dan silih bergantinya malam dan siang terdapat tanda-tanda (kebesaran Allah) bagi orang-orang yang berakal.",
            source: "QS. Ali 'Imran: 190",
            hikmah: "Eksperimen dan kajian fisika adalah salah satu sarana teragung dalam merenungi hukum-hukum keteraturan ciptaan Allah."
        },
        {
            theme: "Kewajiban Menuntut Ilmu",
            arabic: "طَلَبُ الْعِلْمِ فَرِيضَةٌ عَلَى كُلِّ مُسْلِمٍ",
            translation: "Menuntut ilmu itu adalah kewajiban bagi setiap muslim.",
            source: "HR. Ibnu Majah No. 224",
            hikmah: "Niatkan setiap aktivitas belajar, asistensi, dan pengolahan data praktikum sebagai ibadah yang bernilai pahala di sisi Allah."
        },
        {
            theme: "Derajat Penuntut Ilmu",
            arabic: "يَرْفَعِ اللَّهُ الَّذِينَ آمَنُوا مِنكُمْ وَالَّذِينَ أُوتُوا الْعِلْمَ دَرَجَاتٍ",
            translation: "Allah akan meninggikan orang-orang yang beriman di antaramu dan orang-orang yang diberi ilmu pengetahuan beberapa derajat.",
            source: "QS. Al-Mujadilah: 11",
            hikmah: "Pencapaian akademis dan keilmuan yang dilandasi iman akan membawa keberkahan dan kehormatan di dunia serta akhirat."
        },
        {
            theme: "Jalan Menuju Surga",
            arabic: "مَنْ سَلَكَ طَرِيقًا يَلْتَمِسُ فِيهِ عِلْمًا سَهَّلَ اللَّهُ لَهُ بِهِ طَرِيقًا إِلَى الْجَنَّةِ",
            translation: "Barangsiapa menelusuri jalan untuk mencari ilmu, maka Allah akan memudahkan baginya jalan menuju surga.",
            source: "HR. Muslim No. 2699",
            hikmah: "Setiap langkah menuju laboratorium dan ketekunan mengamati fenomena alam adalah jembatan menuju keridhaan-Nya."
        },
        {
            theme: "Prinsip Itqan & Ketelitian Praktikum",
            arabic: "إِنَّ اللَّهَ يُحِبُّ إِذَا عَمِلَ أَحَدُكُمْ عَمَلًا أَنْ يُتْقِنَهُ",
            translation: "Sesungguhnya Allah menyukai jika seseorang di antara kalian melakukan suatu pekerjaan, ia melakukannya dengan itqan (teliti, bersungguh-sungguh, dan profesional).",
            source: "HR. At-Thabrani & Al-Baihaqi",
            hikmah: "Kalibrasi instrumen, pencatatan data yang jujur, dan ketertiban laboratorium merupakan bentuk penerapan itqan dalam sains."
        },
        {
            theme: "Perbedaan Ahli Ilmu",
            arabic: "قُلْ هَلْ يَسْتَوِي الَّذِينَ يَعْلَمُونَ وَالَّذِينَ لَا يَعْلَمُونَ ۗ إِنَّمَا يَتَذَكَّرُ أُولُو الْأَلْبَابِ",
            translation: "Katakanlah: Adakah sama orang-orang yang mengetahui dengan orang-orang yang tidak mengetahui? Sesungguhnya orang yang berakallah yang dapat menerima pelajaran.",
            source: "QS. Az-Zumar: 9",
            hikmah: "Ilmu pengetahuan membedakan cara pandang kita terhadap dunia, menjadikan kita senantiasa rendah hati dan arif bersikap."
        },
        {
            theme: "Adab & Kunci Kesuksesan Ilmu",
            arabic: "أَخِي لَنْ تَنَالَ العِلْمَ إِلَّا بِسِتَّةٍ: ذَكَاءٌ، وَحِرْصٌ، وَاجْتِهَادٌ، وَبُلْغَةٌ، وَصُحْبَةُ أُسْتَاذٍ، وَطُولُ زَمَانٍ",
            translation: "Saudaraku, engkau tidak akan mendapatkan ilmu kecuali dengan enam perkara: kecerdasan, rasa haus ilmu, kesungguhan, bekal, bimbingan dosen/guru, dan waktu yang panjang.",
            source: "Pesan Imam Asy-Syafi'i",
            hikmah: "Muliakanlah para dosen dan laboran, serta bersabarlah dalam menuntaskan eksperimen demi kematangan pemahaman."
        },
        {
            theme: "Observasi & Pengamatan Ilmiah",
            arabic: "قُلِ انْظُرُوا مَاذَا فِي السَّمَاوَاتِ وَالْأَرْضِ",
            translation: "Katakanlah: Perhatikanlah apa yang ada di langit dan di bumi!",
            source: "QS. Yunus: 101",
            hikmah: "Pengamatan empiris terhadap gerak, gaya, gelombang, dan partikel adalah cerminan dari perintah Al-Qur'an untuk meneliti alam."
        }
    ];

    let currentQuoteIndex = -1;

    function renderQuote(index) {
        if (index < 0 || index >= islamicQuotes.length) {
            index = Math.floor(Math.random() * islamicQuotes.length);
        }
        currentQuoteIndex = index;
        const q = islamicQuotes[index];

        const themeEl = document.getElementById('islamicQuoteTheme');
        const arabicEl = document.getElementById('islamicQuoteArabic');
        const transEl = document.getElementById('islamicQuoteTranslation');
        const sourceEl = document.getElementById('islamicQuoteSource');
        const hikmahEl = document.getElementById('islamicQuoteHikmah');

        if (themeEl) themeEl.innerHTML = `<i class="bi bi-moon-stars-fill text-[11px]"></i> ${q.theme}`;
        if (arabicEl) arabicEl.textContent = q.arabic;
        if (transEl) transEl.textContent = `"${q.translation}"`;
        if (sourceEl) sourceEl.innerHTML = `<i class="bi bi-bookmark-check-fill text-emerald-600"></i> ${q.source}`;
        if (hikmahEl) hikmahEl.textContent = q.hikmah;
    }

    function openModal() {
        const backdrop = document.getElementById('islamicQuoteModalBackdrop');
        const card = document.getElementById('islamicQuoteModalCard');
        if (!backdrop || !card) return;
        
        // Pilih quote acak saat dibuka
        renderQuote(Math.floor(Math.random() * islamicQuotes.length));
        
        // Animasi transisi Tailwind untuk backdrop
        backdrop.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
        backdrop.classList.add('opacity-100', 'visible', 'pointer-events-auto');

        // Animasi transisi Tailwind untuk kartu modal
        card.classList.remove('scale-95', 'translate-y-3', 'opacity-0');
        card.classList.add('scale-100', 'translate-y-0', 'opacity-100');
        
        backdrop.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const backdrop = document.getElementById('islamicQuoteModalBackdrop');
        const card = document.getElementById('islamicQuoteModalCard');
        if (!backdrop || !card) return;

        // Animasi keluar Tailwind untuk backdrop
        backdrop.classList.remove('opacity-100', 'visible', 'pointer-events-auto');
        backdrop.classList.add('opacity-0', 'invisible', 'pointer-events-none');

        // Animasi keluar Tailwind untuk kartu modal
        card.classList.remove('scale-100', 'translate-y-0', 'opacity-100');
        card.classList.add('scale-95', 'translate-y-3', 'opacity-0');

        backdrop.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    // Expose global function jika ada menu / header yang ingin memicu modal ini kembali
    window.showIslamicQuoteModal = openModal;
    window.closeIslamicQuoteModal = closeModal;

    document.addEventListener('DOMContentLoaded', function() {
        // Gunakan session ID Laravel agar setiap login baru (sesi baru) otomatis mereset status
        const SESSION_ID = '{{ session()->getId() }}';
        const STORAGE_KEY = 'sislab_quote_' + SESSION_ID;

        // Bersihkan key quote lama dari sesi sebelumnya agar storage rapi
        try {
            Object.keys(sessionStorage).forEach(function(key) {
                if (key.startsWith('sislab_quote_') && key !== STORAGE_KEY) {
                    sessionStorage.removeItem(key);
                }
                if (key === 'sislab_islamic_quote_shown') {
                    sessionStorage.removeItem(key);
                }
            });
        } catch (e) {}

        // 1. Cek apakah di sesi login saat ini sudah pernah ditampilkan
        const hasSeenQuote = sessionStorage.getItem(STORAGE_KEY);

        if (!hasSeenQuote) {
            // Berikan jeda 800ms agar halaman tampil tenang lebih dulu
            setTimeout(function() {
                openModal();
                // Tandai di sessionStorage untuk sesi login ini
                sessionStorage.setItem(STORAGE_KEY, 'true');
            }, 800);
        }

        // 2. Event Listeners untuk Tutup
        const closeBtnTop = document.getElementById('islamicQuoteCloseBtnTop');
        const closeBtnBottom = document.getElementById('islamicQuoteCloseBtnBottom');
        const backdrop = document.getElementById('islamicQuoteModalBackdrop');
        const nextBtn = document.getElementById('islamicQuoteNextBtn');

        if (closeBtnTop) closeBtnTop.addEventListener('click', closeModal);
        if (closeBtnBottom) closeBtnBottom.addEventListener('click', closeModal);

        // Tutup saat klik di luar kartu (backdrop)
        if (backdrop) {
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) {
                    closeModal();
                }
            });
        }

        // Tutup saat tombol ESC ditekan
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                closeModal();
            }
        });
    });
})();
</script>
