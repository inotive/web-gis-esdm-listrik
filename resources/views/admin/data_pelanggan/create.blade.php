{{-- Modal: Tambah Data Pelanggan --}}
<div class="modal" id="modalPelanggan" aria-hidden="true" role="dialog" aria-labelledby="modalPelangganTitle">
  <div class="modal-panel" role="document">
    <div class="modal-head">
      <h3 id="modalPelangganTitle">Tambah Data Pelanggan</h3>
      <button type="button" class="btn-x" aria-label="Tutup" data-close>
        <i class="ri-close-line"></i>
      </button>
    </div>

    <form id="formPelanggan" action="#" method="POST" onsubmit="event.preventDefault(); this.closest('.modal').classList.remove('show');">
      @csrf
      <div class="modal-body">

        <label class="field">
          <span class="label">Tipe Pelanggan</span>
          <div class="control">
            <i class="ri-user-3-line"></i>
            <select name="tipe" required>
              <option value="" selected disabled>Pilih Tipe</option>
              <option value="Rumah Tangga">Rumah Tangga</option>
              <option value="Bisnis">Bisnis</option>
              <option value="Industri">Industri</option>
              <option value="Pemerintah">Pemerintah</option>
              <option value="Sosial">Sosial</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
        </label>

        <label class="field">
          <span class="label">Jumlah</span>
          <div class="control">
            <i class="ri-hashtag"></i>
            <input type="number" name="jumlah" min="0" step="1" placeholder="0" required>
          </div>
        </label>

        <label class="field">
          <span class="label">Daya Tersambung</span>
          <div class="control split">
            <i class="ri-flashlight-line"></i>
            <input type="number" name="daya_val" min="0" step="0.01" placeholder="Masukkan angka" required>
            <div class="unit">
              <select name="daya_unit" aria-label="Satuan daya">
                <option value="VA">VA</option>
                <option value="kVA" selected>kVA</option>
                <option value="MVA">MVA</option>
              </select>
            </div>
          </div>
        </label>

        <label class="field">
          <span class="label">Keterangan (opsional)</span>
          <div class="control textarea">
            <textarea name="ket" rows="3" placeholder="Catatan tambahan..."></textarea>
          </div>
        </label>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" data-close>Batal</button>
        <button type="submit" class="btn-save">
          <i class="ri-check-line"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
  /* ===== Simple Modal ===== */
  .modal{ position:fixed; inset:0; background:rgba(2,6,23,.45); display:none; align-items:center; justify-content:center; z-index:100; padding:22px; }
  .modal.show{ display:flex; }
  .modal-panel{
    width: min(760px, 96vw); background:#fff; border:1px solid var(--line); border-radius:16px; box-shadow:var(--shadow-2);
    overflow:hidden; animation:pop .16s ease-out;
  }
  @keyframes pop{ from{ transform:scale(.98); opacity:.6 } to{ transform:scale(1); opacity:1 } }

  .modal-head{ display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px 18px 12px; border-bottom:1px solid var(--line); }
  .modal-head h3{ margin:0; font-size:18px; font-weight:800; color:#1F2937; letter-spacing:-.2px; }
  .btn-x{ width:36px; height:36px; border:1px solid var(--line); border-radius:8px; background:#fff; cursor:pointer; }
  .btn-x:hover{ background:#F8FAFC; }

  .modal-body{ padding:18px; display:grid; grid-template-columns:1fr; gap:14px; }
  .field .label{ display:block; font-weight:600; font-size:13px; color:#475569; margin-bottom:8px; }
  .control{
    display:flex; align-items:center; gap:8px; background:#FCFCFD; border:1px solid #E2E8F0; border-radius:10px; padding:10px 12px;
  }
  .control textarea,
  .control input,
  .control select{ border:none; outline:none; background:transparent; font:inherit; color:#0f172a; width:100%; }
  .control i{ color:#94A3B8; }
  .control.textarea{ padding:0; }
  .control.textarea textarea{ padding:10px 12px; resize:vertical; min-height:80px; }
  .control.split{ padding-right:8px; }
  .control.split .unit{ border-left:1px solid #E2E8F0; padding-left:8px; }
  .control:focus-within{ border-color:#CBD5E1; box-shadow:0 0 0 3px rgba(16,185,129,.12); }

  .modal-foot{ display:flex; justify-content:flex-end; gap:8px; padding:16px 18px; border-top:1px solid var(--line); background:#fff; }
  .btn-cancel{ height:40px; padding:0 14px; border:1px solid var(--line); background:#fff; border-radius:10px; cursor:pointer; }
  .btn-cancel:hover{ background:#F8FAFC; }
  .btn-save{
    height:40px; padding:0 16px; border:none; border-radius:10px; background:var(--accent-2); color:#fff; font-weight:700; cursor:pointer;
    box-shadow:0 10px 22px rgba(34,197,94,.18);
  }
  .btn-save:hover{ filter:brightness(.96); }
</style>
@endpush
