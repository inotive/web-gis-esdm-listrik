@php
    $kabupatenOptions = $kabupatenList->map(static function ($item) {
        return [
            'id' => $item->id,
            'provinsi_id' => $item->province_id,
            'nama' => $item->name,
        ];
    })->values()->toArray();

    $kecamatanOptions = $kecamatanList->map(static function ($item) {
        return [
            'id' => $item->id,
            'kabupaten_id' => $item->regency_id,
            'nama' => $item->name,
        ];
    })->values()->toArray();

    $kelurahanOptions = $kelurahanList->map(static function ($item) {
        return [
            'id' => $item->id,
            'kecamatan_id' => $item->district_id,
            'nama' => $item->name,
        ];
    })->values()->toArray();
@endphp
<script>
    const kabupatenData = @json($kabupatenOptions);
    const kecamatanData = @json($kecamatanOptions);
    const kelurahanData = @json($kelurahanOptions);

    const groupBy = (data, key) => data.reduce((accumulator, item) => {
        const groupKey = item[key];
        
        // Skip if key is null or undefined
        if (groupKey == null) {
            return accumulator;
        }

        const normalizedKey = String(groupKey);

        if (!accumulator[normalizedKey]) {
            accumulator[normalizedKey] = [];
        }

        accumulator[normalizedKey].push({
            id: String(item.id),
            nama: item.nama,
        });

        return accumulator;
    }, {});

    const kabupatenByProvinsi = groupBy(kabupatenData, 'provinsi_id');
    const kecamatanByKabupaten = groupBy(kecamatanData, 'kabupaten_id');
    const kelurahanByKecamatan = groupBy(kelurahanData, 'kecamatan_id');

    // Debug: Log data grouping
    console.log('Kabupaten by Provinsi:', kabupatenByProvinsi);
    console.log('Kecamatan by Kabupaten:', kecamatanByKabupaten);
    console.log('Kelurahan by Kecamatan:', kelurahanByKecamatan);

    const fillSelect = (select, items, selectedValue) => {
        const placeholderText = select.dataset.placeholderText
            || (select.querySelector('option[value=""]')?.textContent ?? 'Pilih opsi');

        select.dataset.placeholderText = placeholderText;
        select.innerHTML = '';

        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.textContent = placeholderText;
        select.appendChild(placeholderOption);

        const desiredValue = selectedValue != null ? String(selectedValue) : '';
        let matchFound = false;

        items.forEach((item) => {
            const option = document.createElement('option');
            option.value = String(item.id);
            option.textContent = item.nama;

            if (desiredValue && String(item.id) === desiredValue) {
                option.selected = true;
                matchFound = true;
            }

            select.appendChild(option);
        });

        if (!matchFound && desiredValue) {
            select.value = '';
        }
    };

    const setupRegionSelects = (form) => {
        const provSelect = form.querySelector('select[name="reg_provinces_id"]');
        const kabSelect = form.querySelector('select[name="reg_regencies_id"]');
        const kecSelect = form.querySelector('select[name="reg_districts_id"]');
        const kelSelect = form.querySelector('select[name="reg_villages_id"]');

        if (!provSelect || !kabSelect || !kecSelect || !kelSelect) {
            return;
        }

        [provSelect, kabSelect, kecSelect, kelSelect].forEach((select) => {
            if (!select.dataset.placeholderText) {
                const placeholderOption = select.querySelector('option[value=""]') || select.options[0];
                select.dataset.placeholderText = placeholderOption ? placeholderOption.textContent : 'Pilih opsi';
            }
        });

        const initialValues = {
            kabupaten: kabSelect.value || null,
            kecamatan: kecSelect.value || null,
            kelurahan: kelSelect.value || null,
        };

        const updateKelurahan = (selectedKel = null) => {
            const kecamatanValue = String(kecSelect.value || '');
            const list = kecamatanValue ? (kelurahanByKecamatan[kecamatanValue] || []) : [];
            console.log('Update Kelurahan - Kecamatan ID:', kecamatanValue, 'Found:', list.length, 'items');
            fillSelect(kelSelect, list, selectedKel);
        };

        const updateKecamatan = (selectedKec = null, selectedKel = null) => {
            const kabupatenValue = String(kabSelect.value || '');
            const list = kabupatenValue ? (kecamatanByKabupaten[kabupatenValue] || []) : [];
            console.log('Update Kecamatan - Kabupaten ID:', kabupatenValue, 'Found:', list.length, 'items');
            fillSelect(kecSelect, list, selectedKec);
            updateKelurahan(selectedKel);
        };

        const updateKabupaten = (selectedKab = null, selectedKec = null, selectedKel = null) => {
            const provinsiValue = String(provSelect.value || '');
            const list = provinsiValue ? (kabupatenByProvinsi[provinsiValue] || []) : [];
            console.log('Update Kabupaten - Provinsi ID:', provinsiValue, 'Found:', list.length, 'items');
            fillSelect(kabSelect, list, selectedKab);
            updateKecamatan(selectedKec, selectedKel);
        };

        provSelect.addEventListener('change', () => {
            updateKabupaten(null, null, null);
        });

        kabSelect.addEventListener('change', () => {
            updateKecamatan(null, null);
        });

        kecSelect.addEventListener('change', () => {
            updateKelurahan(null);
        });

        updateKabupaten(initialValues.kabupaten, initialValues.kecamatan, initialValues.kelurahan);
    };

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-region-form="asset"]').forEach((form) => {
            setupRegionSelects(form);
        });
    });
</script>
